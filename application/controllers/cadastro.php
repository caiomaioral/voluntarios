<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

//
// include manually module library - SendInBlue API
//
require_once(FCPATH . 'vendor/autoload.php'); 

class Cadastro extends MY_Controller {
	 
    public function __construct()
	{
        parent::__construct();
		
        $this->load->model('inscricao_model');
        $this->load->model('ofertas_model');
	}

	//
	// Metodo que chama view index
    //
	public function index()
	{
        $this->data['AddCss'] 		  	 =   load_css(array('site'));
		$this->data['AddJavascript']  	 =   load_js(array('libraries', 'scripts'));

		$this->usable('cadastro');
	}

    //
	// Metodo que chama view index
	//
	public function example()
	{
        $this->data['AddCss'] 		  	 =   load_css(array('example'));
		$this->data['AddJavascript']  	 =   load_js(array(''));
		
		$this->usable('example');
	}    

	//
	// Metodo que verifica se o cara já esta cadastrado
	//
    function verificar_doador($CPF)
    {
		$Data['CPF']  =  str_replace('-', '', str_replace('.', '', $CPF));
        
		$Result = $this->inscricao_model->check_donation($Data['CPF']);

		header("Content-Type: application/json", true);

		if($Result == false)
		{
			echo json_encode(array('Status' => 0));
		}
		else
		{
			echo json_encode(array('Id' => $Result->Id, 'Nome' => $Result->Nome, 'Email' => $Result->Email, 'Telefone' => $Result->Telefone)); 
		}
    }

	//
	// Metodo que chama o post
	//
	public function salvar()
	{
        //
        // Tabela de Voluntarios
        //
        $Data['Nome'] 	        =    mb_strtoupper(trim($this->input->post('Nome')));
        $Data['CPF'] 		    =    str_replace('-', '', str_replace('.', '', $this->input->post('CPF')));
        $Data['Email'] 	        =    mb_strtolower(trim($this->input->post('Email')));
        $Data['Telefone']       =    trim($this->input->post('Telefone'));
        $Data['Termos']         =    $this->input->post('Termos');
        $Data['Data']           =    date('Y-m-d H:i:s');
        
        debug($Data);
        
        //
        // Cria uma variavel Body para mostrar os dados
        //
        $this->session->set_flashdata('Body', $Data);
        
        //
        // Chama a confirmação
        //			
        //redirect(base_url() . 'ofertas/confirmacao');
	}
	
	//
	// Metodo que chama view index
	//
	public function confirmacao()
	{
		if($this->session->flashdata('Body') != '')
		{
            $this->data['AddCss'] 		  	 =   load_css(array('site', 'example'));
            $this->data['AddJavascript']  	 =   load_js(array('libraries', 'scripts'));
            $this->data['AddProjeto']        =   $this->ofertas_model->get_projetos_dropdown();
			$this->data['Body'] 			 =   $this->session->flashdata('Body');
			
			$this->usable('confirmacao');
		}
		else
		{
			redirect(base_url() . 'ofertas');	
		}
	}

	//
    // End-Point da Granito
    //
    public function endpoint()
    {
        // Elo Credito -  6505210000000002 - 12/49 111
        // Hipercard credito -  6062820000000003 - 12/49 111
        // Mastercard credito -  5204970000005250 -  12/49 111

        //
        // Dados do Formulário
        //        
        $nonce              =   $this->input->post('nonce');
        $order              =   $this->input->post('order');
        $amount             =   str_replace('.', '', str_replace(',', '', $this->input->post('amount')));
        $customer_name      =   $this->input->post('customer_name');
		$customer_identity  =   $this->input->post('customer_identity');
		$customer_email  	=   $this->input->post('customer_email');
		$church_identity  	=   $this->input->post('church_identity');

        $data_string = 
        '{
            "payment": {
              "termUrl": "https://boladeneve.com/dizimos/ofertas/sucesso",
              "paymentMethod": {
                "paymentMethodNonce": "'.$nonce.'"
              },
              "amount": '.$amount.',
              "installments": 1,
              "settle": true,
              "authenticate": false
            },
            "merchantOrderId": "DIZ|'.$order.'|'.$church_identity.'"
        }';

        $url = 'https://gateway.granitopagamentos.com.br/Payments/Authorize/Payment/Process';                                                             
        
        //
        //  Initiate curl
        //
        $ch = curl_init();
        
        //
        // Set the timeout
        //
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        //
        // Disable SSL verification
        //
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //
        // Gera o token para o Bearer
        //
        $token  =  jwt_generator();        
        
        //
        // Set the header
        //
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                                       'Content-Type: application/json',                                                                                
                                                       'Cache-Control: no-cache',
                                                       'Authorization: Bearer ' . $token
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
        // Retorno final da venda
        //   
		$GranitoReturn = json_decode($result);
        
        //
        // Dados do retorno para jogar nas varíaveis
        //
        $Data['pedido']    	   	               =    floatval($order);
        $Data['order_number']    	   	       =    inclui_zero_esq($order);
        $Data['valor']    	                   =    num_cielo_to_db($amount);
        $Data['customer_name']                 =    $customer_name;
		$Data['customer_identity']    	       =    $customer_identity; 
		$Data['customer_email']    	       	   =    $customer_email; 

        //
        // Armazenamento do resultado do serviço da Granito
        //        
        $Data['payment_method_type']           =    1;
        $Data['payment_maskedcreditcard']      =    $GranitoReturn->payment->paymentMethod->card->number;
        $Data['payment_status']    	           =    paymentStatus($GranitoReturn->payment->status, 'Codigo');
        $Data['tid']                           =    $GranitoReturn->payment->paymentId;
        $Data['status_date'] 	               =    date('Y-m-d H:i:s');
		
        //
        // Persistência no banco de dados
        //
        $this->inscricao_model->update_payment($Data);

        //
        // Envia uma notificação de e-mail
        //        
        $this->notificacao($Data);
    }

    //
    // Metodo que retorna os dados para a tabela de status
    //
    public function notificacao($Data)
    {
        //
        // Tipo de pagamento
        //
        $Legenda = '';

        if($Data['payment_status'] == 1)
        {
            $status    =  'Pendente';
            $mensagem  =  '<p>Sua oferta foi realizado com sucesso. Iremos analisar os dados do titular do cartão e poderemos, em até 1 dia útil, entrar em contato via e-mail para confirmar a compra. Por favor, aguarde novo contato via e-mail.</p>';		
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
            $status    =  'Rejeitada';
            $mensagem  =  '';			
        }        
        if($Data['payment_status'] == 5)
        {
            $status    =  'Cancelado';
            $mensagem  =  '';			
        }															
        if($Data['payment_status'] == 6 || $Data['payment_status'] == 0 || $Data['payment_status'] == 99)
        {
            $status    =  'Não finalizado';
            $mensagem  =  '';			
        }
        if($Data['payment_status'] == 7)
        {
            $status    =  'Autorizada';
            $mensagem  =  '';			
        }

        $mail_conteudo   =   '<style type="text/css">
                                    .lin:link {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:active {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:visited {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:hover {text-decoration: underline; font-weight: bold; color: #000000; font-size: 11px}
                              </style>';		
        
        $mail_conteudo   .=   '<table width="100%" border="0" cellpadding="10" cellspacing="10" style="font-family: Verdana, Tahoma, sans-serif, serif; font-size:13px;background-color: #fff; color:#000;">
							   <tr><td style="padding-left: 3px"><img src="'.base_url().'assets/images/wmb_logo.jpg" width="100" />
                               <tr><td style="padding-left: 30px; padding-bottom: 30px">';
        
        $mail_conteudo   .=   '<p>Ol&aacute; <strong>'.$Data['customer_name'].'</strong>, voc&ecirc; acabou de efetuar uma oferta para a Igreja Bola de Neve.</p>
                                    '.$mensagem.'
                                    <p>O status atual do seu pedido é: <strong>'.$status.'</strong></p>
                                    <p>Segue abaixo os seus dados:</p>
                                    <p><strong>Numero:</strong>&nbsp;'.$Data['order_number'].'</p>
                                    <p><strong>Nome Completo:</strong>&nbsp;'.$Data['customer_name'].'</p>
                                    <p><strong>CPF:</strong> '.formatCPF_CNPJ($Data['customer_identity']).'</p>
                                    <p><strong>Cartão:</strong>&nbsp;'.$Data['payment_maskedcreditcard'].'</p>
                                    <p><strong>Valor:&nbsp;R$&nbsp;'.num_to_user($Data['valor']).'</strong></p>
                                    <p><strong>Data:</strong> '.date('d/m/Y - H:i:s').'</p>';
        $mail_conteudo   .=   '<br /><br /><b>E-mail enviado em ' . date('d/m/Y - H:i:s') . '.</b>';
        $mail_conteudo   .=   '</td></tr></table>';

        //
        // Configure API key authorization: api-key
        //
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '');

        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
            'subject' => 'Oferta para a Igreja Bola de Neve - ' . $status,
            'sender' => ['name' => 'Igreja Bola de Neve', 'email' => 'pagamentos@boladeneve.com'],
            'to' => [[ 'email' => $Data['customer_email']]],
            'htmlContent' => $mail_conteudo,
            'params' => ['bodyMessage' => 'Igreja Bola de Neve']
        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            echo $e->getMessage(),PHP_EOL;
        }         
    }	
	
	//
	// Metodo que chama view index
	//
	public function sucesso()
	{
		$this->data['AddCss']   =   load_css(array('site'));
		
		$this->usable('sucesso');
	}

	//
	// Metodo que valida CPF
	//
	public function validarCPF($CPF)
	{ 
		if(!validaCPF($CPF))
		{ 
			$this->form_validation->set_message('validarCPF', 'Número de <strong>CPF</strong> esta inválido.');
			return false; 
		} 
		else
		{ 
			return true;
		} 
	}

	//
	// Metodo que verifica a doação
	//	
	function maximumCheck($num)
	{
		if(num_to_db($num) < 100)
		{
			$this->form_validation->set_message('maximumCheck', 'O <strong>VALOR</strong> deve ser no valor mínimo de R$ 100,00.');
			
			return false;
		}
		else
		{
			return true;
		}
	}

    //
	// Metodo que valida CPF
	//
	public function validarRecorrenciaDia($CPF)
	{ 
        if($this->inscricao_model->Get_Duplicidades(str_replace('-', '', str_replace('.', '', $CPF))) == 1)
		{ 
			return false; 
		} 
		else
		{ 
			return true;
		} 
	}
    
	//
	// Metodo que verifica a doação
	//	
	function checkCaptcha($Captcha)
	{
        if($Captcha != $this->session->userdata('captcha'))
		{
			$this->form_validation->set_message('checkCaptcha', 'O <strong>CAPTCHA</strong> não corresponde a imagem, digite novamente.');
			
			return false;
		}
		else
		{
			return true;
		}
	}
    
	//
	// Metodo que valida CPF
	//
	public function validarFraudes($CPF)
	{ 
        if($this->inscricao_model->Get_Fraudes(str_replace('-', '', str_replace('.', '', $CPF)), mb_strtolower(trim($this->input->post('Email')))) == 1)
		{ 
			$this->form_validation->set_message('validarFraudes', 'Você já tentou mais de uma vez com um <strong>CARTÃO NEGADO</strong>, por medida de segurança você deve efetuar sua doação por outro meio.');

			return false; 
		} 
		else
		{ 
			return true;
		} 
	}    
}