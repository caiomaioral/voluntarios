<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	 
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }

    //
    // Carrega tela de login
    //
    public function index()
    {
        $this->load->view('login');     
    }

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

    public function autenticar()
    {
        $Data['Login']       =    $this->input->post('login');
        $Data['Senha']       =    $this->input->post('pwd');
        $recaptchaResponse   =    $this->input->post('g-recaptcha-response');

        $Login  =  preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $Data['Login']);
        $Login  =  trim($Login); ## Limpa espaços vazio
        $Login  =  strip_tags($Login); ## Tira tags HTML e PHP
        $Login  =  addslashes($Login); ## Adiciona barras invertidas a uma string

        $Senha  =  preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $Data['Senha']);
        $Senha  =  trim($Senha); ## Limpa espaços vazio
        $Senha  =  strip_tags($Senha); ## Tira tags HTML e PHP
        $Senha  =  addslashes($Senha); ## Adiciona barras invertidas a uma string

        if(reCAPTCHA($recaptchaResponse))
        {
            if($Senha != "")
            {	
                if($Data = $this->user_model->Get_Login($Data))
                {
                    $this->load->library('session');
                    $this->session->set_userdata($Data);
                    $this->session->set_userdata(array('Logado' => true));
    
                    //
                    // Redirect para a pagina principal
                    //
                    redirect(base_url() . 'links');
                }
                else
                {
                    $this->form_validation->set_message('autenticar', '%s ou <strong>SENHA</strong> inválidos, tente novamente.');
                    return false;
                }
            }
            else
            {
                $this->form_validation->set_message('autenticar');
                return false;			
            }            
        }
        else
        {
            $this->form_validation->set_message('autenticar', 'Você não é <strong>HUMANO</strong>, tente novamente.');
            return false;
        }
    }
}
