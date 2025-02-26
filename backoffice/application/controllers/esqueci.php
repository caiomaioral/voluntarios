<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Esqueci extends MY_Controller {
	 
    public function __construct()
    {
        parent::__construct();

        $this->load->model('autenticacao_model');
    }

    //
    // Metodo que chama view index
    //
    public function index()
    {
        //
        // CSS padrão dessa estrutura
        //
        $this->data['AddCss']   =   load_css(array('logar'));
        $this->data['AddJs']    =   load_js(array('login/login'));        
        
        $this->usable_login('esqueci');
    }

    /*
     *  Metodo que chama o post
     */
    public function enviar()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            return true;
        }
    }

    //
    // Metodo que a autenticação do email
    //
    public function autenticar()
    {
        $Email  =  $this->input->post('email', TRUE);

        $Email  =  preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $Email);
        $Email  =  trim($Email); ## Limpa espaços vazio
        $Email  =  strip_tags($Email); ## Tira tags HTML e PHP
        $Email  =  addslashes($Email); ## Adiciona barras invertidas a uma string

        if($Email != "")
        {	
            if($Data = $this->autenticacao_model->get_email($Email))
            {
                $mail_disparo   =  $Data['Email'];

                // Prepara o conteudo do email
                $mail_conteudo  = 	'<blockquote>';
                $mail_conteudo  .= 	'Caro aluno, segue os dados abaixo.<br /><br />';
                $mail_conteudo  .= 	'E-mail: ' . $Data['Email'] . '<br />';
                $mail_conteudo  .= 	'Senha: ' . $Data['Senha'] . ' <br /><br />';
                $mail_conteudo  .= 	'<strong>Mensagem automatica. Favor não responder esse e-mail.</strong><br />';
                $mail_conteudo  .=  '<br />E-mail enviado em '.date ("d/m/Y - H:i:s").'.';
                $mail_conteudo  .= 	'</blockquote>';

                // Configure API key authorization: api-key
                $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
                // Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
                // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('api-key', 'Bearer');
                // Configure API key authorization: partner-key

                $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);

                $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                    'subject' => 'Instituto Global | Esqueci minha senha',
                    'sender' => ['name' => 'Igreja Bola de Neve', 'email' => 'institutoglobal@boladeneve.com'],
                    'to' => [[ 'email' => $mail_disparo ]],
                    'htmlContent' => $mail_conteudo,
                    'params' => ['bodyMessage' => 'Igreja Bola de Neve']
                ]);

                try {
                    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                } catch (Exception $e) {
                    echo $e->getMessage(),PHP_EOL;
                } 

                $this->form_validation->set_message(__FUNCTION__, '<strong>SENHA</strong> enviada com sucesso por e-mail.');

                return false;
            }
            else
            {
                $this->form_validation->set_message(__FUNCTION__, '<strong>E-MAIL</strong> não consta em nossa base de dados.');

                return false;
            }
        }
    }
}
