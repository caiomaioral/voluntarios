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
    public $content = 'ofertas';

    public function __construct()
	{
		parent::__construct();
        
		$this->data['key'] = '@Setva01Na';
		
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');
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
		if(isset($this->data['title']) == false)  
		
		$this->data['title'] = NAME_SITE;
		
        // Gera o Conteudo
        $this->content = $content;

     	// Carrega os CSS
        $this->data['CssProjects'] = load_bootstrap(array('bootstrap'));        

		// Carrega o Javascript
		$this->data['Javascripts'] = load_js(array('jquery-1.10.2.min', 
												   'jquery.validate.min', 
												   'jquery.validate.unobtrusive.min', 
												   'jquery.maskedinput-1.1.4.pack',
												   'jquery.mask'));

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
    public function usable_boostrap($content)
	{
		if(isset($this->data['title']) == false)  
		
		$this->data['title'] = NAME_SITE;
		
        // Gera o Conteudo
        $this->content = $content;

     	// Carrega os CSS
		$this->data['CssProjects'] = load_bootstrap(array('bootstrap'));
		
		// Carrega o Javascript
		$this->data['Javascripts'] = load_js(array('jquery-1.10.2.min', 
												   'jquery.validate.min', 
												   'jquery.validate.unobtrusive.min', 
												   'jquery.maskedinput-1.1.4.pack',
												   'jquery.mask'));

        $this->load->view('default/header', $this->data);
        $this->load->view($this->content, $this->data);
        $this->load->view('default/footer', $this->data);
    }    
}