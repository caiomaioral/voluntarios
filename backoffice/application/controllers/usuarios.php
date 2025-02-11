<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('intTipo') <> 0)
        {
            redirect(base_url() . 'eventos');
        }

        $this->load->model('usuarios_model');
        $this->load->model('relatorios_model');
    }
	 
    /*
     *  Lista os usuários por evento
     */
    public function index()
    {
        $this->data['AddCss']   	    =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'usuarios/usuarios'));
        
        $this->usable('usuarios');
    }
	
    /*
     *  Método que visualiza os eventos
     */    
    public function visualizar($id_evento)
    {
        $this->data['AddCss']   	    =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'financeiro/financeiro'));
        $this->data['AddData']          =   $this->financeiro_model->get_eventos($id_evento);
        
        $this->data['AddStatus']        =   array(NULL => 'Selecione um Status',
                                                     1 => 'Aguardando', 
                                                     2 => 'Pago',
                                                     3 => 'Negado',
                                                     4 => 'Expirado',
                                                     5 => 'Cancelado',
                                                     6 => 'Não Finalizado',
                                                     7 => 'Autorizado');

        $this->usable('financeiro');		
    }

    /*
     *  Lista os usuarios
     */	
    public function listar_ajax()
    {
        echo $this->usuarios_model->listar_usuarios_json();
    }
	
    /*
     *  Metodo para inclusão de usuarios
     */
    public function incluir()
    {
        $this->data['AddCss']           =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']   =   load_js(array('eventos/incluir'));
        $this->data['AddEventos']       =   $this->relatorios_model->get_eventos_dropdown_search(0);

        $this->usable('usuarios/incluir');
    }
    
    /*
     *  Metodo que inclui um evento
     */
    public function enviar()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir();
        }
        else
        {
            $Data['strNome']         =   trim($this->input->post('str_Nome'));
            $Data['strLogin']        =   mb_strtolower(trim($this->input->post('str_Login')));
            $Data['strSenha']    	 =   trim($this->input->post('str_Senha'));
            $Data['strEmail']    	 =   mb_strtolower(trim($this->input->post('str_Login')));
            $Data['intTipo']         =   $this->input->post('str_Evento');
            $Data['dtCadastro']    	 =   date('Y-m-d');
            
            /*
            *  Efetua a persistência no banco de dados
            */	                
            $this->usuarios_model->insert_usuario($Data);
            
            redirect(base_url() . 'usuarios');
        }
    }

    /*
     *  Metodo que exibe a janela com os dados dos eventos
     */
    public function alterar($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/alterar'));
        $this->data['DataBody'] 	        =   $this->usuarios_model->get_usuario($id);
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search(0);

        $this->usable('usuarios/alterar');
    }

    /*
     *  Metodo que salva a alteração de um evento
     */
    public function salvar()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('ID'));	
        }
        else
        {
            $Data['idUsuario']       =   $this->input->post('ID');
            $Data['strNome']         =   trim($this->input->post('str_Nome'));
            $Data['strLogin']        =   mb_strtolower(trim($this->input->post('str_Login')));
            $Data['strSenha']    	 =   trim($this->input->post('str_Senha'));
            $Data['strEmail']    	 =   mb_strtolower(trim($this->input->post('str_Login')));
            $Data['intTipo']         =   $this->input->post('str_Evento');
            $Data['dtCadastro']    	 =   date('Y-m-d');
            
            /*
            *  Efetua a persistência no banco de dados
            */	                
            $this->usuarios_model->update_usuario($Data);
            
            redirect(base_url() . 'usuarios');						
        }
    }	

    /*
     *  Chama o metodo do model para ativar um aluno
     */
    public function ativar($id)
    {
        $dados['id_eventos']  =  $id;
        $dados['status']      =  1;

        $this->financeiro_model->ativar_eventos($dados);	
    }

    /*
     *  Chama o metodo do model para inativar um aluno
     */
    public function inativar($id)
    {
        $dados['id_eventos']  =  $id;
        $dados['status']      =  0;

        $this->financeiro_model->inativar_eventos($dados);	
    }

    /*
     *  Chama o metodo do model para "exclusão" do registro
     */
    public function excluir($id)
    {
        $this->usuarios_model->excluir($id);
    }
}