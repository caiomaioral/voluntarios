<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Competicoes extends MY_Controller {

    private $atletas  =  'tb_atletas';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('atleta_model');
        $this->load->model('elenco_model');
        $this->load->model('competicoes_model');
    }
	
	//
	// Metodo que chama view de atletas 
	//
    public function index()
    {
        $this->data['AddCss']   	    =   load_css(array('competicoes/competicoes'));
        $this->data['AddJavascripts']   =   load_js(array('competicoes/competicoes'));
        $this->data['Jogos']            =   $this->input->post('Jogos');
        $this->data['Temporada']        =   $this->input->post('Temporada');
        $this->data['Assistencias']     =   $this->input->post('Assistencias');

        $this->usable('competicoes');
    }

	//
	// Metodo que inclui um jogo
	//    
    public function incluir_jogo()
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->usable('competicoes/incluir');
    }

	//
	// Metodo que inclui um jogo
	//    
    public function enviar_jogo()
    {
        $this->form_validation->set_rules('Data',                   '<strong>DATA DO JOGO</strong>', 				     'required|callback_isDate');
        $this->form_validation->set_rules('Mandante',               '<strong>MANDANTE</strong>',                         'trim|required');
        $this->form_validation->set_rules('Visitante',              '<strong>VISITANTE</strong>', 				         'trim|required');
        $this->form_validation->set_rules('GolsMandante',           '<strong>GOLS MANDANTE</strong>', 				     'required|numeric');
        $this->form_validation->set_rules('GolsVisitante',          '<strong>GOLS VISITANTE</strong>', 				     'required|numeric');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_jogo();
        }
        else
        {
            //
            // Tabela de Jogo
            //
            $Data['Data']        	     =    data_br_to_us($this->input->post('Data'));
            $Data['Mandante']            =    mb_strtoupper($this->input->post('Mandante'));
            $Data['Visitante']        	 =    mb_strtoupper($this->input->post('Visitante'));
            $Data['GolsMandante'] 		 =    $this->input->post('GolsMandante');
            $Data['GolsVisitante'] 		 =    $this->input->post('GolsVisitante');
            $Data['Ano']        	     =    date('Y');

            //
            // Include
            //
            $this->competicoes_model->insert_jogos($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'competicoes');
        }        
    }

    //
	// Metodo que altera um jogo
	//    
    public function alterar_jogo($IdJogo)
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->data['AddJogo']	        =   $this->competicoes_model->get_jogo($IdJogo);

        $this->usable('competicoes/alterar');
    }

	//
	// Metodo que inclui um jogo
	//    
    public function salvar_jogo()
    {
        $this->form_validation->set_rules('Data',                   '<strong>DATA DO JOGO</strong>', 				     'required|callback_isDate');
        $this->form_validation->set_rules('Mandante',               '<strong>MANDANTE</strong>',                         'trim|required');
        $this->form_validation->set_rules('Visitante',              '<strong>VISITANTE</strong>', 				         'trim|required');
        $this->form_validation->set_rules('GolsMandante',           '<strong>GOLS MANDANTE</strong>', 				     'required|numeric');
        $this->form_validation->set_rules('GolsVisitante',          '<strong>GOLS VISITANTE</strong>', 				     'required|numeric');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_jogo($this->input->post('IdJogo'));
        }
        else
        {
            //
            // Tabela de Jogo
            //
            $Data['Id']        	         =    $this->input->post('IdJogo');
            $Data['Data']        	     =    data_br_to_us($this->input->post('Data'));
            $Data['Mandante']            =    mb_strtoupper($this->input->post('Mandante'));
            $Data['Visitante']        	 =    mb_strtoupper($this->input->post('Visitante'));
            $Data['GolsMandante'] 		 =    $this->input->post('GolsMandante');
            $Data['GolsVisitante'] 		 =    $this->input->post('GolsVisitante');

            //
            // Change
            //
            $this->competicoes_model->update_jogo($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'competicoes');
        }        
    }    

	//
	// Metodo que exclui um jogo
	//    
    public function excluir($IdJogo)
    {
        $this->competicoes_model->excluir_sumula($IdJogo);
        $this->competicoes_model->excluir_jogo($IdJogo);
    }

	//
	// Metodo que inclui um artilheiro
	//     
    public function incluir_artilheiro()
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->data['AddAtletas']       =   $this->competicoes_model->get_atletas_dropdown();
        $this->data['AddJogo']          =   $this->competicoes_model->get_jogos_dropdown();

        $this->usable('competicoes/incluir_artilheiro');
    }

	//
	// Metodo que envia um artilheiro a lista
	//     
    public function enviar_artilheiro()
    {
        $this->form_validation->set_rules('Jogador',                '<strong>ATLETA</strong>', 				         'required|greater_than[0]');
        $this->form_validation->set_rules('Gols',                   '<strong>GOLS</strong>',                         'required|numeric');
        $this->form_validation->set_rules('Assistencias',           '<strong>ASSISTÊNCIAS</strong>',                 'numeric');
        $this->form_validation->set_rules('Jogo',                   '<strong>JOGO</strong>',                         'required|greater_than[0]');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_artilheiro();
        }
        else
        {
            //
            // Tabela de Artilheiro
            //
            $Data['IdAtleta']     	     =    $this->input->post('Jogador');
            $Data['IdJogo'] 	         =    $this->input->post('Jogo');
            $Data['Gols'] 		         =    $this->input->post('Gols');
            $Data['Assistencias'] 		 =    ($this->input->post('Assistencias') == '')? 0 : $this->input->post('Assistencias');
            $Data['Ano'] 		         =    date('Y');

            //
            // Include
            //
            $this->competicoes_model->insert_artilheiro($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'competicoes');
        }        
    }

	//
	// Metodo que altera um artilheiro
	//     
    public function alterar_artilheiro($IdArtilheiro)
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->data['AddAtletas']       =   $this->competicoes_model->get_atletas_dropdown();
        $this->data['AddAtleta']        =   $this->competicoes_model->get_artilheiro($IdArtilheiro);
        $this->data['AddJogo']          =   $this->competicoes_model->get_jogos_dropdown();
        
        $this->usable('competicoes/alterar_artilheiro');
    }    

	//
	// Metodo que salva um artilheiro a lista
	//     
    public function salvar_artilheiro()
    {
        $this->form_validation->set_rules('Jogador',                '<strong>ATLETA</strong>', 				         'required|greater_than[0]');
        $this->form_validation->set_rules('Gols',                   '<strong>GOLS</strong>',                         'required|numeric');
        $this->form_validation->set_rules('Assistencias',           '<strong>ASSISTÊNCIAS</strong>',                 'numeric');
        $this->form_validation->set_rules('Jogo',                   '<strong>JOGO</strong>',                         'required|greater_than[0]');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_artilheiro($this->input->post('IdArtilheiro'));
        }
        else
        {
            //
            // Tabela de Artilheiro
            //
            $Data['Id']     	         =    $this->input->post('IdArtilheiro');
            $Data['IdAtleta']     	     =    $this->input->post('Jogador');
            $Data['IdJogo'] 	         =    $this->input->post('Jogo');
            $Data['Gols'] 		         =    $this->input->post('Gols');
            $Data['Assistencias'] 		 =    ($this->input->post('Assistencias') == '')? 0 : $this->input->post('Assistencias');
            $Data['Ano'] 		         =    date('Y');

            //
            // Change
            //
            $this->competicoes_model->update_artilheiro($Data);

            //
            // Redireciona
            //
            redirect(base_url() . 'competicoes');
        }        
    }
    
	//
	// Metodo que exclui um artilheiro
	//    
    public function excluir_artilheiro($IdArtilheiro)
    {
        $this->competicoes_model->excluir_artilheiro($IdArtilheiro);
    }    

	//
	// Metodo que chama view de detalhes 
	//
    public function detalhes($IdJogo)
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        echo 'Calmaaaa, acelerado!';
    }    
}