<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('perfil_model');
        $this->load->model('pagamento_model');
        $this->load->model('elenco_model');
        $this->load->model('competicoes_model');
        $this->load->model('escalacao_model');

        $this->data['AddCss']  		       =   load_css(array('home/home'));
        $this->data['AddJavascripts']      =   load_js(array('home/home'));
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['error']  =  "";
        
        $this->usable_system('home');
    }
}