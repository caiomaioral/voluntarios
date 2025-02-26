<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financeiro extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

		//
		// Validando se pode ou não acessar a pagina
		//
		if($this->session->userdata('Admin') == 0)
        {
            redirect(base_url() . 'home');
        }         
		
        $this->load->model('pagamento_model');
    }
	
    //
    // Metodo que chama view de demonstrativo de pagamento
    //
    public function index()
    {
        $this->data['AddCss']            =    load_css(array('ajax'));
        $this->data['AddJavascript']     =    load_js(array('financeiro/financeiro'));
        $this->data['Natureza']          =    $this->input->post('Natureza');
        $this->data['Periodo']           =    $this->input->post('Periodo');
        $this->data['Pendentes']         =    $this->input->post('Pendentes');
        
        $this->usable('financeiro');
    }

    //
    // Metodo que chama view de demonstrativo de pagamento
    //    
    public function lancamento()
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->usable('pagamento/lancar');
    }

    //
    // Metodo que salva a inclusão de lançamento
    //
    public function enviar()
    {
        $this->form_validation->set_rules('Descricao',              '<strong>DESCRIÇÃO</strong>', 				         'trim|required');
        $this->form_validation->set_rules('Valor',                  '<strong>VALOR</strong>',                            'required');
        $this->form_validation->set_rules('Natureza',               '<strong>OPERAÇÃO</strong>', 				         'required');
        $this->form_validation->set_rules('Periodo',                '<strong>PERÍODO</strong>', 				         'required');

        // 
        // Chamada do Submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->lancamento();
        }
        else
        {
            //
            // Tabela de Pagamento
            //
            $Data['Descricao']        	    =    mb_strtoupper($this->input->post('Descricao', TRUE));
            $Data['Valor']                  =    num_to_db($this->input->post('Valor'));
            $Data['NaturezaOperacao']       =    $this->input->post('Natureza');
            $Data['Periodo']                =    $this->input->post('Periodo');
            $Data['Pago']        	        =    1;
            $Data['Ano']                    =    date('Y');
            
            //
            // Manda o pagamento 
            //
            $this->pagamento_model->insert_pagamento($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'financeiro');
        }
    }

    //
    // Metodo que chama view alterar um lançamento
    //    
    public function alterar_lancamento($IdPagamento)
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->data['AddLancamento']	=   $this->pagamento_model->get_pagamentos_extras($IdPagamento);

        $this->usable('pagamento/alterar');
    }
    
    //
    // Metodo que salva a alteração de despesa
    //
    public function salvar()
    {
        $this->form_validation->set_rules('Descricao',              '<strong>DESCRIÇÃO</strong>', 				         'trim|required');
        $this->form_validation->set_rules('Valor',                  '<strong>VALOR</strong>',                            'required');
        $this->form_validation->set_rules('Natureza',               '<strong>OPERAÇÃO</strong>', 				         'required');
        $this->form_validation->set_rules('Periodo',                '<strong>PERÍODO</strong>', 				         'required');

        // 
        // Chamada do Submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_lancamento($this->input->post('IdPagamento', TRUE));
        }
        else
        {
            //
            // Tabela de Pagamento
            //
            $Data['Id']        	            =    $this->input->post('IdPagamento', TRUE);
            $Data['Descricao']        	    =    mb_strtoupper($this->input->post('Descricao', TRUE));
            $Data['Valor']                  =    num_to_db($this->input->post('Valor'));
            $Data['NaturezaOperacao']       =    $this->input->post('Natureza');
            $Data['Periodo']                =    $this->input->post('Periodo');
            
            //
            // Alterar
            //
            $this->pagamento_model->update_pagamento($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'financeiro');
        }
    }     
    
    //
	// Metodo que chama a exclusão de pagamentos 
	//
    public function excluir($IdPagamento)
    {
        $this->pagamento_model->excluir_pagamento($IdPagamento);
    }     
}