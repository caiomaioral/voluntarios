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
	// Metodo que chama o post
	//
	public function processar()
	{
        //
        // Tabela de Voluntarios
        //
        $Data['Nome'] 	        =    mb_strtoupper(trim($this->input->post('Nome')));
        $Data['CPF'] 		    =    str_replace('-', '', str_replace('.', '', $this->input->post('CPF')));
        $Data['Email'] 	        =    mb_strtolower(trim($this->input->post('Email')));
        $Data['Telefone']       =    trim($this->input->post('Telefone'));
        $Data['Termos']         =    $this->input->post('Termos');
        
        //
        // Cria uma variavel Body para mostrar os dados
        //
        $this->session->set_flashdata('Body', $Data);
        
        //
        // Chama a confirmação
        //			
        redirect(base_url() . 'cadastro/confirmacao');
	}
	
	//
	// Metodo que chama view index
	//
	public function confirmacao()
	{
		if($this->session->flashdata('Body') != '')
		{
            $this->data['AddCss'] 		  	 =   load_css(array('site'));
            $this->data['AddJavascript']  	 =   load_js(array('libraries', 'scripts'));
			$this->data['Body'] 			 =   $this->session->flashdata('Body');
			
			$this->usable('confirmacao');
		}
		else
		{
			redirect(base_url() . 'cadastro');	
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
        
        //
        // Salva os dados no banco de dados
        //
        $this->inscricao_model->insert_cadastro($Data);
        
        //
        // Chama a confirmação
        //			
        redirect(base_url() . 'cadastro/sucesso');
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

    /**
     * Metodo que verifica se CPF é duplicado
     *
     * @param string $CPF
     * @return bool
     */
    public function duplicidade($CPF)
    { 
        $cleanedCPF = str_replace(['-', '.'], '', $CPF);

        if ($this->inscricao_model->Get_Duplicidades(['CPF' => $cleanedCPF])) 
        {
            return false; 
        } 
        else 
        {
            return true;
        } 
    }  
}