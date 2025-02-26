<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('perfil_model');
    }
	 
	//
	// Exibe o formulario de alteração do perfil
	//
    public function index()
    {
		$this->usable('perfil');
    }

	//
	// Metodo que salva a alteração do perfil
	//
	public function salvar()
    {
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$Data['Id'] 	   		=    $this->session->userdata('Id');
			$Data['Senha']      	=    $this->input->post('NewPassword');
			
			if($this->perfil_model->update_perfil($Data))
			{
				$this->usable('perfil/sucesso');
			}
		}
    }

	//
	// Metodo que verifica se as senhas são diferentes
	//
	public function password_check($str)
	{
		if($str == $this->input->post('OldPassword'))
		{
			$this->form_validation->set_message('password_check', 'O campo %s deve ser diferente do campo <strong>SENHA ATUAL</strong>.');
			
			return false;
		}
		else
		{
			return true;
		}
	}

	//
	// Metodo que confirma se a senha digitada confere
	//
	public function confirm_senha($str)
	{
		if($str != $this->perfil_model->get_senha($this->session->userdata('Id')))
		{
			$this->form_validation->set_message('confirm_senha', 'A %s digitada não confere com a atual.');
			return false;
		}
		else
		{
			return true;
		}		
	}
}