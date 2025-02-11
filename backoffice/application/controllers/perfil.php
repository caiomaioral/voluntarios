<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('perfil_model');
    }
	 
	/*
	 *  Exibe o formulario de alteração do perfil
	 */
    public function index()
    {
		$this->data['AddCss']   		 =    load_css(array('perfil/perfil'));
		$this->data['AddJavascripts']    =    load_js(array('perfil/perfil', 'password_strength_plugin'));
		$this->data['DataUser'] 	     =    $this->perfil_model->get_usuarios($this->session->userdata('idUsuario'));
		
		$this->usable('perfil');
    }

	/*
	 *  Metodo que salva a alteração do perfil
	 */
	public function salvar()
    {
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$Data['idUsuario'] 	  =    $this->session->userdata('idUsuario');
			$Data['strSenha']     =    $this->input->post('str_Senha_Nova');
			
			if($this->perfil_model->update_perfil($Data))
			{
				$this->session->set_userdata(array('strSenha' => $Data['strSenha']));
				
				$this->usable('perfil/sucesso');
			}
		}
    }

	/*
	 *  Metodo que verifica se as senhas são diferentes
	 */
	public function password_check($str)
	{
		if($str == $this->input->post('str_Senha'))
		{
			$this->form_validation->set_message('password_check', 'O campo %s deve ser diferente do campo <strong>SENHA</strong>.');
			
			return false;
		}
		else
		{
			return true;
		}
	}

	/*
	 *  Metodo que confirma se a senha digitada confere
	 */
	public function confirm_senha($str)
	{
		if($str != $this->perfil_model->get_senha($this->session->userdata('idUsuario')))
		{
			$this->form_validation->set_message('confirm_senha', 'A %s digitada não confere com a atual.');
			return false;
		}
		else
		{
			return true;
		}		
	}

	/*
	 *  Metodo que efetua o upload da imagem
	 */
	public function avatar()
	{
		$targetFolder   	   =    'assets/upload';
		$path_parts_files      =    pathinfo($_FILES['Fileavatar']['name']);
		$str_arquivo           =    sanitize_title_with_dashes($path_parts_files['filename']) . '.' . deepLower($path_parts_files['extension']);
		$data['idUsuario']     =    $this->session->userdata('idUsuario');
		$data['strFoto']	   =    $str_arquivo;
		
		if(move_uploaded_file($_FILES['Fileavatar']['tmp_name'], $targetFolder . '/' . $str_arquivo))
		{
			$this->perfil_model->set_uploads_avatar($data);
		}

		echo '<div class="container_foto">
		
				  '.get_picture($str_arquivo, '180', '200').'

				  <input id="file" name="Fileavatar" type="file" hidefocus="true" size="1" accept="image/jpeg" onchange="efetuaUpload(\'FormUpload\', \''.base_url().'perfil/avatar\', \'Avatar\', \'Aguarde\', \'\')" />

			  </div>';
	}
}