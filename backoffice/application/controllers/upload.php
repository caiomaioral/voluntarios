<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller {

    private $Participantes  =  'sig_Participantes';

    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('upload_model');
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['AddCss']  		     =   load_css(array('upload/upload'));
        $this->data['AddJavascripts']    =   load_js(array('upload/upload'));

        $this->usable_system('upload');
    }

    //
    // Efetua o upload da planilha de Excel
    //	    
    public function excel()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->index();	
        }
        else
        {
            if(isset($_FILES['customFile'])) 
            {
                $this->upload_model->set_lista_excel($_FILES['customFile']);
            }

            //redirect(base_url() . 'lotes', 'refresh');			
        }        
    }
    
    //
    // Metodo que verifica se foi upada o arquivo de lote
    //
    public function handle_upload()
    {
        if(isset($_FILES['customFile']) && !empty($_FILES['customFile']['name']))
        {
            return true;
        }
        else
        {
            // Seta o erro
            $this->form_validation->set_message('handle_upload', 'Você deve selecionar um arquivo de Excel com os <strong>CONTATOS</strong>.');
            return false;
        }
    }    
}