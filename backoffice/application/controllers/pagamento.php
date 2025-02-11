<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagamento extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('financeiro_model');
    }
	 
    /*
     *  Exibe o formulario de alteração do perfil
     */
    public function index()
    {
        debug($acompanhantes  =  $this->financeiro_model->get_acompanhantes('00000008'));
    }

    /*
     *  Metodo que retorna os dados para a tabela de status
     */
    public function status()
    {
        $this->load->library('email');

        $Data['order_number'] 				    = 	$this->input->post('order_number');
        $Data['checkout_cielo_order_number']    = 	$this->input->post('checkout_cielo_order_number');
        $Data['customer_name']				    = 	$this->input->post('customer_name');
        $Data['customer_identity']			    = 	$this->input->post('customer_identity');
        $Data['customer_email']				    = 	$this->input->post('customer_email');
        $Data['shipping_type']				    = 	$this->input->post('shipping_type');	
        $Data['payment_method_type']			= 	$this->input->post('payment_method_type');
        $Data['payment_method_brand']			= 	$this->input->post('payment_method_brand');
        $Data['payment_maskedcreditcard']		= 	$this->input->post('payment_maskedcreditcard');
        $Data['payment_installments']			= 	$this->input->post('payment_installments');
        $Data['payment_status']				    = 	$this->input->post('payment_status');
        $Data['payment_method_bank']			= 	$this->input->post('payment_method_bank');
        $Data['valor']                          =   num_cielo_to_db($this->input->post('amount'));
        $Data['tid']					        = 	$this->input->post('tid');
        $Data['created_date'] 				    = 	$this->input->post('created_date');
        $Value['valor']                         = 	num_to_user($Data['valor']);

        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $array_Evento  =  $this->financeiro_model->get_id_dados_general($Data['order_number']);     
        
        //
        //  Atualiza a tabela de cartão
        //            
        $this->financeiro_model->update_payment($Data);

        //
        // Variáveis de suporte
        //
        $evento    =  $array_Evento->titulo;
        $alerta    =  $array_Evento->alerta;
        $status    =  '';
        $mensagem  =  '';

        /*
        *  Validação
        */
        if($Data['payment_status'] == 1)
        {
            $status    =  'Pendente';
            $mensagem  =  '<p>Seu pedido foi realizado com sucesso. Iremos analisar os dados do titular do cartão e poderemos, em até 1 dia útil, entrar em contato via e-mail para confirmar a compra. Por favor, aguarde novo contato via e-mail.</p>';		
        }
        if($Data['payment_status'] == 2)
        {
            $status    =  'Aprovado';
            $mensagem  =  '';			
        }
        if($Data['payment_status'] == 3)
        {
            $status    =  'Negado';
            $mensagem  =  '';			
        }
        if($Data['payment_status'] == 4)
        {
            $status    =  'Expirado';
            $mensagem  =  '';			
        }            
        if($Data['payment_status'] == 5)
        {
            $status    =  'Cancelado';
            $mensagem  =  '';			
        }															
        if($Data['payment_status'] == 6)
        {
            $status    =  'Não finalizada';
            $mensagem  =  '';			
        }
        if($Data['payment_status'] == 7)
        {
            $status    =  'Autorizada';
            $mensagem  =  '';			
        }

        //
        // Tipo de pagamento
        //
        $Legenda = '';

        if($Data['payment_method_type'] == 1)
        {
            $Legenda = utf8_decode('CARTÃO DE CRÉDITO');
        }

        if($Data['payment_method_type'] == 4)
        {
            $Legenda = utf8_decode('CARTÃO DE DÉBITO');   
        }

        if($Data['payment_method_type'] == 3)
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

        $mail_conteudo   .=   '<p>Ol&aacute; <strong>'.$Data['customer_name'].'</strong>, voc&ecirc; acabou de efetuar a inscrição para o evento <strong>'.$evento.'</strong>.</p>'.$mensagem.'<p>O status atual do seu pedido é: <strong>'.$status.'</strong></p><br />';
                            
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
                                <p>Numero do Pedido:&nbsp;'.$Data['order_number'].'</p>
                                <p>Nome Completo:&nbsp;'.$Data['customer_name'].'</p>
                                <p>CPF:&nbsp;'.formatCPF_CNPJ($Data['customer_identity']).'</p>
                                <p>E-mail:&nbsp;'.$Data['customer_email'].'</p>
                                <p>Tipo de Pagamento:&nbsp;'.$Legenda.'</p>';                                

        if($Data['payment_method_type'] <> 3)
        {
            $mask = ($Data['payment_maskedcreditcard'] == '')? '-' : $Data['payment_maskedcreditcard'];
            
            $mail_conteudo   .=    '<p>Cart&atilde;o:&nbsp;'.$Data['payment_maskedcreditcard'].'</p>
                                    <p>Parcelado em:&nbsp;'.$Data['payment_installments'].'x</p>';                                
        }

        if($array_Evento->tipo_evento == 1)
        {
            $mail_conteudo   .=   '<p>Valor Total:&nbsp;R$&nbsp;'.$Value['valor'].'</p>';
        }
            
        $mail_conteudo   .=   '<p>Data da compra:&nbsp;'.date("d/m/Y - H:i:s").'</p><p>&nbsp;</p>';

        //
        // Verifica se é EVENTOS ou TEATRO
        //
        if($array_Evento->tipo_evento == 1)
        {
            $acompanhantes  =  $this->financeiro_model->get_acompanhantes($Data['order_number']);

            if($acompanhantes > 0)
            {
                $mail_conteudo   .=   '<p>Segue abaixo os dados dos acompanhantes:</p>';
    
                foreach($acompanhantes as $sAcompanhantes):
                    
                    $mail_conteudo   .=   '<p><strong>Nome do Acompanhante:</strong>&nbsp;'.$sAcompanhantes->str_Nome.'</p>';
                    
                    if($sAcompanhantes->str_CPF != '99999999999')
                    {
                        $mail_conteudo   .=   '<p><strong>CPF:</strong>&nbsp;'.formatCPF_CNPJ($sAcompanhantes->str_CPF).'</p>';   
                    }

                endforeach;
            }
        }
        else
        {
            //
            //  Atualiza a tabela liberando ou não as cadeiras
            //            
            $Cart['id_evento']     =   $array_Evento->id_Evento;
            $Cart['id_inscrito']   =   $array_Evento->id_inscrito;
            
            if($Data['payment_status'] == 1)
            {
                $Cart['pago'] = 1;
            }
            elseif($Data['payment_status'] == 2)
            {
                $Cart['pago'] = 1;	
            }
            elseif($Data['payment_status'] == 7)
            {
                $Cart['pago'] = 1;			
            }   
            else
            {
                $Cart['pago'] = 0;
            }         
            
            //
            //  Persistência no banco
            //             
            $this->financeiro_model->update_cart($Cart);            
            
            //
            //  Pega dados para ir para o e-mail
            //            
            $cadeiras  =  $this->financeiro_model->get_cadeiras($Data['order_number']);

            if($cadeiras == 0)
            {
                $mail_conteudo   .=   '<h3 style="color: red"><strong>Houve um problema com a confirmação das cadeiras entre em contato com eventos.online@boladeneve.com o mais rápido possível!</strong></h3>';    
            }
            else
            {
                $mail_conteudo   .=   '<p><strong>Segue abaixo as cadeiras / setores adquiridos:</strong></p>';
                $mail_conteudo   .=   '<p><strong>Setor / Cadeira / Valor:</strong></p>';
    
                $Value['valor'] = 0;
    
                foreach($cadeiras as $sCadeiras):
                    
                    $mail_conteudo   .=   '<p>'.$sCadeiras->str_setor.' / '.$sCadeiras->str_cadeira.' / R$ '.num_to_user($sCadeiras->valor_cadeira).'</p>';
                    $Value['valor'] += $sCadeiras->valor_cadeira;
    
                endforeach;
    
                $mail_conteudo   .=   '<p><strong>Valor Total:&nbsp;R$&nbsp;'.num_to_user($Value['valor']).'</strong></p>';
            }
        }

        $mail_conteudo   .=   '<br /><br /><b>E-mail enviado em ' . date("d/m/Y - H:i:s") . '.</b>';
        $mail_conteudo   .=   '</td></tr></table>';

        $this->email->from('<pagamentos@boladeneve.com>', 'Bola de Neve Eventos');
        $this->email->to($Data['customer_email']);

        $this->email->subject('Inscrição para o Bola de Neve Eventos - ' . $status);
        $this->email->message($mail_conteudo);

        $rc = $this->email->send();

        echo '<status>OK</status>';
    }		

    /*
     *  Metodo que retorna os dados para a tabela de status
     */
    public function update_status()
    {			
        //
        // Campos que servirão para a atualização do Status
        //
        $Check['checkout_cielo_order_number'] 	  = 	 $this->input->post('checkout_cielo_order_number');
        $Data['payment_status']			          = 	 $this->input->post('payment_status');

        //
        // Persistência no banco de dados
        //
        $this->financeiro_model->update_status($Data, $Check);

        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $array_Evento  =  $this->financeiro_model->get_id_dados_general_checkout($Check['checkout_cielo_order_number']);   

        //
        // Verifica se é EVENTOS ou TEATRO
        //
        if($array_Evento->tipo_evento == 2)
        {
            //
            //  Atualiza a tabela liberando ou não as cadeiras
            //            
            $Cart['id_evento']     =   $array_Evento->id_Evento;
            $Cart['id_inscrito']   =   $array_Evento->id_inscrito;
            
            if($Data['payment_status'] == 1)
            {
                $Cart['pago'] = 1;
            }
            elseif($Data['payment_status'] == 2)
            {
                $Cart['pago'] = 1;	
            }
            elseif($Data['payment_status'] == 7)
            {
                $Cart['pago'] = 1;			
            }   
            else
            {
                $Cart['pago'] = 0;
            }     
            
            $this->financeiro_model->update_cart($Cart);
        }        

        echo '<status>OK</status>';
    }	

    /*
     *  Metodo que verifica se CPF é valido
     */
    public function validarCPF($CPF)
    { 
        if(!validaCPF($CPF))
        { 
            $this->form_validation->set_message(__FUNCTION__, 'O campo <strong>CPF</strong> esta inválido, favor digitar novamente.');

            return false; 
        } 
        else
        { 
            return true;
        } 
    }
}