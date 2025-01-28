<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bootstrap extends MY_Controller {
	 
    public function __construct()
	{
        parent::__construct();
		
        $this->load->model('inscricao_model');		
	}

	//
	// Metodo que chama view index
	//
	public function index()
	{
        $this->data['AddCss']  	         =  load_css(array('site'));
        $this->data['AddJavascript']  	 =  load_js(array('scripts'));
		
		$this->data['AddIgreja']         =   $this->inscricao_model->get_igrejas_dropdown();
		
		$this->usable_boostrap('ofertas_bootstrap');
	}

	//
	// Metodo que verifica se o cara já esta cadastrado
	//
    function verificar_doador($CPF)
    {
		$Data['CPF']  =  str_replace('-', '', str_replace('.', '', $CPF));
        
		$Result = $this->inscricao_model->check_donation($Data['CPF']);

		header("Content-Type: application/json", true);

		if($Result == false)
		{
			echo json_encode(array('Status' => 0));
		}
		else
		{
			echo json_encode(array('Id' => $Result->id_doador, 'Doador' => $Result->str_nome, 'Email' => $Result->str_email)); 
		}
    }

	//
	// Metodo que chama o post
	//
	public function salvar()
	{
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			//
			// Tabela de Doadores e Cartão
			//
			$caracteres = array("(", ")", " ", "-");
			
			$Doador['str_nome'] 	        =    mb_strtoupper(trim($this->input->post('Doador')));
			$Doador['str_cpf'] 		        =    str_replace('-', '', str_replace('.', '', $this->input->post('CPF')));
			$Doador['str_email'] 	        =    mb_strtolower(trim($this->input->post('Email')));
			$Doador['id_igreja']            =    $this->input->post('Igreja');
			$Doador['dt_cadastro']          =    date('Y-m-d H:i:s');           
			
			//
			// Criar arquivo de cartão
			//
			$Cartao['order_number']         =    inclui_zero_esq($this->inscricao_model->Get_Pedido());
			$Cartao['customer_name']        =    mb_strtoupper(trim($this->input->post('Doador')));
			$Cartao['customer_identity'] 	=    str_replace('-', '', str_replace('.', '', $this->input->post('CPF')));
			$Cartao['valor']      			=    num_to_db($this->input->post('DoacaoValor'));
			$Cartao['created_date']         =    date('Y-m-d H:i:s');   
			
			// Chama a rotina de inclusão
			if($this->inscricao_model->check_person_donation($Doador['str_cpf']) == true)
			{
				$this->inscricao_model->insert_doador($Doador);

				$Cartao['id_doador']  =  $this->db->insert_id();
			}
			else
			{
				$Cartao['id_doador']  =  $this->inscricao_model->get_id_doador($Doador['str_cpf']);	
			}

			//
			// Verifica se o cara escolheu boleto (== 1 é cartão)
			//
			$this->inscricao_model->insert_cartao($Cartao);
			
			//
			// Cria uma variavel Body para mostrar os dados
			//
			$this->session->set_flashdata('Body', array_merge($Doador, $Cartao));
			
			//
			// Chama a confirmação
			//			
			redirect(base_url() . 'bootstrap/confirmacao');
		}
	}
	
	//
	// Metodo que chama view index
	//
	public function confirmacao()
	{
		if($this->session->flashdata('Body') != '')
		{
			$this->data['AddCss'] 		    =   load_css(array('template', 'app', 'fonts'));
			$this->data['AddJavascript']  	=   load_js(array('scripts', 'cartao'));
			
			$this->data['AddIgreja']        =   $this->inscricao_model->get_igrejas_dropdown();
			$this->data['Body'] 			=   $this->session->flashdata('Body');
			
			$this->usable('confirmacao');
		}
		else
		{
			redirect(base_url() . 'ofertas');	
		}
	}

	/*
	 *  Metodo que chama view index
	 */
	public function sucesso()
	{
		$this->data['AddCss']   =   load_css(array('template', 'app', 'fonts'));
		
		$this->usable('sucesso');
	}

	/*
	 *  Metodo que valida CPF
	 */
	public function validarCPF($CPF)
	{ 
		if(!validaCPF($CPF))
		{ 
			$this->form_validation->set_message('validarCPF', 'O campo <strong>CPF</strong> esta inválido.');
			return false; 
		} 
		else
		{ 
			return true;
		} 
	}

	/*
	 *  Metodo que verifica a doação
	 */	
	function maximumCheck($num)
	{
		if(num_to_db($num) < 10)
		{
			$this->form_validation->set_message('maximumCheck', 'O <strong>VALOR DE DOAÇÃO</strong> deve ser no valor mínimo de R$ 10,00.');
			
			return false;
		}
		else
		{
			return true;
		}
	}	
}