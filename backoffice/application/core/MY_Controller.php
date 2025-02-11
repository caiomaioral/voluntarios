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
    public $content = 'home';

    public function __construct()
    {
        parent::__construct();

        if(stripos($_SERVER['REQUEST_URI'], '/relatorios') === FALSE)
        {
            if($this->session->userdata('Logado') != TRUE)
            {
                redirect(base_url(), 'refresh');
            }
        }
       
        $this->data['key'] = '@Setva01Na';
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
		
        $this->data['title'] =  NAME_SITE . ' | ' . ucfirst($this->router->class);

        if(stripos($_SERVER['REQUEST_URI'], '/relatorios') === FALSE)
        {
            if($this->session->userdata('Logado') != TRUE)
            {
                redirect(base_url(), 'refresh');
            }
        }

        // Verificador da troca de senha e pré-cadastro
        if($this->session->userdata('strSenha') == '123mudar')
        {
            if($this->router->class != 'perfil') redirect(base_url() . 'perfil');
        }
    	
        // Gera o Conteudo
        $this->content = $content;

        // Invoca o Módulo de usuários
        $this->load->model('user_model');

        // Carrega os temas
        $this->data['Themes'] = load_theme(array('jquery-ui-1.8.4.custom'));

     	// Carrega os CSS
        $this->data['CssProjects'] = load_css(array('reset', 
                                                    'style', 
                                                    'overlay-minimal', 
                                                    'menu', 
                                                    'demo_page', 
                                                    'demo_table_jui', 
                                                    'kwicks', 
                                                    'play_font', 
                                                    'BreadCrumb',
                                                    '../plugins/fancybox/jquery.fancybox-1.3.4'));
													
        //Carrega o Javascript
        $this->data['Javascripts'] = load_js(array('jquery-1.7.min', 
                                                   'jquery-ui-1.8.16.custom.min', 
                                                   'jquery.maskedinput-1.1.4.pack',
                                                   'jquery.jBreadCrumb.1.1', 
                                                   '../plugins/fancybox/jquery.mousewheel-3.0.4.pack', 
                                                   '../plugins/fancybox/jquery.fancybox-1.3.4.pack',
                                                   'kwicks', 
                                                   'css3-mediaqueries',
                                                   'jquery.mask',
                                                   'scripts'));

        $this->data['Perfil']        =   $this->session->userdata('intPerfil');
        $this->data['Usuario']       =   $this->session->userdata('strNome');
        $this->data['Sessions']      =   $this->session->all_userdata();	

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
    public function usable_form($content)
    {
    	if(isset($this->data['title']) == false)  
		
		$this->data['title'] =  NAME_SITE . ' | ' . ucfirst($this->router->class);
		
        if($this->session->userdata('Logado') != TRUE)
        {
            redirect(base_url(), 'refresh');
        }
 	
        //Gera o Conteudo
        $this->content = $content;

        //Invoca o Módulo de usuários
        $this->load->model('user_model');

        //Carrega os temas
		$this->data['Themes'] = load_theme(array('jquery-ui-1.8.4.custom'));

     	//Carrega os CSS
		$this->data['CssProjects'] = load_css(array('reset', 
												    'style', 
												    'overlay-minimal', 
												    'menu', 
												    'demo_page', 
													'demo_table_jui', 
													'kwicks', 
                                                    'play_font',
                                                    'BreadCrumb',
                                                    'bootstrap/bootstrap',
													'form/style'));
													
        //Carrega o Javascript
        $this->data['Javascripts']    =    load_js(array('form/jquery-1.10.2.min', 
                                                         'jquery-ui-1.8.16.custom.min',
                                                         'form/jquery.validate', 
                                                         'form/jquery.metadata', 
                                                         'form/jquery.maskedinput.min', 
                                                         'jquery.jBreadCrumb.1.1',
                                                         'form/main'));

		$this->data['Perfil']        =   $this->session->userdata('intPerfil');
		$this->data['Usuario']       =   $this->session->userdata('strNome');
		$this->data['Sessions']      =   $this->session->all_userdata();	
		
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
    public function usable_box($content)
    {
    	if(isset($this->data['title']) == false)  
		
		$this->data['title'] =  NAME_SITE . ' | ' . ucfirst($this->router->class);
		
        if($this->session->userdata('Logado') != TRUE)
        {
            redirect(base_url(), 'refresh');
        }
 	
        //Gera o Conteudo
        $this->content = $content;

        //Invoca o Módulo de usuários
        $this->load->model('user_model');

        //Carrega os temas
		$this->data['Themes'] = load_theme(array('jquery-ui-1.8.4.custom'));

     	//Carrega os CSS
		$this->data['CssProjects'] = load_css(array('reset', 
												    'style', 
												    'overlay-minimal', 
												    'menu', 
												    'demo_page', 
													'demo_table_jui', 
													'kwicks', 
                                                    'play_font'));
													
        //Carrega o Javascript
        $this->data['Javascripts']    =    load_js(array('form/jquery-1.10.2.min', 
                                                         'jquery-ui-1.8.16.custom.min',
                                                         'form/jquery.validate', 
                                                         'form/jquery.metadata', 
                                                         'form/jquery.maskedinput.min', 
                                                         'form/main'));

		$this->data['Perfil']        =   $this->session->userdata('intPerfil');
		$this->data['Usuario']       =   $this->session->userdata('strNome');
		$this->data['Sessions']      =   $this->session->all_userdata();	
		
        $this->load->view($this->content, $this->data);
    }
    
    /**
     * Verifica se o usuário está logado no sistema
     * Caso positivo o monta a pagina selecionada ou a pagina padrão
     *
     * @access	public
     * @param	string Nome da view a ser carregada
     */
    public function usable_gateway($content)
	{
		if(isset($this->data['title']) == false)  
		
		$this->data['title'] = NAME_SITE;

        //
        // Regra para validar se bloqueia ou não formulário
        //        
        $Hoje       =  strtotime(date('Y-m-d'));
        $Validade   =  strtotime($this->data['AddBody']->validade);
        
        if($this->data['AddBody']->status == 0) $content = 'encerrado';
        
        if($Hoje >= $Validade) $content = 'encerrado';

        //
        // Regra para fechar o formulario dos já vendidos
        //
        if($this->data['AddBody']->limite == $this->data['AddBody']->vendidos) $content = 'encerrado';
        
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
