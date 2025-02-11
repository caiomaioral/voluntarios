<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Eventos extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('eventos_model');
    }
	 
    //
    //  Lista as familias de acordo com a estudantes logada
    //
    public function index()
    {
        $this->data['AddCss']   	     =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']    =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'eventos/eventos'));
        
        $this->usable('eventos');
    }

    //
    // Lista as familias de acordo com a estudantes logada
    //	
    public function listar_ajax()
    {
        echo $this->eventos_model->listar_eventos_json();
    }
    
    //
    // Metodo para inclusão de eventos (bola eventos)
    //
    public function incluir_eventos()
    {
        $this->data['AddCss']           =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']   =   load_js(array('eventos/incluir'));
        $this->data['AddIgreja']        =   $this->eventos_model->get_igrejas_dropdown_search();
        
        $this->usable('eventos/incluir_evento');
    }    

    //
    // Metodo que inclui um evento para o eventos
    //
    public function enviar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_eventos();
        }
        else
        {
            $Data['id_igreja']   =   trim($this->input->post('Igreja'));
            $Data['titulo']      =   trim($this->input->post('str_Titulo'));

            //
            // Efetua a persistência no banco de dados
            //	                
            $this->eventos_model->set_eventos($Data);

            //
            // Pega o ID incluido
            //
            $id_evento  =  $this->db->insert_id();

            //
            // Json para incluir no SIAF
            //
            $data_string = 
            '{
                "Filial": "'.$Data['id_igreja'].'",
                "Id": '.$id_evento.',
                "Descricao": "'.$Data['titulo'].'",
                "Emissao": "'.date('Ymd').'",
                "DataInicio": "'.date('Ymd').'"
            }';

            $url = 'http://siaffiliais.com.br:7070/ws/WSEventos';
        
            //
            //  Initiate curl
            //
            $ch = curl_init();

            //
            // Set the timeout
            //
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            //
            // Disable SSL verification
            //
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            //
            // Passar o SSL
            //
            curl_setopt ($ch, CURLOPT_SSLVERSION, 6);        
            
            //
            // Passar o Header
            //            
            curl_setopt($ch, CURLOPT_HEADER, true);
            
            //
            // Set the header
            //
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',
                'Connection: keep-alive',                                                                              
                'Cache-Control: no-cache',
                'Auth-Token: 2f12bcff-8eaa-4963-a4f8-90dba5b9bfcd'
            ));             
            
            //
            // Set the method
            //
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                                     
            
            //
            // Set the Json
            //
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);        
            
            //
            // Will return the response, if false it print the response
            //
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            //
            // Set the url
            //
            curl_setopt($ch, CURLOPT_URL, $url);

            //
            // Execute
            //
            $result = curl_exec($ch);

            //
            // Closing
            //
            curl_close($ch);            

            //
            // Chama a view de sucesso
            //            
            $this->usable('sucesso');
        }
    }

    //
    // Metodo que exibe a janela com os dados dos eventos
    //
    public function alterar($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/alterar'));
        $this->data['AddIgreja']            =   $this->eventos_model->get_igrejas_dropdown_search();
        $this->data['DataBody'] 	        =   $this->eventos_model->get_eventos_geral($id);

        $this->usable('eventos/alterar_evento');
    }

    //
    // Metodo que salva a alteração de um evento
    //
    public function salvar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('ID'));	
        }
        else
        {
            $Data['id_evento']    =   $this->input->post('ID');
            $Data['id_igreja']    =   trim($this->input->post('Igreja'));
            $Data['titulo']       =   trim($this->input->post('str_Titulo'));

            $this->eventos_model->update_eventos($Data);

            redirect(base_url() . 'eventos', 'refresh');						
        }
    }

    //
    // Chama o metodo do model para "exclusão" do registro
    //
    public function excluir($id)
    {
        $this->eventos_model->excluir($id);
    }

    //
    // Metodo que verifica se a URL é repetida
    //
    public function uniqueURL($URL)
    {
        if($this->financeiro_model->get_url($URL) == false)
        {
            $this->form_validation->set_message('uniqueURL', 'Já existe um evento <strong>ATIVO</strong> com o endereço <strong>'.mb_strtoupper($URL).'</strong>.');
            return false;	            
        }
        else 
        {
            return true;
        }
    }

    //
    // Metodo que verifica se CPF é valido
    //
    public function duplicidade_form()
    { 
        $Data['ID']   =  $this->input->get('evento');
        $Data['CPF']  =  str_replace('-', '', str_replace('.', '',$this->input->get('str_CPF')));
        
        if($this->eventos_model->check_duplicidade_evento($Data) == true)
        { 
            return true;
        } 
        else
        { 
            $this->form_validation->set_message('duplicidade_form', 'Já existe esse <strong>CPF</strong> cadastrado nesse evento</strong>.');
            return false;	
        } 
    }     
    
    /*
     *  Metodo que verifica se foi upado um arquivo 
     */
    public function handle_upload()
    {
        if(isset($_FILES['str_Arquivo']) && !empty($_FILES['str_Arquivo']['name']))
        {
            $ext['extensoes'] = array('jpg', 'jpeg', 'png');

            $Nome_Arquivo = mb_strtolower($_FILES['str_Arquivo']['name']);

            $extensao = explode('.', $Nome_Arquivo);
            $extensoes = end($extensao);

            if(array_search($extensoes, $ext['extensoes']) === false)
            {
                $this->form_validation->set_message('handle_upload', 'Você deve usar um arquivo de extensão <strong>JPG ou PNG</strong>.');
                return false;			
            }
            else
            {				
                return true;
            }
        }
        else
        {
            $this->form_validation->set_message('handle_upload', 'Você deve selecionar uma <strong>IMAGEM DE CABEÇALHO</strong>.');
            return false;
        }
    }

    /*
     *  Metodo que permite a alteração dos dados do inscrito
     */
    public function alterar_inscrito($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/inscritos'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataBody'] 	=    $this->financeiro_model->get_inscrito($id);
        
        //
        // Tipo de evento = 1 (Eventos)
        //
        $this->data['DataDependente']  =  $this->financeiro_model->get_dependentes($id);    

        $this->usable('eventos/inscrito');
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
            $Red['id_eventos']     =   $this->input->post('ID');
            
            $Data['id_Inscrito']   =   $this->input->post('ID_Inscrito');
            $Data['str_Nome']      =   trim(mb_strtoupper($this->input->post('str_Nome')));
            $Data['str_CPF']       =   trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
            $Data['str_Email']     =   trim(mb_strtolower($this->input->post('str_Email')));
            $Data['dec_Valor']     =   num_to_db($this->input->post('str_Valor'));
            
            $Cielo['id_inscrito']       =   $this->input->post('ID_Inscrito');
            $Cielo['customer_email']    =   trim(mb_strtolower($this->input->post('str_Email')));
            $Cielo['valor']             =   num_to_db($this->input->post('str_Valor'));  
            
            //
            //  Salvando as informações do Inscrito
            //            
            $this->financeiro_model->update_inscrito($Data);

            //
            //  Salvando as informações do registro de pagamento do Inscrito
            //            
            $this->financeiro_model->update_pagamento($Cielo);

            //
            //  Redirecionamento
            //
            redirect(base_url() . 'eventos/visualizar/' . $Red['id_eventos']);
        }
    }    

    /*
     *  Chama o metodo do model para "exclusão" do registro
     */
    public function excluir_inscrito($id)
    {
        //
        //  Iniciando o processo de exclusão e atualização
        // 
        $this->financeiro_model->excluir_geral($id);
    }

    //
    //  Metodo que exibe a janela com os dados da estudantes
    //
    public function alterar_dependente($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/dependentes'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataBody'] 	=    $this->financeiro_model->get_dependentes_by_id($id);

        $this->usable('eventos/alterar_inscrito');
    }  
    
    //
    //  Metodo que exibe a janela com os dados da estudantes
    //    
    public function salvar_dependente()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_dependente($this->input->post('ID_Dependente'));
        }
        else
        {         
            $Data['id_Dependente']  =   $this->input->post('ID_Dependente');
            $Data['str_Nome']       =   trim(mb_strtoupper($this->input->post('str_Nome')));
            $Data['str_CPF']        =   trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
            
            //
            //  Salvando as informações do Inscrito
            //            
            $this->financeiro_model->update_dependente($Data);

            redirect(base_url() . 'eventos/alterar_inscrito/' . $this->input->post('ID_Inscrito'));
        }
    }

    /*
     *  Chama o metodo do model para "exclusão" do registro
     */
    public function excluir_dependentes($id)
    {
       /*
        *  Iniciando o processo de exclusão e atualização
        */        
        $this->financeiro_model->excluir_dependente($id, $valor);
    }

    /*
     *  Chama o metodo para exibir os dependentes depois da ação de exclusão
     */
    public function exibir_dependentes($id)
    {
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $DataBody 	      =   $this->financeiro_model->get_inscrito($id);
        $DataDependente   =   $this->financeiro_model->get_dependentes($id);
       
        //
        //  Iniciando o processo de atualização do pagamento
        //
        $valor['valor']  =  ($DataBody->valor - $DataBody->valor_evento);      
        
        $this->financeiro_model->atualizar_pagamento($id, $valor);

        $_Table = '';

        if($DataDependente == 0)
        {
            echo '<h3><strong>Não possui dependentes</strong></h3>';
        }
        else
        {
            $Action = '';
            $Action = ($DataBody->payment_status <> 2)? '<th width="60">Ação</th>' : '';
            
            $_Table .= '<table border="0" class="opa">
                        <tr>
                            '.$Action.'
                            <th width="420">Nome</th>
                            <th width="120">CPF</th>
                        </tr> ';
            
            foreach($DataDependente as $sDependente):

                $_Table .= '<tr>';

                if($DataBody->payment_status <> 2)
                {
                    $_Table .= '<td style="padding-top: 9px; padding-left: 10px">
                    
                                    <a id="ExcluirDependente" href="javascript:Delete('.$sDependente->id_Dependente.')" title="Excluir"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> 
                    
                                </td>';
                }

                $_Table .= '<td style="padding-botton: 5px">'.$sDependente->str_Nome.'</td>
                            <td style="padding-botton: 5px">'.formatCPF_CNPJ($sDependente->str_CPF).'</td>
                        </tr>';   

            endforeach;

            echo $_Table . '</table>';
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
    
    //
    // Metodo que exibe uma mensagem de sucesso ao fim do cadastro do participante
    //    
    public function sucesso($id_evento, $id_participante)
    {
        $this->data['AddCss']            =    load_css(array('BreadCrumb', 'form/style'));
        $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
        
        $this->data['AddBody']           =    $this->eventos_model->get_dados_evento($id_evento);
        $this->data['AddUser']           =    $this->eventos_model->get_dados_inscrito($id_participante);

        $this->usable_form('pagamentos/sucesso_participante');   
    }

    //
    // Metodo que retorna os dados para a tabela de status
    //
    public function status_money($Inscrito)
    {
        $this->load->library('email');

        //
        // Busca os dados  no banco de dados
        //
        $Data = $this->financeiro_model->get_cartao_by_id($Inscrito);
        
        if($Data->payment_status == 0)
        {
            header("HTTP/1.0 404 Not Found");
        }

        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $array_Evento  =  $this->financeiro_model->get_id_dados_general($Data->order_number);

        //
        // Busco os dados para ir para o e-mail
        //        
        $Card  =  $this->financeiro_model->get_dados_card($Data->order_number);
        
        //
        // Variáveis de suporte
        //
        $evento    =  $array_Evento->titulo;
        $alerta    =  $array_Evento->alerta;
        $presenca  =  $array_Evento->presenca;
        $status    =  '';
        $mensagem  =  '';

        //
        // Pega o status da compra
        //
        $status  =  paymentStatus($Data->payment_status, 'Status');

        //
        // Tipo de pagamento
        //
        $Legenda = '';

        if($Data->payment_method_type == 1)
        {
            $Legenda = 'CARTÃO DE CRÉDITO';
        }

        if($Data->payment_method_type == 4)
        {
            $Legenda = 'CARTÃO DE DÉBITO';   
        }

        if($Data->payment_method_type == 3)
        {
            $Legenda = 'DINHEIRO';    
        }        

        $config['protocol']         =   'smtp';
        $config['smtp_host']        =   'ssl://smtp.gmail.com';
        $config['smtp_port']        =   465;
        $config['smtp_user']        =   'pagamentosboladeneve@gmail.com';
        $config['smtp_pass']        =   'pag432@1';
        $config['validate']         =   TRUE;
        $config['newline']          =   "\r\n";
        $config['charset']          =   'utf-8';
        $config['mailtype']         =   'html';				

        $this->email->initialize($config);

        $mail_conteudo   = 	  '<style type="text/css">
                                    .lin:link {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:active {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:visited {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:hover {text-decoration: underline; font-weight: bold; color: #000000; font-size: 11px}
                               </style>';		

        $mail_conteudo   .=   '<table width="100%" border="0" cellpadding="10" cellspacing="10" style="font-family: Arial, Verdana, Tahoma, sans-serif, serif; font-size:15px;background-color: #fff; color:#000;">
                               <tr><td style="padding-left: 3px"><img src="'.base_url().'assets/images/wmb_logo.jpg" width="100" /><h2>EVENTOS DO BOLA DE NEVE</h2></td></tr>
                               <tr><td style="padding-left: 3px"><h3>Comprovante de Pagamento</h3></td></tr>
                               <tr><td style="padding-left: 1px">';

        $mail_conteudo   .=   '<p>Ol&aacute; <strong>'.$Card->customer_name.'</strong>, voc&ecirc; acabou de efetuar a inscrição para o evento <strong>'.$evento.'</strong>.</p>'.$mensagem.'<p>O status atual do seu pedido é: <strong>'.$status.'</strong></p><br />';
                            
        if($alerta != NULL)
        {
            $mail_conteudo   .=    '<p><strong>Informações importantes:</strong></p>
                                    <p>Indispensável a apresentação de documento com foto e número do CPF do titular da inscrição para realização de check-in e retirada da Pulseira.<br /></p>

                                    <p>Se você optou por Transporte, atenção o ônibus sairá da Rua Clélia 1517, no dia 15/12/2018 às 04h30.</p>
                                    <p>Retornará para o mesmo endereço ao término do evento.</p>
                                    <p>O embarque somente será possível com a apresentação de documento com foto.</p><br />

                                    <p><strong>Programação:</strong></p>

                                    <p>10/12 - 20hs - Rua Clélia, 1517</p>
                                    <p>11/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>12/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>13/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>14/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>15/12 - 09hs/15hs/19hs – Estancia Arvore da Vida – Sumaré - SP</p>
                                    <p>16/12 - 10hs/16hs/20hs - Rua Clélia 1517</p><br />'; 
        }
                            
        $mail_conteudo   .=    '<p><strong>Segue abaixo os seus dados:</strong></p>
                                <p>Numero do Pedido:&nbsp;'.$Card->order_number.'</p>
                                <p>Nome Completo:&nbsp;'.$Card->customer_name.'</p>
                                <p>CPF:&nbsp;'.formatCPF_CNPJ($Card->customer_identity).'</p>
                                <p>E-mail:&nbsp;'.$Card->customer_email.'</p>
                                <p>Tipo de Pagamento:&nbsp;'.$Legenda.'</p>';                                

        if($Card->payment_method_type <> 3)
        {
            $mask = ($Card->payment_maskedcreditcard == '')? 'Aprovado' : $Card->payment_maskedcreditcard;
            
            $mail_conteudo   .=    '<p>Status:&nbsp;'.$mask.'</p>';                                
        }

        if($array_Evento->tipo_evento == 1)
        {
            $mail_conteudo   .=   '<p>Valor Total:&nbsp;R$&nbsp;'.num_to_user($Card->valor).'</p>';
        }
        
        $mail_conteudo   .=   '<p>Data da compra:&nbsp;'.date("d/m/Y - H:i:s").'</p>';

        //
        // Mensagem do COVID 19
        //
        $mail_conteudo   .=   '<p><strong>Atesto para os devidos fins que não apresento qualquer sintoma de tosse persistente, falta de ar, desconforto respiratório e gripe/resfriado, dores pelo corpo ou qualquer outro desconforto. Da mesma forma, declaro que não sou pertencente a nenhum grupo de risco, tais como, pessoa acima de 60 (sessenta) anos, hipertenso, diabético, gestante e imunodeprimido.</strong></p>';

        //
        // Emissão do QR Code do Comprador
        //        
        if($Card->payment_status == 2 && $presenca == 1)
        {
            //
            // Codigo do Evento
            //
            $Codigo = $Card->customer_identity . '&' . $array_Evento->id_Evento . '&' . $Card->payment_status;

            $this->QrCode($Codigo);

            $mail_conteudo   .=   '<b>QR Code para validar sua presença!</b>';
            $mail_conteudo   .=   '<br /><br /><img src="https://boladeneve.com/eventos/assets/qrcodes/'.$Codigo.'.png" width="200">';
        }

        //
        // Verifica se existe ou não acompanhantes
        //
        $acompanhantes  =  $this->financeiro_model->get_acompanhantes($Card->order_number);
        
        if($acompanhantes > 0)
        {
            $mail_conteudo   .=   '<p>&nbsp;</p><h3 style="font-size:16px"><strong>Segue abaixo os dados dos acompanhantes:</strong></h3><br />';

            foreach($acompanhantes as $sAcompanhantes):
                
                $mail_conteudo   .=   '<p><strong>Nome do Acompanhante:</strong>&nbsp;'.$sAcompanhantes->str_Nome.'</p>';
                
                if($sAcompanhantes->str_CPF != '99999999999')
                {
                    $mail_conteudo   .=   '<p><strong>CPF:</strong>&nbsp;'.formatCPF_CNPJ($sAcompanhantes->str_CPF).'</p>';
                    
                    //
                    // Emissão do QR Code
                    //        
                    if($Card->payment_status == 2)
                    {
                        //
                        // Codigo do Evento
                        //
                        $Codigo = $sAcompanhantes->str_CPF . '&' . $array_Evento->id_Evento . '&' . $Card->payment_status;

                        $this->QrCode($Codigo);

                        $mail_conteudo   .=   '<b>QR Code para validar sua presença!</b>';
                        $mail_conteudo   .=   '<br /><br /><img src="https://boladeneve.com/eventos/assets/qrcodes/'.$Codigo.'.png" width="200">';
                    }                    
                }

            endforeach;
        }
        
        //
        // Footer do e-mail
        //
        $mail_conteudo   .=   '<br /><br /><b>E-mail enviado em ' . date("d/m/Y - H:i:s") . '.</b>';
        $mail_conteudo   .=   '</td></tr></table>';

        $this->email->from('<pagamentos@boladeneve.com>', 'Bola de Neve Eventos');
        $this->email->to($Card->customer_email);

        $this->email->subject('Inscrição para o Bola de Neve Eventos - ' . $status);
        $this->email->message($mail_conteudo);

        $rc = $this->email->send();       
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
    // Metodo que atualiza os valores dos ingressos
    //    
    public function calcular_cadeiras()
    {
        $Valor_Base   =  $this->input->get('valor');
        
        $Valor_Total = $Valor_Base;	

        echo $_return = '<font style="font-size:20px"><strong>R$ '.number_format($Valor_Total, 2, ',', '.').'</strong></font>
                         <input id="ValorTotal" name="ValorTotal" type="hidden" value="'.$Valor_Total.'" /><br />Valor total a ser pago.';
    }     
    
    /*
     *  Metodo que mostra cadeiras de acordo com a escolha
     */    
    public function show_chairs()
    {
        $body = '';

        $Valor = 0;
        
        if(empty($this->session->userdata('cart')))
        {
            echo '<table cellpadding="0" cellspacing="0">
                  <tr>
                       <td style="padding-left:15px"><font style="font-size:14px"><strong>Não existe nenhuma cadeira incluída.</strong></font></td>
                  </tr>
                  </table>';
                
            echo '<input id="valor" name="valor" type="hidden" value="0"><br />';
        }
        else
        {
            $body .= '<table class="table table-striped">
            <thead>
            <tr>
                <th width="100" height="25">Ação</th>    
                <th width="100" height="25">Setor</th>
                <th width="100" height="25">Cadeira</th>
                <th width="100" height="25">Valor</th>
            </tr>
            </thead>
            <tbody>';

            foreach ($this->session->userdata('cart') as $ino => $sAddGrid):

                $Excluir  =  '<a href="javascript:Excluir('.$ino.')" title="Excluir"><img src="'.base_url().'assets/images/ico-lixeira.gif" width="17" height="17"></a>';

                $body .= '<tr>
                                <td>'.$Excluir.'</td>    
                                <td>'.$sAddGrid->str_setor.'</td>
                                <td>'.$sAddGrid->str_cadeira.'</td>        
                                <td>R$ '.num_to_user($sAddGrid->valor_cadeira).'</td>    
                            </tr>';

                $Valor += $sAddGrid->valor_cadeira;   

            endforeach;            

            echo $body .= '</tbody></table>';

            echo '<input id="valor" name="valor" type="hidden" value='.$Valor.'><br />';
        }

        echo form_error('valor', '<span class="field-validation-valid validation" data-valmsg-for="valor" data-valmsg-replace="true">', '</span>');
    } 

    /*
     *  Metodo que adiciona cadeiras de acordo com a escolha
     */      
    public function add_chairs($Id_Chair)
    {
        $body = '';
        
        //
        // Busca no banco as cadeiras pelo ID
        //
        $AddGrid  =  $this->eventos_model->listar_chair_by_id($Id_Chair);        
        
        //
        // Verifica se já existe a session
        //
        if(empty(array_filter($this->session->userdata('cart'))))
        {
            $this->session->set_userdata('cart', array());
        }

        if(!in_array($AddGrid, $this->session->userdata('cart'))) 
        {
            //
            // Inclui um item no carrinho
            //
            $old_que_ans_session =  $this->session->userdata('cart');
            
            array_push($old_que_ans_session, $AddGrid);
            
            $this->session->set_userdata('cart', $old_que_ans_session);            
        }
    } 
    
    /*
     *  Lista as familias de acordo com a estudantes logada
     */	
    public function excluir_chair($id)
    {
        //
        // Remove the item from the cart
        //
        $cart = $this->session->userdata('cart');
        
        unset($cart[$id]);

        $this->session->set_userdata('cart', $cart);
    } 
    
    /*
     *  Metodo que verifica se CPF é duplicado e permite nova tentativa de preenchimento
     */
    public function duplicidade_retype()
    { 
        $Data['ID']     =   $this->input->get('evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '',$this->input->get('cpf')));
        
        if($this->eventos_model->payment_finder_teatro($Data) == false)
        { 
            echo 'false'; 
        } 
        else
        { 
            //
            // Limpar o carrinho de compras do usuário que selecionou e não utilizou
            //
            $this->eventos_model->limpa_cart($Data);

            echo 'true';        
        } 
    }
    
    /*
     *  Metodo que adiciona participantes de acordo com o proposto no admin
     */       
    public function enviar_participante_teatro()
    {
        $CPF  =  trim($this->input->post('str_CPF'));
        
        /*
         *  Tabela de Inscritos
         */
        $Data['id_Evento'] 		       =    $this->input->post('id_eventos');
        $Data['str_CPF']               =    trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
        $Data['id_Inscrito']           =    $this->eventos_model->check_return_id($Data);
        $Data['str_Nome']        	   =    trim(mb_strtoupper($this->input->post('str_Nome')));

        if($Data['id_Inscrito'] == 0)
        {
            $Data['id_Inscrito']       =    $this->eventos_model->get_id_inscricao();
        }

        $Data['str_Email']    	       =    trim(mb_strtolower($this->input->post('str_Email')));
        $Data['str_Telefone']    	   =    trim(mb_strtolower($this->input->post('str_Telefone')));
        $Data['str_Celular']    	   =    trim(mb_strtolower($this->input->post('str_Celular')));
        $Data['str_Cidade']    	       =    trim(mb_strtoupper($this->input->post('str_Cidade')));
        $Data['str_UF']    	   	       =    $this->input->post('id_UF');
        $Data['dec_Valor']             =    $this->input->post('ValorTotal');
        $Data['str_Usuario']    	   =    $this->session->userdata('strLogin');
        $Data['dt_Cadastro'] 	       =    date('Y-m-d');	
        
        /*
         *  Bota para dentro o Inscrito
         */
        $this->eventos_model->insert_inscrito($Data);

        /*
         *  Tabela de Cartões
         */            
        $Cartao['id_inscrito']           =    $Data['id_Inscrito'];
        $Cartao['id_evento'] 		     =    $this->input->post('id_eventos');
        $Cartao['event_type']            =    2;
        $Cartao['valor']                 =    $this->input->post('ValorTotal');
        $Cartao['order_number']          =    $this->eventos_model->create_order_number();
        $Cartao['customer_name']         =    $Data['str_Nome'];
        $Cartao['customer_identity']     =    $Data['str_CPF'];
        $Cartao['customer_email']        =    $Data['str_Email'];
        $Cartao['shipping_type']         =    5;
        $Cartao['payment_method_type']   =    $this->input->post('sel_Pagamento');
        $Cartao['payment_status']        =    2;
        $Cartao['payment_date']          =    date('Y-m-d H:i:s');        

        /*
         *  Bota para dentro o Pagamento em Cartão
         */
        $this->eventos_model->insert_cartao($Cartao);            

        /*
         *  Inclui no banco a intensão de compra do usuário
         *  Isso não garante a propriedade da cadeira
         */ 
        foreach($this->session->userdata('cart') as $ino => $sAddGrid):

            $Cart['id_evento']       =     $Data['id_Evento'];
            $Cart['id_inscrito']     =     $Data['id_Inscrito'];
            $Cart['id_cadeira']      =     $sAddGrid->id_cadeira;
            $Cart['str_setor']       =     $sAddGrid->str_setor;
            $Cart['str_cadeira']     =     $sAddGrid->str_cadeira;
            $Cart['valor_cadeira']   =     $sAddGrid->valor_cadeira;
            $Cart['pago']            =     1;

            $this->eventos_model->insert_cadeira($Cart);

        endforeach;

        //
        // Destroi a sessão pois não mais ela interessa
        //
        $this->session->unset_userdata('cart');
            
        //
        // Metodo que dispara o e-mail de confirmação
        //                      
        $this->status_money($Data['id_Inscrito']);

        //
        // Redirect para dizer que deu tudo certo
        //                      
        redirect(base_url() . 'eventos/sucesso/' . $this->input->post('id_eventos') . '/' . $Data['id_Inscrito']);
    }         
}