<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presenca extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

		$this->load->model('presenca_model');
		$this->load->model('relatorios_model');
    }
	 
	//
	// Chama a view de pedidos
	//
	public function index()
    {
		$this->data['AddCss']  			 =   load_css(array('presenca/presenca'));
		$this->data['AddJavascripts']    =   load_js(array('presenca/presenca'));
		
		$this->usable('presenca');
	}
	
	//
	// Chama a view de pedidos
	//
	public function cpf()
    {
		$this->data['AddCss']  			 =   load_css(array('presenca/presenca'));
		$this->data['AddJavascripts']    =   load_js(array('presenca/cpf'));
		$this->data['AddEventos']        =   $this->relatorios_model->get_eventos_dropdown_search($this->session->userdata('intTipo'));
		
		$this->usable('presenca/cpf');
	}
	
	//
	// Metodo que faz check-in por CPF
	// 
	public function getParticipantConfirmCPF()
	{
		$Data['Eventos']   =   $this->input->post('Eventos');
		$Data['CPF']       =   $this->input->post('CPF');

		$Data['GetCodigo']  =  $this->presenca_model->GetParticipantByCPF($Data);
	}
	
	//
	// Metodo que pega a rota
	// 
	public function getParticipantConfirm()
    {
		// Modelo do codigo
		// 28736925888&22&2
        // 28736925888&1&2
        // 10463029810&62&2
		//
		$Data['Codigo']   =   $this->input->post('Codigo');

		$Codigo = explode('&', $Data['Codigo']);

		$GetCodigo  =  $this->presenca_model->GetParticipantByNumber($Codigo);
		
		echo $GetCodigo;
	}
	
    //
	// Metodo que reverte um check-in
	//
    public function rollback($id_Inscrito)
    {
        $this->presenca_model->execute_rollback($id_Inscrito);	
    }

	/*
     *  @param string $cpf O CPF com ou sem pontos e traço
     *  @return bool True para CPF correto - False para CPF incorreto
     */
    public function valida_cpf($cpf = false) 
    {
        $cpf  =  str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        /**
         * Multiplica dígitos vezes posições 
         *
         * @param string $digitos Os digitos desejados
         * @param int $posicoes A posição que vai iniciar a regressão
         * @param int $soma_digitos A soma das multiplicações entre posições e digitos
         * @return int Os digitos enviados concatenados com o último dígito
         *
         */
        if(!function_exists('calc_digitos_posicoes'))
        {
            function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0) 
            {
                // Faz a soma dos digitos com a posição
                // Ex. para 10 posições: 
                //   0    2    5    4    6    2    8    8   4
                // x10   x9   x8   x7   x6   x5   x4   x3  x2
                // 	 0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                for ($i = 0; $i < strlen($digitos); $i++) 
                {
                    $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                    $posicoes--;
                }

                // Captura o resto da divisão entre $soma_digitos dividido por 11
                // Ex.: 196 % 11 = 9
                $soma_digitos = $soma_digitos % 11;

                // Verifica se $soma_digitos é menor que 2
                if($soma_digitos < 2) 
                {
                    // $soma_digitos agora será zero
                    $soma_digitos = 0;
                } 
                else 
                {
                    // Se for maior que 2, o resultado é 11 menos $soma_digitos
                    // Ex.: 11 - 9 = 2
                    // Nosso dígito procurado é 2
                    $soma_digitos = 11 - $soma_digitos;
                }

                // Concatena mais um digito aos primeiro nove digitos
                // Ex.: 025462884 + 2 = 0254628842
                $cpf = $digitos . $soma_digitos;

                // Retorna
                return $cpf;
            }
        }

        // Verifica se o CPF foi enviado
        if(!$cpf) 
        {
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }

        // Remove tudo que não é número do CPF
        // Ex.: 025.462.884-23 = 02546288423
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o CPF tem 11 caracteres
        // Ex.: 02546288423 = 11 números
        if(strlen($cpf) != 11) 
        {
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }
        
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
        }          

        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($cpf, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = calc_digitos_posicoes($digitos);

        // Faz o cálculo dos 10 digitos do CPF para obter o último dígito
        $novo_cpf = calc_digitos_posicoes($novo_cpf, 11);

        // Verifica se o novo CPF gerado é identico ao CPF enviado
        if($novo_cpf === $cpf)
        {
            // CPF válido
            return true;
        } 
        else 
        {
            // CPF inválido
            $this->form_validation->set_message('valida_cpf', '<p><strong>CPF</strong> inválido, favor utilizar um correto.</p>');
            
            return false;
        }
    }
}