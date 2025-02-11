<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Participantes extends MY_Controller {

    private $inscricao        =  'tbl_participantes';
    private $cartao           =  'tbl_pagamentos';
    private $eventos          =  'tbl_eventos';
    private $admin_eventos    =  'tbl_admin_eventos';
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('participantes_model');
    }

	//
    // Lista de forma paginada e processada dos participantes dos eventos
    //	    
    public function listar_participantes_ajax_server($id_evento)
    {
		$Data['ID_Evento']  =  $id_evento;
		$Data['Status']     =  $this->input->post('Status');
		$Data['Numero']     =  $this->input->post('NN');		
		
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_Inscrito', 'Nome', 'CPF', 'Pedido', 'Pago', 'Presenca', 'Data_Hora_Presenca', 'Data_Cadastro');
        
        // DB table to use
        $sTable = $this->inscricao;
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
                
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
            }
            
            $this->db->where("(". $aColumns[1] ." LIKE '%". $sSearch ."%' || ". $aColumns[2] ." LIKE '%". $this->db->escape_like_str(str_replace('-', '', str_replace('.', '', $sSearch))) ."%' || ". $aColumns[3] ." LIKE '%". $sSearch ."%' || ". $aColumns[4] ." LIKE '%". $sSearch ."%' || ". $aColumns[5] ." LIKE '%". $sSearch ."%')");
        }      
        
        // Filtro por status
        if($Data['Status'] > 0)
        {
            $Status = ($Data['Status'] == 2)? 1 : '!= 2';
            
            $this->db->where('Pago', $Status);
        }    
        
        // FILTRO do NN
        if($Data['Numero'] != "")
        {
            $this->db->where('Pedido', $Data['Numero']);
        }

        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        
        // EXECUTA a query
        $rResult = $this->db
                        ->where('id_Evento_Link', $Data['ID_Evento'])
                        ->get($sTable);    
        
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iFilteredTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        foreach($rResult->result_array() as $aRow)
        {
			$row = array();
            
            $aColumns = $aColumns;
            
            foreach($aColumns as $col)
            {
                $status = '';
                $presenca = '';
				
				if($aRow['Pago'] == 0)
				{
					$status = '<strong>Inscrição pendente</strong>';	
				}

				if($aRow['Pago'] == 1)
				{
					$status = '<strong>Inscrição paga</strong>';		
				}

				if($aRow['Presenca'] == 0)
				{
					$presenca = '<strong>Não presente</strong>';	
				}

				if($aRow['Presenca'] == 1)
				{
					$presenca = '<a href="javascript:Rollback('.$aRow['id_Inscrito'].')"><strong>Presente</strong></a>';		
				}

                $CPF = ($aRow['CPF'] == NULL)? '99999999999' : $aRow['CPF'];

				$row = array($aRow['id_Inscrito'], mb_strtoupper($aRow['Nome']), formatCPF_CNPJ($CPF), gera_matricula($aRow['Pedido']), $presenca, data_time_us_to_br($aRow['Data_Hora_Presenca']), data_us_to_br($aRow['Data_Cadastro']), $status);

				$row[] = $aRow[str_replace('a.', '', $col)];
			}

			$output['aaData'][] = $row;
        }

        echo @json_encode($output);
    } 	
	
	//
	// Exibe um grid vazio
	//	
    public function listar_pagamentos_ajax_vazio()
	{
        echo $this->financeiro_model->listar_pagamentos_json_vazio();
    }
	
    //
    // Metodo que permite a alteração dos dados do inscrito
    //
    public function alterar_inscrito($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/inscritos'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataBody'] 	 =    $this->participantes_model->get_inscrito($id);
        $this->data['DataOrder'] 	 =    $this->participantes_model->get_pedido($this->data['DataBody']->Pedido);
        $this->data['TipoUsuario']   =    $this->session->userdata('intTipo');

        $this->usable('participantes/inscrito');
    }

    //
    //  Chama o metodo do model para poderem alterar os dados do inscrito
    //
    public function salvar_inscrito()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_inscrito($this->input->post('ID_Inscrito'));
        }
        else
        {        
            //
            //  Iniciando o processo de exclusão e atualização
            // 
            $Red['id_evento']        =   $this->input->post('ID_Evento');
            $Red['id_evento_link']   =   $this->input->post('ID_Evento_Link');
            
            $Data['id_Inscrito']     =   $this->input->post('ID_Inscrito');
            $Data['Nome']            =   trim(mb_strtoupper($this->input->post('str_Nome')));
            $Data['CPF']             =   trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));

            //
            // Emissão do QR Code do Comprador
            //        
            if($this->input->post('Status') == 2)
            {
                //
                // Codigo do Evento
                //
                $Codigo  =  $Data['CPF'] . '&' . $Red['id_evento'] . '&' . $this->input->post('Status');

                $this->QrCode($Codigo);
            }            
            
            //
            //  Salvando as informações do Inscrito
            //            
            $this->participantes_model->update_inscrito($Data);

            //
            //  Redirecionamento
            //
            redirect(base_url() . 'links/visualizar/' . $Red['id_evento_link']);
        }
    }    

    //
    // Chama o metodo do model para "exclusão" do registro
    //
    public function excluir_inscrito($id)
    {
        //
        //  Iniciando o processo de exclusão e atualização
        // 
        $this->participantes_model->excluir_geral($id);
    }
    
    //
    // Metodo que gera e salva o QR Code no e-mail
    //    
    public function QrCode($Codigo)
    {
        //
        // Incluir Composer
        //
        include './vendor/autoload.php';

        //
        // Configurações do QRCode
        //
        $options = new QROptions([
            'version'    => 5,
            'eccLevel'   => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG, 
            'imageBase64' => false 
        ]);

        //
        // invoca uma nova instância QRCode
        //
        $qrcode = new QRCode($options);
        
        //
        // Gerar a imagem e salvar a imagem do QR no servidor
        //
        $qrcode->render($Codigo, '../assets/qrcodes/' . $Codigo . '.png');
    }

    //
    //  Metodo que verifica se CPF é valido
    //
    public function duplicidade($CPF)
    { 
        $Data['ID']     =   $this->input->post('ID_Evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '', $CPF));

        if($this->participantes_model->check_duplicidade_evento_validation($Data) == 1)
        { 
            // CPF inválido
            $this->form_validation->set_message('duplicidade', '<p><strong>CPF</strong> já se encontra cadastrado.</p>');

            return false;
        } 
        else
        { 
            return true;
        } 
    }

    /*
     *  @param string $cpf O CPF com ou sem pontos e traço
     *  @return bool True para CPF correto - False para CPF incorreto
     */
    public function valida_cpf($cpf = false) 
    {
        $cpf  =  str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        /**
         * Multiplica dígitos vezes posições 
         *
         * @param string $digitos Os digitos desejados
         * @param int $posicoes A posição que vai iniciar a regressão
         * @param int $soma_digitos A soma das multiplicações entre posições e digitos
         * @return int Os digitos enviados concatenados com o último dígito
         *
         */
        if(!function_exists('calc_digitos_posicoes'))
        {
            function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0) 
            {
                // Faz a soma dos digitos com a posição
                // Ex. para 10 posições: 
                //   0    2    5    4    6    2    8    8   4
                // x10   x9   x8   x7   x6   x5   x4   x3  x2
                // 	 0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                for ($i = 0; $i < strlen($digitos); $i++) 
                {
                    $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                    $posicoes--;
                }

                // Captura o resto da divisão entre $soma_digitos dividido por 11
                // Ex.: 196 % 11 = 9
                $soma_digitos = $soma_digitos % 11;

                // Verifica se $soma_digitos é menor que 2
                if($soma_digitos < 2) 
                {
                    // $soma_digitos agora será zero
                    $soma_digitos = 0;
                } 
                else 
                {
                    // Se for maior que 2, o resultado é 11 menos $soma_digitos
                    // Ex.: 11 - 9 = 2
                    // Nosso dígito procurado é 2
                    $soma_digitos = 11 - $soma_digitos;
                }

                // Concatena mais um digito aos primeiro nove digitos
                // Ex.: 025462884 + 2 = 0254628842
                $cpf = $digitos . $soma_digitos;

                // Retorna
                return $cpf;
            }
        }

        // Verifica se o CPF foi enviado
        if(!$cpf) 
        {
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }

        // Remove tudo que não é número do CPF
        // Ex.: 025.462.884-23 = 02546288423
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o CPF tem 11 caracteres
        // Ex.: 02546288423 = 11 números
        if(strlen($cpf) != 11) 
        {
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }
        
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
        }          

        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($cpf, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = calc_digitos_posicoes($digitos);

        // Faz o cálculo dos 10 digitos do CPF para obter o último dígito
        $novo_cpf = calc_digitos_posicoes($novo_cpf, 11);

        // Verifica se o novo CPF gerado é identico ao CPF enviado
        if($novo_cpf === $cpf)
        {
            // CPF válido
            return true;
        } 
        else 
        {
            // CPF inválido
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }
    }    
}