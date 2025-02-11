<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bilheteria extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        $this->load->model('bilheteria_model');
        $this->load->model('gateway/eventos_model');
        $this->load->model('gateway/inscricao_model');
        $this->load->model('gateway/financeiro_model');        
    }
	 
    //
    // Chama o metodo para incluir o participante que vai pagar no balcão
    //    
    public function incluir_bilheteria($id_evento)
    {
        //
        // Mostrar os dados do evento para incluir no form
        //
        $this->data['AddBody']    =    $this->bilheteria_model->get_dados_evento($id_evento);

        $Numero_Inscritos     =    $this->data['AddBody']->vendidos;
        $Numero_Maximo        =    $this->data['AddBody']->limite;

        if($Numero_Inscritos >= $Numero_Maximo)
        {
            $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
            
            $this->usable_form('bilheteria/encerrado');            
        }
        else
        {
            $this->data['AddJavascripts']    =    load_js(array('form/form_eventos_bilheteria'));
            
            $this->usable_form('bilheteria/form_eventos_bilheteria');                   
        }
    }

    //
    // Metodo que adiciona participantes de acordo com o proposto no admin
    //       
    public function enviar_bilheteria_evento()
    {
        $CPF  =  NULL;
        
        //
        // Tabela de Pagamento
        //
        $Data['id_Evento'] 		            =    $this->input->post('id_evento');
        $Data['id_Evento_Link']             =    $this->input->post('id_evento_link');
        $Data['Nome']        	            =    trim(mb_strtoupper('CADASTRO EFETUADO PELA BILHETERIA'));
        $Data['CPF']                        =    $CPF;
        $Data['Email']    	                =    trim(mb_strtolower('bilheteria@boladeneve.com'));
        $Data['Valor']                      =    $this->input->post('ValorTotal');
        $Data['Quantidade_Ingressos']    	=    $this->input->post('sel_Adicionais');
        $Data['Tipo_Pagamento']             =    $this->input->post('sel_Pagamento');
        $Data['Parcelas']    	            =    1;
        $Data['Modal']                      =    'bilheteria';
        $Data['Status_Pagamento']    	    =    2;
        $Data['Data_Cadastro'] 	            =    date('Y-m-d H:i');	
        $Data['Data_Pagamento'] 	        =    date('Y-m-d H:i');

        //
        //  Bota para dentro o Pagamento em Cartão
        // 
        $Participante['Pedido']           =     $this->bilheteria_model->insert_cartao($Data);
        $Participante['Data_Cadastro']    =     $Data['Data_Cadastro'];        
        $Participante['Modal']            =     'bilheteria';

        //
        //  Quantidade de Adicionais
        //  E checa se é maior que zero por precaução
        // 
        $Quantidade  =  $this->input->post('sel_Adicionais');

        if($Quantidade > 0)
        {
            for($x = 1; $x <= $Quantidade; $x++)
            {
                $Participante['id_Evento']         =     $this->input->post('id_evento');
                $Participante['id_Evento_Link']    =     $this->input->post('id_evento_link');
                
                //
                // Chama os arrays
                //
                $Participante['Nome'] = $Data['Nome'];
                
                //
                // Chama os arrays
                //
                $Participante['CPF'] = $Data['CPF'];

                //
                // Chama os arrays
                //                
                $Participante['Pago'] = 1;
                
                //
                //  Bota para dentro os inscritos
                //                    
                $this->bilheteria_model->insert_inscrito($Participante);
            }

            //
            // Controle de ingressos
            //
            $Controle['id_evento_link']       =    $Data['id_Evento_Link'];
            $Controle['vendidos']             =    $Quantidade;
            $Controle['status']               =    2;

            //
            // Persistência no banco de dados
            //
            $this->financeiro_model->update_tickets($Controle);             
        }

        redirect(base_url() . 'bilheteria/sucesso/' . $this->input->post('id_evento_link'));
    }

    //
    // Metodo que atualiza os valores dos ingressos
    //    
    public function calcular()
    {
        // 0 = Não, irei pagar para outra pessoa.
        // 1 = Sim, é para mim mesmo.

        $Valor_Base 	=  $this->input->get('valor');
        $Quantidade 	=  $this->input->get('quantidade');
        $Transporte 	=  $this->input->get('transporte');
        $Valor_Trans    =  $this->input->get('vtrans');
        $Participante   =  ($this->input->get('participante') == -1)? 1 : $this->input->get('participante');
        $Transporte     =  0;
        
        if($Quantidade == 0)
        {
            if($Transporte != 0)
            {
                $Valor_Total = $Valor_Base + $Valor_Trans;
            }
            else
            {
                $Valor_Total = $Valor_Base;
            }					
        }
        elseif($Participante == 0 && $Quantidade == 1)
        {
            if($Transporte == 1)
            {
                if($Quantidade == 1) 
                {
                    $Valor_Total = ($Valor_Base + $Valor_Trans);
                }
                else
                {
                    $Valor_Total = ($Valor_Base * $Quantidade - 1 + $Valor_Base + $Valor_Trans);
                }
            }
            else
            {
                if($Quantidade == 1) 
                {
                    $Valor_Total = ($Valor_Base);	
                }
                else
                {
                    $Valor_Total = ($Valor_Base * $Quantidade - 1 + $Valor_Base);	
                }
            }
        }
        elseif($Participante == 1 && $Quantidade == 1)
        {
            $Quantidade = $Quantidade + 1;

            if($Transporte == 1)
            {
                $Valor_Transporte = ($Valor_Trans * $Quantidade);
                $Valor_Total = (($Quantidade * $Valor_Base) + $Valor_Transporte);
            }
            else
            {
                $Valor_Total = ($Valor_Base * $Quantidade);	
            }
        }
        elseif($Participante == 0 && $Quantidade == 2)
        {
            $Quantidade = $Quantidade;

            if($Transporte == 1)
            {
                $Valor_Transporte = ($Valor_Trans * $Quantidade);
                $Valor_Total = (($Quantidade * $Valor_Base) + $Valor_Transporte);
            }
            else
            {
                $Valor_Total = ($Valor_Base * $Quantidade);	
            }
        }
        elseif($Participante == 1 && $Quantidade == 2)
        {
            $Quantidade = $Quantidade + 1;

            if($Transporte == 1)
            {
                $Valor_Transporte = ($Valor_Trans * $Quantidade);
                $Valor_Total = (($Quantidade * $Valor_Base) + $Valor_Transporte);
            }
            else
            {
                $Valor_Total = ($Valor_Base * $Quantidade);	
            }
        }
        elseif($Participante == 0 && $Quantidade > 2)
        {
                $Quantidade = $Quantidade;

                if($Transporte == 1)
                {
                    $Valor_Transporte = ($Valor_Trans * $Quantidade);
                    $Valor_Total = (($Quantidade * $Valor_Base) + $Valor_Transporte);
                }
                else
                {
                    $Valor_Total = ($Valor_Base * $Quantidade);	
                }
        }
        else 
        {
            $Quantidade = $Quantidade + 1;

            if($Transporte == 1)
            {
                $Valor_Transporte = ($Valor_Trans * $Quantidade);
                $Valor_Total = (($Quantidade * $Valor_Base) + $Valor_Transporte);
            }
            else
            {
                $Valor_Total = ($Valor_Base * $Quantidade);	
            }
        }

        echo $_return = '<font style="font-size:20px"><strong>R$ '.number_format($Valor_Total, 2, ',', '.').'</strong></font>
                         <input id="ValorTotal" name="ValorTotal" type="hidden" value="'.$Valor_Total.'" /><br />Valor total a ser pago.';
    }    

    //
    // Metodo que exibe uma mensagem de sucesso ao fim do cadastro do participante
    //    
    public function sucesso($id_evento)
    {
        $this->data['AddCss']            =    load_css(array('BreadCrumb', 'form/style'));
        $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
        
        $this->data['AddBody']           =    $this->bilheteria_model->get_dados_evento($id_evento);

        $this->usable_form('bilheteria/sucesso_bilheteria');   
    }    
}