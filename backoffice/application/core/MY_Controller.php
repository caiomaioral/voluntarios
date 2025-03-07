<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
    /**
     * Dados de acesso
     *
     * @access	public
     * @param array de variaveis
     */
    public $data = array();
    
    /**
     * Nome da view
     *
     * @access	public
     * @param	string
     */
    public $content = 'login';

    public function __construct()
	{
		parent::__construct();
        
		$this->data['key'] = '@Setva01Na';
		
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');

        $this->load->model('autenticacao_model');
    }
    
    /**
     * Verifica se o usuário está logado no sistema
     * Caso positivo o monta a pagina selecionada ou a pagina padrão
     *
     * @access	public
     * @param	string Nome da view a ser carregada
     */
    public function usable($content)
	{
        if(isset($this->data['title']) == false) $this->data['title'] =  NAME_SITE . ' | ' . ucfirst($this->router->class);

        $this->data['class'] = $this->router->class;

        //
        // Verificador da session
        //
        if($this->session->userdata('logado') == false)
        {
            redirect(base_url(), 'refresh');
        }

        // Gera o Conteudo
        $this->content = $content;

     	// Carrega os CSS
        $this->data['CssBootstrap'] = load_bootstrap(array('bootstrap.min'));  
        
     	// Carrega os CSS
        $this->data['CssProjects'] = load_css(array('site')); 
        
        // Carrega os temas
        $this->data['Themes'] = load_theme(array('jquery-ui-1.8.4.custom'));        

		// Carrega o Javascript
        $this->data['Javascripts'] = load_js(array('jquery-3.5.1',
                                                   'jquery.validate', 
                                                   'jquery.metadata', 
                                                   'jquery-ui-1.8.16.custom.min', 
                                                   'jquery.maskedinput-1.1.4.pack',
                                                   'jquery.jBreadCrumb.1.1',                                                    
                                                   'jquery.mask',
                                                   'scripts'));

        // Carrega os JS do Bootstrap
        $this->data['JsBootstrap'] = load_bootstrap_js(array('bootstrap.min', 'bootstrap.bundle.min'));

		$this->load->view('default/header', $this->data);
		$this->load->view($this->content, $this->data);
		$this->load->view('default/footer', $this->data);
    }

    /**
     * Verifica se o usuário está logado no sistema
     * Caso positivo o monta a pagina selecionada ou a pagina padrão
     *
     * @access	public
     * @param	string Nome da view a ser carregada
     */
    public function usable_login($content)
	{
		if(isset($this->data['title']) == false)  
		
		$this->data['title'] = NAME_SITE;
		
        // Gera o Conteudo
        $this->content = $content;

     	// Carrega os CSS
        $this->data['CssProjects'] = load_css(array('site'));
		
        $this->load->view($this->content, $this->data);
    }

    /**
     * Verifica se o usuário está logado no sistema
     * Caso positivo o monta a pagina selecionada ou a pagina padrão
     *
     * @access	public
     * @param	string Nome da view a ser carregada
     */
    public function usable_system($content)
	{
        if(isset($this->data['title']) == false) $this->data['title'] =  NAME_SITE . ' | ' . ucfirst($this->router->class);

        $this->data['class'] = $this->router->class;

        //
        // Verificador da session
        //
        if($this->session->userdata('logado') == false) 
        {
            redirect(base_url(), 'refresh');
        }

        // Gera o Conteudo
        $this->content = $content;

     	// Carrega os CSS
        $this->data['CssBootstrap'] = load_bootstrap(array('bootstrap.min'));  
        
     	// Carrega os CSS
        $this->data['CssProjects'] = load_css(array('site', 'dashboard')); 
        
        //  https://code.jquery.com/jquery-3.7.1.js
        //  https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js
        //  https://cdn.datatables.net/2.2.2/js/dataTables.js
        //  https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js

		// Carrega o Javascript
        $this->data['Javascripts'] = load_js(array('jquery-3.7.1',
                                                   'jquery.validate', 
                                                   'jquery.metadata', 
                                                   'jquery.maskedinput-1.1.4.pack',
                                                   'jquery.jBreadCrumb.1.1',                                                    
                                                   'jquery.mask',
                                                   'scripts'));

        // Carrega os JS do Bootstrap
        $this->data['JsBootstrap'] = load_bootstrap_js(array('bootstrap.min', 'bootstrap.bundle.min'));

		$this->load->view('default/header_system', $this->data);
		$this->load->view($this->content, $this->data);
		$this->load->view('default/footer_system', $this->data);
    }    
}