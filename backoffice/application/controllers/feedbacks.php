<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedbacks extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('feedback_model');
    }
	 
	//
	// Exibe o formulario de feedbacks
	//
    public function index()
    {
		$this->usable('feedbacks');
    }

	//
	// Metodo que envia o feedback
	//
	public function enviar()
    {
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$Data['ID_Atleta'] 	  =    $this->session->userdata('Id');
			$Data['Sugestao']     =    $this->input->post('Sugestao');
			
			$this->feedback_model->insert_feedback($Data);
			
			$this->usable('feedback/sucesso');
		}
    }
}