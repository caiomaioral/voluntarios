<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Termo extends MY_Controller {
	 
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

		$this->usable('termo');
	}
}