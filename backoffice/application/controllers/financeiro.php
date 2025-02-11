<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Financeiro extends MY_Controller {

    private $pagamentos   =  'tbl_pagamentos';
	
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('eventos_model');
        $this->load->model('financeiro_model');
        $this->load->model('participantes_model');
    }
	 
    //
    // Lista as familias de acordo com a estudantes logada
    //
    public function index()
    {
        $this->data['AddCss']   	     =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']    =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'links/links_financeiro'));
        
        $this->usable('links');
    }

    //
    // Método que visualiza os eventos
    //    
    public function visualizar($id_evento)
    {
        $this->data['AddCss']   	    =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'jquery.dataTables.delay.min', 'dataTables.fnReloadAjax', 'financeiro/financeiro'));
        $this->data['AddData']          =   $this->eventos_model->get_eventos($id_evento);
        $this->data['AddStatus']        =   array(NULL => 'Selecione um Status',
                                                     2 => 'Pagos',
                                                     99 => 'Negados');

        $this->usable('financeiro');		
    }    
	
    //
    // Lista os eventos para eu checar os pagamentos
    //	
    public function listar_ajax()
    {
        echo $this->eventos_model->listar_eventos_json();
    }
    
    //
    // Lista os pagamentos paginados
    //    
    public function listar_cartao_ajax_server($id_evento)
    {
		$Data['ID_Evento']  =  $id_evento;
		$Data['Status']     =  $this->input->post('Status');
		$Data['Numero']     =  $this->input->post('NN');		
		
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('Pedido', 'id_Evento', 'Valor', 'Nome', 'CPF', 'Email', 'Tipo_Pagamento', 'Quantidade_Ingressos', 'Parcelas', 'Status_Pagamento', 'Data_Pagamento');
        
        // DB table to use
        $sTable = $this->pagamentos;
    
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
            
            $this->db->where("(". $aColumns[1] ." LIKE '%". $sSearch ."%' || ". $aColumns[4] ." LIKE '%". $this->db->escape_like_str(str_replace('-', '', str_replace('.', '', $sSearch))) ."%' || ". $aColumns[3] ." LIKE '%". $sSearch ."%' || ". $aColumns[2] ." LIKE '%". $sSearch ."%' || ". $aColumns[5] ." LIKE '%". $sSearch ."%')");
        }      
        
        // Filtro por status
        if($Data['Status'] == 2)
        {
            $this->db->where('Status_Pagamento', $Data['Status']);
        }
        
        // Filtro por status
        if($Data['Status'] == 99)
        {
            $this->db->where('Status_Pagamento !=', 2);
        }         
        
        // FILTRO do NN
        if($Data['Numero'] != "")
        {
            $this->db->where('Pedido', $Data['Numero']);
        }
        
        // FILTRO com evento
        if($Data['ID_Evento'] > 0)
        {
            $this->db->where('id_Evento_Link', $Data['ID_Evento']);
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
				
				if($aRow['Status_Pagamento'] == 0)
				{
					$status = '<strong>Inscrição pendente para pagamento.</strong>';	
				}

				if($aRow['Status_Pagamento'] == 1)
				{
					$status = '<strong>Inscrição ainda pendente aguardando aprovação.</strong>';		
				}

				if($aRow['Status_Pagamento'] == 2)
				{
					$status = '<strong>Inscrição paga.</strong>';		
				}

				if($aRow['Status_Pagamento'] == 3)
				{
					$status = '<strong>Inscrição negada pela operadora de cartão.</strong>';		
				}

				if($aRow['Status_Pagamento'] == 4)
				{
					$status = '<strong>Inscrição negada pela operadora de cartão.</strong>';		
				}                

				if($aRow['Status_Pagamento'] == 5)
				{
					$status = '<strong>Inscrição negada pela operadora de cartão.</strong>';		
				}															

				if($aRow['Status_Pagamento'] == 6)
				{
					$status = '<strong>Inscrição negada pela operadora de cartão.</strong>';		
				}

				if($aRow['Status_Pagamento'] == 7)
				{
					$status = '<strong>Inscrição rejeitada.</strong>';		
                }

				if($aRow['Status_Pagamento'] == 99)
				{
					$status = '<strong>Pedido negado pela operadora de cartão.</strong>';		
				}                
                
                //
                // Tipo de pagamento
                //
                $Tipo = '';

                if($aRow['Tipo_Pagamento'] == 1)
                {
                    $Tipo = 'CARTÃO DE CRÉDITO';
                }

                if($aRow['Tipo_Pagamento'] == 2)
                {
                    $Tipo = 'PIX';
                }                

                if($aRow['Tipo_Pagamento'] == 3)
                {
                    $Tipo = 'DINHEIRO';    
                } 

                if($aRow['Tipo_Pagamento'] == 4)
                {
                    $Tipo = 'CARTÃO DE DÉBITO';   
                }
                
                if($aRow['Tipo_Pagamento'] == 0)
                {
                    $Tipo = 'NÃO FINALIZOU';    
                } 
                
                $Parcelas = ($aRow['Parcelas'] == 0 || $aRow['Parcelas'] == 1)? 'À vista' : $aRow['Parcelas'];

                $CPF = ($aRow['CPF'] == NULL)? '99999999999' : $aRow['CPF'];

				$row = array($aRow['Pedido'], mb_strtoupper($aRow['Nome']), formatCPF_CNPJ($CPF), mb_strtolower($aRow['Email']), inclui_zero_esq($aRow['Pedido']), 'R$ ' . num_to_user($aRow['Valor']), $Parcelas, $Tipo, data_us_to_br($aRow['Data_Pagamento']), $status);
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
    public function alterar_pedido($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/inscritos'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataOrder'] 	=    $this->participantes_model->get_pedido($id);

        //
        // Status de pagamento
        //
        $this->data['AddStatus']    =   array(2 => 'Evento Pago', 3 => 'Evento Não Pago');  
        
        $this->usable('financeiro/alterar_pedido');
    }

    //
    //  Chama o metodo do model para poderem alterar os dados do pedido
    //
    public function salvar_pedido()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_pedido($this->input->post('ID_Pedido'));
        }
        else
        {        
            //
            //  Iniciando o processo de atualização
            // 
            $Red['id_evento_link']   =   $this->input->post('ID_Evento_Link');
            
            $Data['Pedido']              =   $this->input->post('ID_Pedido');
            $Data['Valor']               =   num_to_db($this->input->post('fl_Valor'));
            $Data['Status_Pagamento']    =   $this->input->post('Status');

            //
            //  Salvando as informações do Pedido
            //            
            $this->financeiro_model->update_pedido($Data);

            //
            // Controle de ingressos
            //
            $Controle['id_evento_link']       =    $this->input->post('ID_Evento_Link');
            $Controle['vendidos']             =    $this->input->post('Quantidade_Ingressos');
            $Controle['status']               =    $Data['Status_Pagamento'];
            
            //
            //  Redirecionamento
            //
            redirect(base_url() . 'financeiro/visualizar/' . $Red['id_evento_link']);
        }
    }
    
    //
    // Chama o metodo do model para "exclusão" do registro
    //
    public function excluir_pedido($id)
    {
        //
        //  Iniciando o processo de exclusão e atualização
        // 
        $this->financeiro_model->excluir_geral($id);
    }
    
    //
    //  Metodo que verifica se CPF é valido
    //
    public function valida_status($CPF)
    { 
        $Status    =   $this->input->post('Status');

        if($Status == 3)
        {
            return true;
        }
        
        $Data['ID']     =   $this->input->post('ID_Evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '', $CPF));

        if($this->participantes_model->check_duplicidade_evento_validation_v2($Data) == 1)
        { 
            // CPF inválido
            $this->form_validation->set_message('valida_status', '<p><strong>CPF</strong> já se encontra pago com outro pedido.</p>');

            return false;
        } 
        else
        { 
            return true;
        } 
    }    
}