<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('notification_model');
    }
	 
    //
    // Chama o metodo para incluir os eventos que estão para ser incluidos
    //    
    public function index()
    {
        //
        // Alimenta os ultimos eventos cadastrados
        //
        $this->notification_model->get_evento_notification();    
    }
}