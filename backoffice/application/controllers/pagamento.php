<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagamento extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('ID_Pagamento') == 1)
        {
            redirect(base_url() . 'home', 'refresh');
        }          
        
        $this->load->model('autenticacao_model');
        $this->load->model('pagamento_model');
        $this->load->model('atleta_model');
    }
    
    //
    // Exibe o formulario de quitação de mensalidade
    //
    public function quitar($IdAtleta)
    {
        $this->data['AddCss']  		       =   load_css(array());
        $this->data['AddJavascripts']      =   load_js(array('pagamento/pagamento'));
        
        $this->data['AddAtleta']	       =   $this->atleta_model->get_atleta($IdAtleta);
        $this->data['AddPagamento']	       =   $this->pagamento_model->get_pagamentos_por_atleta($IdAtleta);
        
        $this->usable('pagamento');
    }

    //
    // Metodo que salva o modo de pagamento
    //
    public function efetar_baixa()
    {
        $Data['Id']     =   $this->input->post('Id_Pagamento');
        $Data['Pago']   =   $this->input->post('Status');
        
        $this->pagamento_model->update_pagamento($Data);            
    }

    //
    // Metodo que gera o pagamento do ano atual
    //
    public function gerar_pagamento()
    {
        //
        //  Carrega todos os atletas e depois inclui os pagamentos
        //        
        $AddAtletas  =  $this->atleta_model->get_all_atletas();

		foreach($AddAtletas as $sAddAtletas):
			
            foreach($this->pagamento_model->get_all_tipos() as $sAddTipos):

                $Data['IdAtleta']     =     $sAddAtletas->Id;
                $Data['Exercicio']    =     $sAddTipos->Id;
                $Data['Valor']        =     $sAddTipos->Valor;
                $Data['Pago']         =     0;
                $Data['Ano']          =     date('Y');
                
                $this->pagamento_model->insert_pagamento($Data);

            endforeach; 
			
		endforeach;           
    }
}