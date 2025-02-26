<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
	 
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

        $this->usable_login('login');
    }

    //
    // Metodo que chama o post
    //
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
    // Metodo que a autenticação
    //
    public function autenticar()
    {
        $Data['Login']  =  $this->input->post('login');
        $Data['Senha']  =  $this->input->post('pwd');

        $Login  =  preg_replace("/(select|insert|delete|where|drop table|show tables|\\\\)/", "", $Data['Login']);
        $Login  =  trim($Login); ## Limpa espaços vazio
        $Login  =  strip_tags($Login); ## Tira tags HTML e PHP
        $Login  =  addslashes($Login); ## Adiciona barras invertidas a uma string

        $Senha  =  preg_replace("/(select|insert|delete|where|drop table|show tables|\\\\)/", "", $Data['Senha']);
        $Senha  =  trim($Senha); ## Limpa espaços vazio
        $Senha  =  strip_tags($Senha); ## Tira tags HTML e PHP
        $Senha  =  addslashes($Senha); ## Adiciona barras invertidas a uma string

        $Data['Login']  =  $Login;
        $Data['Senha']  =  $Senha;		
		
        if($Senha != "")
        {	
            if($Data = $this->autenticacao_model->get_login($Data))
            {
                $this->load->library('session');

                $this->session->set_userdata($Data);
                $this->session->set_userdata(array('logado' => TRUE));

                redirect(base_url() . 'home');                    
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
}
