<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Pagantes extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('financeiro_model');
        $this->load->model('notification_model');
        $this->load->model('eventos_model');
    }
	 
    /*
     *  Lista as familias de acordo com a estudantes logada
     */
    public function index()
    {
        $this->data['AddCss']   	     =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']    =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'pagantes/pagantes'));
        $this->data['AddNotification']   =   $this->notification_model->show_evento_notification();
        
        $this->usable('pagantes');
    }
	
    /*
     *  Método que visualiza os eventos
     */    
    public function visualizar($id_evento)
    {
        $this->data['AddCss']   	    =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'jquery.dataTables.delay.min', 'dataTables.fnReloadAjax', 'financeiro/financeiro'));
        $this->data['AddData']          =   $this->financeiro_model->get_eventos($id_evento);
        $this->data['AddStatus']        =   array(NULL => 'Selecione um Status',
                                                     1 => 'Aguardando', 
                                                     2 => 'Pago',
                                                     3 => 'Negado',
                                                     4 => 'Expirado',
                                                     5 => 'Cancelado',
                                                     6 => 'Não Finalizado',
                                                     7 => 'Rejeitado');

        $this->usable('financeiro');		
    }

    /*
     *  Lista as familias de acordo com a estudantes logada
     */	
    public function listar_ajax()
    {
        echo $this->financeiro_model->listar_eventos_json();
    }
    
    /*
     *  Metodo para inclusão de eventos (bola eventos)
     */
    public function incluir_eventos()
    {
        $this->data['AddCss']           =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']   =   load_js(array('eventos/incluir'));
        $this->data['AddGateway']       =   $this->financeiro_model->get_gate_dropdown();         
        
        $this->data['AddAdicionais']    =   array(0 => 'Selecione', 
                                                  1 => '+ 1 participante', 
                                                  2 => '+ 2 participantes',
                                                  3 => '+ 3 participantes',
                                                  4 => '+ 4 participantes',
                                                  5 => '+ 5 participantes',
                                                  6 => '+ 6 participantes',
                                                  7 => '+ 7 participantes',
                                                  8 => '+ 8 participantes',
                                                  9 => '+ 9 participantes',
                                                  10 => '+ 10 participantes');        

        $this->usable('eventos/incluir_evento');
    }    

    /*
     *  Metodo para inclusão de eventos (bola teatro)
     */
    public function incluir_teatro()
    {
        $this->data['AddCss']           =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']   =   load_js(array('eventos/incluir'));
        $this->data['AddGateway']       =   $this->financeiro_model->get_gate_dropdown();         

        $this->usable('eventos/incluir_teatro');
    }
    
    /*
     *  Metodo que inclui um evento para o eventos
     */
    public function enviar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_eventos();
        }
        else
        {
            /*
             *  Inclusão do Arquivo
             */
            $Folder   	  =    'assets/upload';
            $PathFiles    =    pathinfo($_FILES['str_Arquivo']['name']);
            $Arquivo      =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);

            /*
             *  Upload do Arquivo
             */			
            if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $Arquivo))
            {
                $Data['titulo']          =   trim($this->input->post('str_Titulo'));
                $Data['url']    	     =   sanitize_title_with_dashes($this->input->post('str_Url'));
                $Data['id_gateway']    	 =   $this->input->post('int_Gateway');
                $Data['tipo_evento']     =   1;
                $Data['validade']        =   data_br_to_us($this->input->post('Data_Limite'));
                $Data['valor']    	     =   num_to_db($this->input->post('str_Valor'));
                $Data['limite']    	     =   $this->input->post('int_Limite');
                $Data['adicionais']    	 =   $this->input->post('int_Adicionais');
                $Data['transporte']      =   num_to_db($this->input->post('str_Transporte'));
                $Data['header']    	     =   $Arquivo;
                $Data['criado_em'] 	     =   date('Y-m-d');
                $Data['status']    	     =   1;
                
               /*
                *  Efetua a persistência no banco de dados
                */	                
                $this->financeiro_model->set_eventos($Data);
                
                $Admin['ID_Admin']       =   $this->session->userdata('idUsuario');
                $Admin['ID_Evento']      =   $this->db->insert_id();
                
               /*
                *  Efetua a persistência no banco de dados
                */	                 
                $this->financeiro_model->set_admin_eventos($Admin);
                
                $this->data['usuario']   =   $this->session->userdata('strNome');
                $this->data['titulo']    =   $Data['titulo'];
                $this->data['url']       =   $Data['url'];
                
                $this->usable('sucesso');
            }
        }
    }

    /*
     *  Metodo que inclui um evento para o teatro
     */
    public function enviar_teatro()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_teatro();
        }
        else
        {
            /*
             *  Inclusão do Arquivo
             */
            $Folder   	  =    'assets/upload';
            $PathFiles    =    pathinfo($_FILES['str_Arquivo']['name']);
            $Arquivo      =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);

            /*
             *  Upload do Arquivo
             */			
            if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $Arquivo))
            {
                $Data['titulo']          =   trim($this->input->post('str_Titulo'));
                $Data['url']    	     =   sanitize_title_with_dashes($this->input->post('str_Url'));
                $Data['id_gateway']    	 =   $this->input->post('int_Gateway');
                $Data['tipo_evento']     =   2;
                $Data['validade']        =   data_br_to_us($this->input->post('Data_Limite'));
                $Data['valor']    	     =   0;
                $Data['limite']    	     =   0;
                $Data['adicionais']    	 =   0;
                $Data['header']    	     =   $Arquivo;
                $Data['criado_em'] 	     =   date('Y-m-d');
                $Data['status']    	     =   1;
                
               /*
                *  Efetua a persistência no banco de dados
                */	                
                $this->financeiro_model->set_eventos($Data);
                
                $Admin['ID_Admin']       =   $this->session->userdata('idUsuario');
                $Admin['ID_Evento']      =   $this->db->insert_id();
                
               /*
                *  Efetua a persistência no banco de dados
                */	                 
                $this->financeiro_model->set_admin_eventos($Admin);
                
                $this->data['usuario']   =   $this->session->userdata('strNome');
                $this->data['titulo']    =   $Data['titulo'];
                $this->data['url']       =   $Data['url'];
                
                $this->usable('sucesso');
            }
        }
    }

    /*
     *  Metodo que exibe a janela com os dados dos eventos
     */
    public function alterar($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/alterar'));
        $this->data['DataBody'] 	        =   $this->financeiro_model->get_eventos($id);
        $this->data['AddGateway']           =   $this->financeiro_model->get_gate_dropdown();    

        if($this->data['DataBody']->tipo_evento == 1)
        {
            $this->data['AddAdicionais']        =   array(0 => 'Selecione', 
                                                          1 => '+ 1 participante', 
                                                          2 => '+ 2 participantes',
                                                          3 => '+ 3 participantes',
                                                          4 => '+ 4 participantes',
                                                          5 => '+ 5 participantes',
                                                          6 => '+ 6 participantes',
                                                          7 => '+ 7 participantes',
                                                          8 => '+ 8 participantes',
                                                          9 => '+ 9 participantes',
                                                          10 => '+ 10 participantes');

            $this->usable('eventos/alterar_evento');
        }
        else
        {
            $this->usable('eventos/alterar_teatro');
        }
    }

    /*
     *  Metodo que salva a alteração de um evento
     */
    public function salvar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('ID'));	
        }
        else
        {
            /*
             *  Inclusão do Arquivo
             */
            $Folder    =   'assets/upload';

            /*
             *  Upload do Arquivo
             */
            if($_FILES['str_Arquivo'])
            {
                if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $_FILES['str_Arquivo']['name']))
                {			 
                    $PathFiles         =    pathinfo($_FILES['str_Arquivo']['name']);
                    $File_Name         =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);
                    
                    $Data['header']   =   $File_Name;
                }
            }

            $Data['id_eventos']    =   $this->input->post('ID');
            $Data['titulo']        =   trim($this->input->post('str_Titulo'));
            $Data['url']    	   =   sanitize_title_with_dashes($this->input->post('str_Url'));
            $Data['id_gateway']    =   $this->input->post('int_Gateway');
            $Data['validade']      =   data_br_to_us($this->input->post('Data_Limite'));
            $Data['valor']    	   =   num_to_db($this->input->post('str_Valor'));
            $Data['limite']        =   $this->input->post('int_Limite');
            $Data['adicionais']    =   $this->input->post('int_Adicionais');            
            $Data['transporte']    =   num_to_db($this->input->post('str_Transporte'));

            $this->financeiro_model->update_eventos($Data);

            redirect(base_url() . 'eventos', 'refresh');						
        }
    }

    /*
     *  Metodo que salva a alteração de um evento
     */
    public function salvar_teatro()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('ID'));	
        }
        else
        {
            /*
             *  Inclusão do Arquivo
             */
            $Folder    =   'assets/upload';

            /*
             *  Upload do Arquivo
             */
            if($_FILES['str_Arquivo'])
            {
                if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $_FILES['str_Arquivo']['name']))
                {			 
                    $PathFiles         =    pathinfo($_FILES['str_Arquivo']['name']);
                    $File_Name         =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);
                    
                    $Data['header']   =   $File_Name;
                }
            }

            $Data['id_eventos']    =   $this->input->post('ID');
            $Data['titulo']        =   trim($this->input->post('str_Titulo'));
            $Data['url']    	   =   sanitize_title_with_dashes($this->input->post('str_Url'));
            $Data['id_gateway']    =   $this->input->post('int_Gateway');
            $Data['validade']      =   data_br_to_us($this->input->post('Data_Limite'));

            $this->financeiro_model->update_eventos($Data);

            redirect(base_url() . 'eventos', 'refresh');						
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
        $this->financeiro_model->excluir($id);
    }

    /*
     *  Metodo que verifica se a URL é repetida
     */
    public function uniqueURL($URL)
    {
        if($this->financeiro_model->get_url($URL) == false)
        {
            $this->form_validation->set_message('uniqueURL', 'Já existe um evento <strong>ATIVO</strong> com o endereço <strong>'.mb_strtoupper($URL).'</strong>.');
            return false;	            
        }
        else 
        {
            return true;
        }
    }

    /*
     *  Metodo que verifica se CPF é valido
     */
    public function duplicidade_form()
    { 
        $Data['ID']   =  $this->input->get('evento');
        $Data['CPF']  =  str_replace('-', '', str_replace('.', '',$this->input->get('str_CPF')));
        
        if($this->eventos_model->check_duplicidade_evento($Data) == true)
        { 
            return true;
        } 
        else
        { 
            $this->form_validation->set_message('duplicidade_form', 'Já existe esse <strong>CPF</strong> cadastrado nesse evento</strong>.');
            return false;	
        } 
    }     
    
    /*
     *  Metodo que verifica se foi upado um arquivo 
     */
    public function handle_upload()
    {
        if(isset($_FILES['str_Arquivo']) && !empty($_FILES['str_Arquivo']['name']))
        {
            $ext['extensoes'] = array('jpg', 'jpeg', 'png');

            $Nome_Arquivo = mb_strtolower($_FILES['str_Arquivo']['name']);

            $extensao = explode('.', $Nome_Arquivo);
            $extensoes = end($extensao);

            if(array_search($extensoes, $ext['extensoes']) === false)
            {
                $this->form_validation->set_message('handle_upload', 'Você deve usar um arquivo de extensão <strong>JPG ou PNG</strong>.');
                return false;			
            }
            else
            {				
                return true;
            }
        }
        else
        {
            $this->form_validation->set_message('handle_upload', 'Você deve selecionar uma <strong>IMAGEM DE CABEÇALHO</strong>.');
            return false;
        }
    }

    /*
     *  Metodo que permite a alteração dos dados do inscrito
     */
    public function alterar_inscrito($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/inscritos'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataBody'] 	=    $this->financeiro_model->get_inscrito($id);
        
        //
        // Tipo de evento = 1 (Eventos) = 2 (Teatro)
        //
        if($this->data['DataBody']->event_type == 1)
        {
            $this->data['DataDependente']  =  $this->financeiro_model->get_dependentes($id);    
        }
        else
        {
            $this->data['DataCadeiras']  =  $this->financeiro_model->get_cadeiras($this->data['DataBody']->order_number);
        }

        $this->usable('eventos/inscrito');
    }

    //
    //  Chama o metodo do model para poderem alterar os dados do inscrito
    //
    public function salvar_inscrito()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_inscrito($this->input->post('ID_Inscrito'));
        }
        else
        {        
            //
            //  Iniciando o processo de exclusão e atualização
            // 
            $Red['id_eventos']     =   $this->input->post('ID');
            
            $Data['id_Inscrito']   =   $this->input->post('ID_Inscrito');
            $Data['str_Nome']      =   trim(mb_strtoupper($this->input->post('str_Nome')));
            $Data['str_CPF']       =   trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
            $Data['str_Email']     =   trim(mb_strtolower($this->input->post('str_Email')));
            $Data['dec_Valor']     =   num_to_db($this->input->post('str_Valor'));
            
            $Cielo['id_inscrito']       =   $this->input->post('ID_Inscrito');
            $Cielo['customer_email']    =   trim(mb_strtolower($this->input->post('str_Email')));
            $Cielo['valor']             =   num_to_db($this->input->post('str_Valor'));  
            
            //
            //  Salvando as informações do Inscrito
            //            
            $this->financeiro_model->update_inscrito($Data);

            //
            //  Salvando as informações do registro de pagamento do Inscrito
            //            
            $this->financeiro_model->update_pagamento($Cielo);

            //
            //  Redirecionamento
            //
            redirect(base_url() . 'eventos/visualizar/' . $Red['id_eventos']);
        }
    }    

    /*
     *  Chama o metodo do model para "exclusão" do registro
     */
    public function excluir_inscrito($id)
    {
        //
        //  Iniciando o processo de exclusão e atualização
        // 
        $this->financeiro_model->excluir_geral($id);
    }

    //
    //  Metodo que exibe a janela com os dados da estudantes
    //
    public function alterar_dependente($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('eventos/dependentes'));
        
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $this->data['DataBody'] 	=    $this->financeiro_model->get_dependentes_by_id($id);

        $this->usable('eventos/alterar_inscrito');
    }  
    
    //
    //  Metodo que exibe a janela com os dados da estudantes
    //    
    public function salvar_dependente()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar_dependente($this->input->post('ID_Dependente'));
        }
        else
        {         
            $Data['id_Dependente']  =   $this->input->post('ID_Dependente');
            $Data['str_Nome']       =   trim(mb_strtoupper($this->input->post('str_Nome')));
            $Data['str_CPF']        =   trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
            
            //
            //  Salvando as informações do Inscrito
            //            
            $this->financeiro_model->update_dependente($Data);

            redirect(base_url() . 'eventos/alterar_inscrito/' . $this->input->post('ID_Inscrito'));
        }
    }

    /*
     *  Chama o metodo do model para "exclusão" do registro
     */
    public function excluir_dependentes($id)
    {
       /*
        *  Iniciando o processo de exclusão e atualização
        */        
        $this->financeiro_model->excluir_dependente($id, $valor);
    }

    /*
     *  Chama o metodo para exibir os dependentes depois da ação de exclusão
     */
    public function exibir_dependentes($id)
    {
        //
        // Busca no banco de dados as informações dos inscritos
        // 
        $DataBody 	      =   $this->financeiro_model->get_inscrito($id);
        $DataDependente   =   $this->financeiro_model->get_dependentes($id);
       
        //
        //  Iniciando o processo de atualização do pagamento
        //
        $valor['valor']  =  ($DataBody->valor - $DataBody->valor_evento);      
        
        $this->financeiro_model->atualizar_pagamento($id, $valor);

        $_Table = '';

        if($DataDependente == 0)
        {
            echo '<h3><strong>Não possui dependentes</strong></h3>';
        }
        else
        {
            $Action = '';
            $Action = ($DataBody->payment_status <> 2)? '<th width="60">Ação</th>' : '';
            
            $_Table .= '<table border="0" class="opa">
                        <tr>
                            '.$Action.'
                            <th width="420">Nome</th>
                            <th width="120">CPF</th>
                        </tr> ';
            
            foreach($DataDependente as $sDependente):

                $_Table .= '<tr>';

                if($DataBody->payment_status <> 2)
                {
                    $_Table .= '<td style="padding-top: 9px; padding-left: 10px">
                    
                                    <a id="ExcluirDependente" href="javascript:Delete('.$sDependente->id_Dependente.')" title="Excluir"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> 
                    
                                </td>';
                }

                $_Table .= '<td style="padding-botton: 5px">'.$sDependente->str_Nome.'</td>
                            <td style="padding-botton: 5px">'.formatCPF_CNPJ($sDependente->str_CPF).'</td>
                        </tr>';   

            endforeach;

            echo $_Table . '</table>';
        }        
    }

    //
    // Chama o metodo para incluir o participante que vai pagar no balcão
    //    
    public function incluir_participante($id_evento)
    {
        //
        // Mostrar os dados do evento para incluir no form
        //
        $this->data['AddBody']    =    $this->eventos_model->get_dados_evento($id_evento);

        //
        // Mostrar os dados do evento para incluir no form
        //        
        if($this->data['AddBody']->tipo_evento == 1)
        {
            $Numero_Inscritos     =    $this->financeiro_model->get_count_inscritos_by_evento($id_evento);
            $Numero_Maximo        =    $this->data['AddBody']->limite;

            if($Numero_Inscritos >= $Numero_Maximo)
            {
                $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
                
                $this->usable_form('pagamentos/encerrado');            
            }
            else
            {
                $this->data['AddJavascripts']    =    load_js(array('form/form_eventos'));
                
                $this->usable_form('pagamentos/form_eventos');                   
            }
        }
        else
        {
            $this->data['AddJavascripts']    =    load_js(array('form/form_teatro'));
            $this->data['AddCadeiras']       =    $this->eventos_model->get_chairs_available($this->data['AddBody']->id_eventos);  

            $this->usable_form('pagamentos/form_teatro'); 
        }
    }

    /*
     *  Metodo que adiciona participantes de acordo com o proposto no admin
     */       
    public function enviar_participante_evento()
    {
        $CPF  =  trim($this->input->post('str_CPF'));
        
        /*
         *  Tabela de Inscritos
         */
        $Data['id_Inscrito']           =    $this->eventos_model->get_id_inscricao();
        $Data['id_Evento'] 		       =    $this->input->post('id_eventos');
        $Data['str_Nome']        	   =    trim(mb_strtoupper($this->input->post('str_Nome')));
        $Data['str_CPF']               =    trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
        $Data['str_Email']    	       =    trim(mb_strtolower($this->input->post('str_Email')));
        $Data['str_Telefone']    	   =    trim(mb_strtolower($this->input->post('str_Telefone')));
        $Data['str_Celular']    	   =    trim(mb_strtolower($this->input->post('str_Celular')));
        $Data['str_Cidade']    	       =    trim(mb_strtoupper($this->input->post('str_Cidade')));
        $Data['str_UF']    	   	       =    $this->input->post('id_UF');
        $Data['int_Proprio']    	   =    $this->input->post('sel_Participante');
        $Data['dec_Valor']             =    $this->input->post('ValorTotal');
        $Data['int_Transporte']    	   =    $this->input->post('int_Transporte');
        $Data['int_Ingressos']    	   =    ($Data['int_Proprio'] == 1)? $this->input->post('sel_Adicionais') + 1 : $this->input->post('sel_Adicionais');
        $Data['str_Usuario']    	   =    $this->session->userdata('strLogin');
        $Data['dt_Cadastro'] 	       =    date('Y-m-d');	

        //
        // Verifica se o cara selecionou transporte ou não
        //
        if($this->input->post('int_Transporte') == 1)
        {
            $Data['str_RG']                =    trim($this->input->post('RG_Transporte'));
            $Data['dt_Nascimento']         =    data_br_to_us($this->input->post('Data_Nascimento'));
        }         
        
        //
        //  Bota para dentro o Inscrito
        //
        $this->eventos_model->insert_inscrito($Data);
            
        //
        //  Tabela de Cartões
        //            
        $Cartao['id_inscrito']           =    $Data['id_Inscrito'];
        $Cartao['id_evento'] 		     =    $this->input->post('id_eventos');
        $Cartao['event_type']            =    1;
        $Cartao['valor']                 =    $this->input->post('ValorTotal');
        $Cartao['order_number']          =    $this->eventos_model->create_order_number();
        $Cartao['customer_name']         =    $Data['str_Nome'];
        $Cartao['customer_identity']     =    $Data['str_CPF'];
        $Cartao['customer_email']        =    $Data['str_Email'];
        $Cartao['shipping_type']         =    5;
        $Cartao['payment_method_type']   =    $this->input->post('sel_Pagamento');
        $Cartao['payment_status']        =    2;
        $Cartao['payment_date']          =    date('Y-m-d H:i:s');

        //
        //  Bota para dentro o Pagamento em Cartão
        // 
        $this->eventos_model->insert_cartao($Cartao); 
        
        //
        //  Tabela de Presença
        //            
        $Presenca['id_evento']         =    $Data['id_Evento'];
        $Presenca['numero_pedido']     =    $Cartao['order_number'];
        $Presenca['str_nome']          =    $Data['str_Nome'];
        $Presenca['str_cpf']           =    $Data['str_CPF'];

        if($Data['int_Proprio'] == 1) 
        {
            $this->eventos_model->insert_presenca($Presenca);
        }          

        //
        //  Quantidade de Adicionais
        //  E checa se é maior que zero por precaução
        // 
        $Quantidade  =  $this->input->post('sel_Adicionais');

        if($Quantidade > 0)
        {
            for($x = 1; $x <= $Quantidade; $x++)
            {
                $AdicionalData['id_Dependente']   =   $this->eventos_model->get_id_dependente();
                $AdicionalData['id_Inscrito']     =   $Data['id_Inscrito'];
                $AdicionalData['id_Evento'] 	  =   $this->input->post('id_eventos');
                
                //
                // Recebe post
                //
                $v_Nome = $this->input->post('Nome_Adicional');
                
                //
                // Chama os arrays
                //
                $AdicionalData['str_Nome'] = mb_strtoupper($v_Nome[$x]);
                
                //
                // Recebe post
                //
                $v_CPF = $this->input->post('CPF_Adicional');
                
                //
                // Chama os arrays
                //
                $AdicionalData['str_CPF'] = str_replace('-', '', str_replace('.', '', $v_CPF[$x]));
                
                //
                //  Se tiver transporte vamos incluir na presença
                //                    
                if($Data['int_Transporte'] == 1)
                {
                    $v_Numero_RG    =   $this->input->post('RG_Transporte_Adicional');
                    $v_Nascimento   =   $this->input->post('Data_Nascimento_Adicional');
                    $v_Telefone     =   $this->input->post('Telefone_Adicional');
                    
                    $AdicionalData['str_RG']           =    trim($v_Numero_RG[$x]);
                    $AdicionalData['dt_Nascimento']    =    data_br_to_us($v_Nascimento[$x]);
                    $AdicionalData['str_Telefone']     =    trim($v_Telefone[$x]);
                }                
                
                //
                //  Bota para dentro o Adicional
                //                    
                $this->eventos_model->insert_adicional($AdicionalData);

                //
                //  Inclui o nome do cara que vai estar presente ou não no evento
                //
                $PresencaAdicional['id_evento']       =    $Data['id_Evento'];
                $PresencaAdicional['numero_pedido']   =    $Cartao['order_number'];
                $PresencaAdicional['str_nome']        =    $AdicionalData['str_Nome'];
                $PresencaAdicional['str_cpf']         =    $AdicionalData['str_CPF'];                
                
                $this->eventos_model->insert_presenca($PresencaAdicional);
            }
        } 
        
        //
        // Metodo que dispara o e-mail de confirmação
        //              
        $this->status_money($Data['id_Inscrito']);
        
        redirect(base_url() . 'eventos/sucesso/' . $this->input->post('id_eventos') . '/' . $Data['id_Inscrito']);
    }

    //
    // Metodo que adiciona participantes de acordo com o proposto no admin
    //    
    public function show($Personas, $Transporte)
    {
        $_table = '';
        
        if($Personas == 0)
        {
            echo $_table .= '<table cellpadding="0" cellspacing="0">
                             <tr>
                                 <td></td>
                             </tr>
                             </table>';
        }
        else
        {
            $_table .= '<table id="Atbl_dpv1" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody id="Atbd_dpv1">';

            for($x = 1; $x <= $Personas; $x++)
            {
                $_table .= '<tr>
                                <td>
                                     <table width="100%" border="0" cellpadding="3" cellspacing="2">
                                     <tr>
                                         <td style="padding-left:9px">Nome Completo</td>
                                     </tr>
                                     <tr>
                                        <td style="padding-left:9px; padding-top: 5px"><input name="Nome_Adicional['.$x.']" type="text" value="" size="50" maxlength="64" class="nome required"></td>
                                     </tr>
                                     <tr><td height="20"></td></tr>
                                     <tr>
                                         <td style="padding-left:9px">CPF</td>
                                     </tr>
                                     <tr>
                                        <td style="padding-left:9px; padding-top: 5px"><input name="CPF_Adicional['.$x.']" type="text" size="12" value="" maxlength="14" class="cpf_adicional required" unique="currency"></td>
                                     </tr>';
                                     
                                     if($Transporte == 1)
                                     {
                                         $_table .= '<tr>
                                                         <td>
                                                             <table width="100%" border="0">
                                                             <tr>
                                                                <td height="20"></td>
                                                             </tr>                                                             
                                                             <tr>
                                                                 <td style="padding-left:9px">RG</td>
                                                             </tr>
                                                             <tr>
                                                                 <td style="padding-left:6px; padding-top: 5px"><input name="RG_Transporte_Adicional['.$x.']" type="text" value="" size="10" maxlength="20" class="rg required"></td>
                                                             </tr>
                                                             <tr><td height="20"></td></tr>                           
                                                             <tr>
                                                                 <td style="padding-left:9px">Nascimento</td>
                                                             </tr>
                                                             <tr>
                                                                 <td style="padding-left:6px; padding-top: 5px"><input name="Data_Nascimento_Adicional['.$x.']" type="text" size="8" value="" maxlength="10" class="nascimento required"></td>
                                                             </tr>
                                                             <tr><td height="20"></td></tr>                           
                                                             <tr>
                                                                 <td style="padding-left:9px">Telefone</td>
                                                             </tr>
                                                             <tr>
                                                                 <td style="padding-left:6px; padding-top: 5px"><input name="Telefone_Adicional['.$x.']" type="text" size="12" value="" class="telefone required"></td>
                                                             </tr>                                                            
                                                             </table>                                                        
                                                         </td>
                                                     </tr>';
                                     }

                                     $_table .= '<tr>
                                         <td colspan="2" height="25" style="padding-top: 20px"><div id="Line"></div></td>
                                     </tr>
                                     </table>
                                 </td>
                             </tr>';
            }  
            
            echo $_table .= '</tbody></table>';
        }
    }

    //
    // Metodo que adiciona os campos de RG e Data de Nascimento
    //    
    public function show_transport()
    {
        $_table = '';
        
        $_table .= '<table id="TransportContent" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td>
                            <table width="100%" border="0" cellpadding="3" cellspacing="2">
                            <tr>
                                <td height="20"></td>
                            </tr>                            
                            <tr>
                                <td style="padding-left:6px">RG</td>
                            </tr>
                            <tr>
                                <td style="padding-left:6px; padding-top: 5px"><input name="RG_Transporte" type="text" value="" size="10" maxlength="20" class="rg required"></td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>                           
                            <tr>
                                <td style="padding-left:6px">Nascimento</td>
                            </tr>
                            <tr>
                                <td style="padding-left:6px; padding-top: 5px"><input name="Data_Nascimento" type="text" size="8" value="" maxlength="10" class="nascimento required"></td>
                            </tr>
                            <tr>
                                <td height="10"></td>
                            </tr>                                
                            </table>
                        </td>
                    </tr>
                    </tbody>
                    </table>';
        
        echo $_table;
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
    //  Metodo que verifica se CPF é valido
    //
    public function duplicidade()
    { 
        $Data['ID']     =   $this->input->get('evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '',$this->input->get('cpf')));
        
        if($this->eventos_model->check_duplicidade_evento($Data) == true)
        { 
            echo 'true'; 
        } 
        else
        { 
            echo 'false';
        } 
    }

    //
    //  Metodo que verifica se CPF é valido
    //
    public function duplicidade_adicionais()
    { 
        $Data['ID']     =   $this->input->get('evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '',$this->input->get('cpf')));
        
        if($this->eventos_model->check_duplicidade_adicionais_evento($Data) == true)
        { 
            echo 'true'; 
        } 
        else
        { 
            echo 'false';
        } 
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
    
    //
    // Metodo que exibe uma mensagem de sucesso ao fim do cadastro do participante
    //    
    public function sucesso($id_evento, $id_participante)
    {
        $this->data['AddCss']            =    load_css(array('BreadCrumb', 'form/style'));
        $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
        
        $this->data['AddBody']           =    $this->eventos_model->get_dados_evento($id_evento);
        $this->data['AddUser']           =    $this->eventos_model->get_dados_inscrito($id_participante);

        $this->usable_form('pagamentos/sucesso_participante');   
    }

    //
    // Metodo que retorna os dados para a tabela de status
    //
    public function status_money($Inscrito)
    {
        $this->load->library('email');

        //
        // Busca os dados  no banco de dados
        //
        $Data = $this->financeiro_model->get_cartao_by_id($Inscrito);
        
        if($Data->payment_status == 0)
        {
            header("HTTP/1.0 404 Not Found");
        }

        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $array_Evento  =  $this->financeiro_model->get_id_dados_general($Data->order_number);

        //
        // Busco os dados para ir para o e-mail
        //        
        $Card  =  $this->financeiro_model->get_dados_card($Data->order_number);
        
        //
        // Variáveis de suporte
        //
        $evento    =  $array_Evento->titulo;
        $alerta    =  $array_Evento->alerta;
        $presenca  =  $array_Evento->presenca;
        $status    =  '';
        $mensagem  =  '';

        //
        // Pega o status da compra
        //
        $status  =  paymentStatus($Data->payment_status, 'Status');

        //
        // Tipo de pagamento
        //
        $Legenda = '';

        if($Data->payment_method_type == 1)
        {
            $Legenda = 'CARTÃO DE CRÉDITO';
        }

        if($Data->payment_method_type == 4)
        {
            $Legenda = 'CARTÃO DE DÉBITO';   
        }

        if($Data->payment_method_type == 3)
        {
            $Legenda = 'DINHEIRO';    
        }        

        $config['protocol']         =   'smtp';
        $config['smtp_host']        =   'ssl://smtp.gmail.com';
        $config['smtp_port']        =   465;
        $config['smtp_user']        =   'pagamentosboladeneve@gmail.com';
        $config['smtp_pass']        =   'pag432@1';
        $config['validate']         =   TRUE;
        $config['newline']          =   "\r\n";
        $config['charset']          =   'utf-8';
        $config['mailtype']         =   'html';				

        $this->email->initialize($config);

        $mail_conteudo   = 	  '<style type="text/css">
                                    .lin:link {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:active {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:visited {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                    .lin:hover {text-decoration: underline; font-weight: bold; color: #000000; font-size: 11px}
                               </style>';		

        $mail_conteudo   .=   '<table width="100%" border="0" cellpadding="10" cellspacing="10" style="font-family: Arial, Verdana, Tahoma, sans-serif, serif; font-size:15px;background-color: #fff; color:#000;">
                               <tr><td style="padding-left: 3px"><img src="'.base_url().'assets/images/wmb_logo.jpg" width="100" /><h2>EVENTOS DO BOLA DE NEVE</h2></td></tr>
                               <tr><td style="padding-left: 3px"><h3>Comprovante de Pagamento</h3></td></tr>
                               <tr><td style="padding-left: 1px">';

        $mail_conteudo   .=   '<p>Ol&aacute; <strong>'.$Card->customer_name.'</strong>, voc&ecirc; acabou de efetuar a inscrição para o evento <strong>'.$evento.'</strong>.</p>'.$mensagem.'<p>O status atual do seu pedido é: <strong>'.$status.'</strong></p><br />';
                            
        if($alerta != NULL)
        {
            $mail_conteudo   .=    '<p><strong>Informações importantes:</strong></p>
                                    <p>Indispensável a apresentação de documento com foto e número do CPF do titular da inscrição para realização de check-in e retirada da Pulseira.<br /></p>

                                    <p>Se você optou por Transporte, atenção o ônibus sairá da Rua Clélia 1517, no dia 15/12/2018 às 04h30.</p>
                                    <p>Retornará para o mesmo endereço ao término do evento.</p>
                                    <p>O embarque somente será possível com a apresentação de documento com foto.</p><br />

                                    <p><strong>Programação:</strong></p>

                                    <p>10/12 - 20hs - Rua Clélia, 1517</p>
                                    <p>11/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>12/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>13/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>14/12 - 14hs/20hs - Rua Clélia, 1517</p>
                                    <p>15/12 - 09hs/15hs/19hs – Estancia Arvore da Vida – Sumaré - SP</p>
                                    <p>16/12 - 10hs/16hs/20hs - Rua Clélia 1517</p><br />'; 
        }
                            
        $mail_conteudo   .=    '<p><strong>Segue abaixo os seus dados:</strong></p>
                                <p>Numero do Pedido:&nbsp;'.$Card->order_number.'</p>
                                <p>Nome Completo:&nbsp;'.$Card->customer_name.'</p>
                                <p>CPF:&nbsp;'.formatCPF_CNPJ($Card->customer_identity).'</p>
                                <p>E-mail:&nbsp;'.$Card->customer_email.'</p>
                                <p>Tipo de Pagamento:&nbsp;'.$Legenda.'</p>';                                

        if($Card->payment_method_type <> 3)
        {
            $mask = ($Card->payment_maskedcreditcard == '')? 'Aprovado' : $Card->payment_maskedcreditcard;
            
            $mail_conteudo   .=    '<p>Status:&nbsp;'.$mask.'</p>';                                
        }

        if($array_Evento->tipo_evento == 1)
        {
            $mail_conteudo   .=   '<p>Valor Total:&nbsp;R$&nbsp;'.num_to_user($Card->valor).'</p>';
        }
        
        $mail_conteudo   .=   '<p>Data da compra:&nbsp;'.date("d/m/Y - H:i:s").'</p>';

        //
        // Mensagem do COVID 19
        //
        $mail_conteudo   .=   '<p><strong>Atesto para os devidos fins que não apresento qualquer sintoma de tosse persistente, falta de ar, desconforto respiratório e gripe/resfriado, dores pelo corpo ou qualquer outro desconforto. Da mesma forma, declaro que não sou pertencente a nenhum grupo de risco, tais como, pessoa acima de 60 (sessenta) anos, hipertenso, diabético, gestante e imunodeprimido.</strong></p>';

        //
        // Emissão do QR Code do Comprador
        //        
        if($Card->payment_status == 2 && $presenca == 1)
        {
            //
            // Codigo do Evento
            //
            $Codigo = $Card->customer_identity . '&' . $array_Evento->id_Evento . '&' . $Card->payment_status;

            $this->QrCode($Codigo);

            $mail_conteudo   .=   '<b>QR Code para validar sua presença!</b>';
            $mail_conteudo   .=   '<br /><br /><img src="https://boladeneve.com/eventos/assets/qrcodes/'.$Codigo.'.png" width="200">';
        }

        //
        // Verifica se existe ou não acompanhantes
        //
        $acompanhantes  =  $this->financeiro_model->get_acompanhantes($Card->order_number);
        
        if($acompanhantes > 0)
        {
            $mail_conteudo   .=   '<p>&nbsp;</p><h3 style="font-size:16px"><strong>Segue abaixo os dados dos acompanhantes:</strong></h3><br />';

            foreach($acompanhantes as $sAcompanhantes):
                
                $mail_conteudo   .=   '<p><strong>Nome do Acompanhante:</strong>&nbsp;'.$sAcompanhantes->str_Nome.'</p>';
                
                if($sAcompanhantes->str_CPF != '99999999999')
                {
                    $mail_conteudo   .=   '<p><strong>CPF:</strong>&nbsp;'.formatCPF_CNPJ($sAcompanhantes->str_CPF).'</p>';
                    
                    //
                    // Emissão do QR Code
                    //        
                    if($Card->payment_status == 2)
                    {
                        //
                        // Codigo do Evento
                        //
                        $Codigo = $sAcompanhantes->str_CPF . '&' . $array_Evento->id_Evento . '&' . $Card->payment_status;

                        $this->QrCode($Codigo);

                        $mail_conteudo   .=   '<b>QR Code para validar sua presença!</b>';
                        $mail_conteudo   .=   '<br /><br /><img src="https://boladeneve.com/eventos/assets/qrcodes/'.$Codigo.'.png" width="200">';
                    }                    
                }

            endforeach;
        }
        
        //
        // Footer do e-mail
        //
        $mail_conteudo   .=   '<br /><br /><b>E-mail enviado em ' . date("d/m/Y - H:i:s") . '.</b>';
        $mail_conteudo   .=   '</td></tr></table>';

        $this->email->from('<pagamentos@boladeneve.com>', 'Bola de Neve Eventos');
        $this->email->to($Card->customer_email);

        $this->email->subject('Inscrição para o Bola de Neve Eventos - ' . $status);
        $this->email->message($mail_conteudo);

        $rc = $this->email->send();       
    }

    //
    // Metodo que gera e salva o QR Code no e-mail
    //    
    public function QrCode($Codigo)
    {
        //
        // Incluir Composer
        //
        include './vendor/autoload.php';

        //
        // Configurações do QRCode
        //
        $options = new QROptions([
            'version'    => 5,
            'eccLevel'   => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG, 
            'imageBase64' => false 
        ]);

        //
        // invoca uma nova instância QRCode
        //
        $qrcode = new QRCode($options);
        
        //
        // Gerar a imagem e salvar a imagem do QR no servidor
        //
        $qrcode->render($Codigo, '../assets/qrcodes/' . $Codigo . '.png');
    }    
        
    //
    // Metodo que atualiza os valores dos ingressos
    //    
    public function calcular_cadeiras()
    {
        $Valor_Base   =  $this->input->get('valor');
        
        $Valor_Total = $Valor_Base;	

        echo $_return = '<font style="font-size:20px"><strong>R$ '.number_format($Valor_Total, 2, ',', '.').'</strong></font>
                         <input id="ValorTotal" name="ValorTotal" type="hidden" value="'.$Valor_Total.'" /><br />Valor total a ser pago.';
    }     
    
    /*
     *  Metodo que mostra cadeiras de acordo com a escolha
     */    
    public function show_chairs()
    {
        $body = '';

        $Valor = 0;
        
        if(empty($this->session->userdata('cart')))
        {
            echo '<table cellpadding="0" cellspacing="0">
                  <tr>
                       <td style="padding-left:15px"><font style="font-size:14px"><strong>Não existe nenhuma cadeira incluída.</strong></font></td>
                  </tr>
                  </table>';
                
            echo '<input id="valor" name="valor" type="hidden" value="0"><br />';
        }
        else
        {
            $body .= '<table class="table table-striped">
            <thead>
            <tr>
                <th width="100" height="25">Ação</th>    
                <th width="100" height="25">Setor</th>
                <th width="100" height="25">Cadeira</th>
                <th width="100" height="25">Valor</th>
            </tr>
            </thead>
            <tbody>';

            foreach ($this->session->userdata('cart') as $ino => $sAddGrid):

                $Excluir  =  '<a href="javascript:Excluir('.$ino.')" title="Excluir"><img src="'.base_url().'assets/images/ico-lixeira.gif" width="17" height="17"></a>';

                $body .= '<tr>
                                <td>'.$Excluir.'</td>    
                                <td>'.$sAddGrid->str_setor.'</td>
                                <td>'.$sAddGrid->str_cadeira.'</td>        
                                <td>R$ '.num_to_user($sAddGrid->valor_cadeira).'</td>    
                            </tr>';

                $Valor += $sAddGrid->valor_cadeira;   

            endforeach;            

            echo $body .= '</tbody></table>';

            echo '<input id="valor" name="valor" type="hidden" value='.$Valor.'><br />';
        }

        echo form_error('valor', '<span class="field-validation-valid validation" data-valmsg-for="valor" data-valmsg-replace="true">', '</span>');
    } 

    /*
     *  Metodo que adiciona cadeiras de acordo com a escolha
     */      
    public function add_chairs($Id_Chair)
    {
        $body = '';
        
        //
        // Busca no banco as cadeiras pelo ID
        //
        $AddGrid  =  $this->eventos_model->listar_chair_by_id($Id_Chair);        
        
        //
        // Verifica se já existe a session
        //
        if(empty(array_filter($this->session->userdata('cart'))))
        {
            $this->session->set_userdata('cart', array());
        }

        if(!in_array($AddGrid, $this->session->userdata('cart'))) 
        {
            //
            // Inclui um item no carrinho
            //
            $old_que_ans_session =  $this->session->userdata('cart');
            
            array_push($old_que_ans_session, $AddGrid);
            
            $this->session->set_userdata('cart', $old_que_ans_session);            
        }
    } 
    
    /*
     *  Lista as familias de acordo com a estudantes logada
     */	
    public function excluir_chair($id)
    {
        //
        // Remove the item from the cart
        //
        $cart = $this->session->userdata('cart');
        
        unset($cart[$id]);

        $this->session->set_userdata('cart', $cart);
    } 
    
    /*
     *  Metodo que verifica se CPF é duplicado e permite nova tentativa de preenchimento
     */
    public function duplicidade_retype()
    { 
        $Data['ID']     =   $this->input->get('evento');
        $Data['CPF']    =   str_replace('-', '', str_replace('.', '',$this->input->get('cpf')));
        
        if($this->eventos_model->payment_finder_teatro($Data) == false)
        { 
            echo 'false'; 
        } 
        else
        { 
            //
            // Limpar o carrinho de compras do usuário que selecionou e não utilizou
            //
            $this->eventos_model->limpa_cart($Data);

            echo 'true';        
        } 
    }
    
    /*
     *  Metodo que adiciona participantes de acordo com o proposto no admin
     */       
    public function enviar_participante_teatro()
    {
        $CPF  =  trim($this->input->post('str_CPF'));
        
        /*
         *  Tabela de Inscritos
         */
        $Data['id_Evento'] 		       =    $this->input->post('id_eventos');
        $Data['str_CPF']               =    trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
        $Data['id_Inscrito']           =    $this->eventos_model->check_return_id($Data);
        $Data['str_Nome']        	   =    trim(mb_strtoupper($this->input->post('str_Nome')));

        if($Data['id_Inscrito'] == 0)
        {
            $Data['id_Inscrito']       =    $this->eventos_model->get_id_inscricao();
        }

        $Data['str_Email']    	       =    trim(mb_strtolower($this->input->post('str_Email')));
        $Data['str_Telefone']    	   =    trim(mb_strtolower($this->input->post('str_Telefone')));
        $Data['str_Celular']    	   =    trim(mb_strtolower($this->input->post('str_Celular')));
        $Data['str_Cidade']    	       =    trim(mb_strtoupper($this->input->post('str_Cidade')));
        $Data['str_UF']    	   	       =    $this->input->post('id_UF');
        $Data['dec_Valor']             =    $this->input->post('ValorTotal');
        $Data['str_Usuario']    	   =    $this->session->userdata('strLogin');
        $Data['dt_Cadastro'] 	       =    date('Y-m-d');	
        
        /*
         *  Bota para dentro o Inscrito
         */
        $this->eventos_model->insert_inscrito($Data);

        /*
         *  Tabela de Cartões
         */            
        $Cartao['id_inscrito']           =    $Data['id_Inscrito'];
        $Cartao['id_evento'] 		     =    $this->input->post('id_eventos');
        $Cartao['event_type']            =    2;
        $Cartao['valor']                 =    $this->input->post('ValorTotal');
        $Cartao['order_number']          =    $this->eventos_model->create_order_number();
        $Cartao['customer_name']         =    $Data['str_Nome'];
        $Cartao['customer_identity']     =    $Data['str_CPF'];
        $Cartao['customer_email']        =    $Data['str_Email'];
        $Cartao['shipping_type']         =    5;
        $Cartao['payment_method_type']   =    $this->input->post('sel_Pagamento');
        $Cartao['payment_status']        =    2;
        $Cartao['payment_date']          =    date('Y-m-d H:i:s');        

        /*
         *  Bota para dentro o Pagamento em Cartão
         */
        $this->eventos_model->insert_cartao($Cartao);            

        /*
         *  Inclui no banco a intensão de compra do usuário
         *  Isso não garante a propriedade da cadeira
         */ 
        foreach($this->session->userdata('cart') as $ino => $sAddGrid):

            $Cart['id_evento']       =     $Data['id_Evento'];
            $Cart['id_inscrito']     =     $Data['id_Inscrito'];
            $Cart['id_cadeira']      =     $sAddGrid->id_cadeira;
            $Cart['str_setor']       =     $sAddGrid->str_setor;
            $Cart['str_cadeira']     =     $sAddGrid->str_cadeira;
            $Cart['valor_cadeira']   =     $sAddGrid->valor_cadeira;
            $Cart['pago']            =     1;

            $this->eventos_model->insert_cadeira($Cart);

        endforeach;

        //
        // Destroi a sessão pois não mais ela interessa
        //
        $this->session->unset_userdata('cart');
            
        //
        // Metodo que dispara o e-mail de confirmação
        //                      
        $this->status_money($Data['id_Inscrito']);

        //
        // Redirect para dizer que deu tudo certo
        //                      
        redirect(base_url() . 'eventos/sucesso/' . $this->input->post('id_eventos') . '/' . $Data['id_Inscrito']);
    }     
    
    /*
     *  Metodo que finaliza o pagamento
     */
    public function pdf_recibo()
    {    
        $this->load->helper('path');
        $this->load->library('fpdf');

        // Posição vertical inicial
        $this->fpdf->SetY('-1');

        // Texto do título
        $titulo = utf8_decode('Recibo de Pagamento de Evento');

        // Variáveis
        $id_Inscrito  =  $this->input->post('id_Inscrito');

        // Carega os dados das alunos e suas notinhas
        $Dados  =  $this->eventos_model->get_dados_inscrito($id_Inscrito);
        
        if(empty($Dados))
        {
            $this->fpdf->AddPage();
            $this->fpdf->SetTitle($titulo);
            $this->fpdf->Cabecalho($titulo);
            $this->fpdf->ln(10);
            $this->fpdf->Cell(190, 10, 'Nenhum registro foi encontrado.', 1, 0, 'C');                
        }
        else
        {
            $this->fpdf->AddPage();
            $this->fpdf->SetTitle($titulo);
            $this->fpdf->Cabecalho($titulo);

            $this->fpdf->ln(10);            

            //
            // Tipo de pagamento
            //
            $Legenda = '';

            if($Dados->payment_method_type == 1)
            {
                $Legenda = utf8_decode('CARTÃO DE CRÉDITO');
            }

            if($Dados->payment_method_type == 4)
            {
                $Legenda = utf8_decode('CARTÃO DE DÉBITO');   
            }

            if($Dados->payment_method_type == 3)
            {
                $Legenda = 'DINHEIRO';    
            }
            
            // Numero de linhas para quebra de página
            $NumLinhas       =  0;
            $Total_Cartao    =  $Dados->valor;

            $this->fpdf->ln(8);

            $this->fpdf->SetFont('Arial', 'B', 10);
            $this->fpdf->Cell(50, 6, 'NUMERO PEDIDO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, $Dados->order_number, 0, 0, 'L');
            $this->fpdf->ln(8);            
            $this->fpdf->Cell(50, 6, 'INSCRITO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, $Dados->str_Nome, 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'TOTAL PAGO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, 'R$ ' . num_to_user($Total_Cartao), 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'FORMA DE PAGAMENTO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, $Legenda, 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'DATA DA COMPRA:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, data_us_to_br($Dados->payment_date), 0, 0, 'L'); 

            if($Dados->event_type == 1)
            {
                //
                // Lista os acompanhantes
                //
                $acompanhantes  =  $this->financeiro_model->get_acompanhantes($Dados->order_number);
                
                if($acompanhantes > 0)
                {
                    $this->fpdf->ln(20);
                    
                    $this->fpdf->Cell(50, 6, 'DADOS DO ACOMPANHANTE:', 0, 0, 'L');
                    $this->fpdf->ln(8);
                    
                    foreach($acompanhantes as $sAcompanhantes):
                        
                        $this->fpdf->Cell(50, 6, 'ACOMPANHANTE:', 0, 0, 'L');
                        $this->fpdf->Cell(70, 6, $sAcompanhantes->str_Nome, 0, 0, 'L');
                        $this->fpdf->ln(8);
    
                        if($sAcompanhantes->str_CPF != '99999999999')
                        {
                            $this->fpdf->Cell(50, 6, 'CPF:', 0, 0, 'L');
                            $this->fpdf->Cell(70, 6, formatCPF_CNPJ($sAcompanhantes->str_CPF), 0, 0, 'L');
                            $this->fpdf->ln(8);                    
                        }        
        
                    endforeach;
                }
            }
            else
            {
                //
                //  Pega dados para ir para o e-mail
                //            
                $cadeiras  =  $this->financeiro_model->get_cadeiras($Dados->order_number);

                $Value['valor'] = 0;

                $this->fpdf->ln(20);
                    
                $this->fpdf->Cell(50, 6, 'DADOS DAS CADEIRAS:', 0, 0, 'L');
                $this->fpdf->ln(8);                

                foreach($cadeiras as $sCadeiras):

                    $this->fpdf->Cell(50, 6, 'SETOR / CADEIRA / VALOR:', 0, 0, 'L');
                    $this->fpdf->Cell(70, 6, $sCadeiras->str_setor.' / '.$sCadeiras->str_cadeira.' / R$ '.num_to_user($sCadeiras->valor_cadeira), 0, 0, 'L');
                    $this->fpdf->ln(8);
                    
                    $Value['valor'] += $sCadeiras->valor_cadeira;

                endforeach;
            }

            $this->fpdf->ln(30);
            $this->fpdf->Cell(70, 0, '', 1, 0, 'L');
            $this->fpdf->ln(2);
            $this->fpdf->Cell(50, 6, utf8_decode('Assinatura do Participante'), 0, 0, 'L');
        }   
        
        $this->fpdf->Rodape();
        $this->fpdf->Output();  
    }    
}