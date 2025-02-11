<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Links extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('notification_model');
        $this->load->model('links_model');
        $this->load->model('relatorios_model');
    }
	 
    //
    // Lista as familias de acordo com a estudantes logada
    //
    public function index()
    {
        $this->data['AddCss']   	     =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']    =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'links/links'));
        $this->data['AddNotification']   =   $this->notification_model->show_evento_notification();
        
        $this->usable('links');
    }
	
    //
    // Método que visualiza os eventos
    //    
    public function visualizar($id_evento)
    {
        $this->data['AddCss']   	    =   load_css(array('financeiro/financeiro'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'jquery.dataTables.delay.min', 'dataTables.fnReloadAjax', 'participantes/participantes'));
        $this->data['AddData']          =   $this->links_model->get_eventos($id_evento);

		$this->data['AddStatus']        =   array(
                                                    NULL => 'Selecione um Status',
                                                    2 => 'Pago',
                                                    3 => 'Pendente',
                                                );

        $this->usable('participantes');		
    }

    //
    // Lista as familias de acordo com a estudantes logada
    //	
    public function listar_ajax()
    {
        echo $this->links_model->listar_eventos_json();
    }
    
    //
    // Metodo para inclusão de eventos (bola eventos)
    //
    public function incluir_eventos()
    {
        $this->data['AddCss']           =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']   =   load_js(array('links/incluir'));
        $this->data['AddEventos']       =   $this->relatorios_model->get_eventos_dropdown_search(0);

        $this->data['AddGateway']       =   array(0 => 'Defina quantidade de parcelas',
                                                  99 => 'Parcela Automática', 
                                                  1 => 'Pagamento à vista', 
                                                  2 => '2 parcelas', 
                                                  3 => '3 parcelas', 
                                                  4 => '4 parcelas',
                                                  5 => '5 parcelas', 
                                                  6 => '6 parcelas',
                                                  7 => '7 parcelas',
                                                  8 => '8 parcelas',
                                                  9 => '9 parcelas',
                                                  10 => '10 parcelas',
                                                  11 => '11 parcelas',
                                                  12 => '12 parcelas');
        
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

        $this->usable('links/incluir_evento');
    }    

    //
    // Metodo que inclui um evento para o eventos
    //
    public function enviar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_eventos();
        }
        else
        {
            //
            // Inclusão do Arquivo
            //
            $Folder   	  =    'assets/upload';
            $PathFiles    =    pathinfo($_FILES['str_Arquivo']['name']);
            $Arquivo      =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);

            //
            // Upload do Arquivo
            //			
            if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $Arquivo))
            {
                $Data['id_evento']       =   $this->input->post('Eventos');                
                $Data['titulo']          =   trim($this->input->post('str_Titulo'));
                $Data['url']    	     =   sanitize_title_with_dashes($this->input->post('str_Url'));
                $Data['texto']    	     =   $this->input->post('editor');
                $Data['parcelas']    	 =   $this->input->post('int_Gateway');
                $Data['data_evento']     =   data_br_to_us($this->input->post('Data_Evento'));
                $Data['fim_evento']      =   ($this->input->post('Fim_Evento') == '')? NULL : data_br_to_us($this->input->post('Fim_Evento'));
                $Data['validade']        =   data_br_to_us($this->input->post('Data_Limite'));
                $Data['valor']    	     =   num_to_db($this->input->post('str_Valor'));
                $Data['limite']    	     =   $this->input->post('int_Limite');
                $Data['adicionais']    	 =   $this->input->post('int_Adicionais');
                $Data['transporte']      =   num_to_db($this->input->post('str_Transporte'));
                $Data['header']    	     =   $Arquivo;
                $Data['criado_em'] 	     =   date('Y-m-d');
                $Data['status']    	     =   1;
                
                //
                // Efetua a persistência no banco de dados
                //	                
                $this->links_model->set_eventos($Data);
                
                $Admin['ID_Admin']       =   $this->session->userdata('idUsuario');
                $Admin['ID_Evento']      =   $this->db->insert_id();
                
                //
                // Efetua a persistência no banco de dados
                //	                 
                $this->links_model->set_admin_eventos($Admin);
                
                $this->usable('sucesso');
            }
        }
    }

    //
    // Metodo que exibe a janela com os dados dos eventos
    //
    public function alterar($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('links/alterar'));
        $this->data['DataBody'] 	        =   $this->links_model->get_eventos($id);
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search(0);
        
        $this->data['AddGateway']           =   array(0 => 'Defina quantidade de parcelas',
                                                      99 => 'Parcela Automática', 
                                                      1 => 'Pagamento à vista', 
                                                      2 => '2 parcelas', 
                                                      3 => '3 parcelas', 
                                                      4 => '4 parcelas',
                                                      5 => '5 parcelas', 
                                                      6 => '6 parcelas',
                                                      7 => '7 parcelas',
                                                      8 => '8 parcelas',
                                                      9 => '9 parcelas',
                                                      10 => '10 parcelas',
                                                      11 => '11 parcelas',
                                                      12 => '12 parcelas');   

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

        $this->usable('links/alterar_evento');
    }

    //
    // Metodo que salva a alteração de um evento
    //
    public function salvar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('ID'));	
        }
        else
        {
            //
            // Inclusão do Arquivo
            //
            $Folder    =   'assets/upload';

            //
            // Upload do Arquivo
            //
            if($_FILES['str_Arquivo'])
            {
                if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $_FILES['str_Arquivo']['name']))
                {			 
                    $PathFiles         =    pathinfo($_FILES['str_Arquivo']['name']);
                    $File_Name         =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);
                    
                    $Data['header']   =   $File_Name;
                }
            }

            $Data['id_evento_link']  =   $this->input->post('ID');
            $Data['id_evento']       =   $this->input->post('Eventos');
            $Data['titulo']          =   trim($this->input->post('str_Titulo'));
            $Data['url']    	     =   sanitize_title_with_dashes($this->input->post('str_Url'));
            $Data['texto']    	     =   $this->input->post('editor');
            $Data['parcelas']        =   $this->input->post('int_Gateway');
            $Data['data_evento']     =   data_br_to_us($this->input->post('Data_Evento'));
            $Data['fim_evento']      =   ($this->input->post('Fim_Evento') == '')? NULL : data_br_to_us($this->input->post('Fim_Evento'));
            $Data['validade']        =   data_br_to_us($this->input->post('Data_Limite'));
            $Data['valor']    	     =   num_to_db($this->input->post('str_Valor'));
            $Data['limite']          =   $this->input->post('int_Limite');
            $Data['adicionais']      =   $this->input->post('int_Adicionais');            
            $Data['transporte']      =   num_to_db($this->input->post('str_Transporte'));

            $this->links_model->update_eventos($Data);

            redirect(base_url() . 'links', 'refresh');						
        }
    }

    //
    // Chama o metodo do model para ativar um evento
    //
    public function ativar($id)
    {
        $dados['id_evento_link']  =  $id;
        $dados['status']          =  1;

        $this->links_model->ativar_eventos($dados);	
    }

    //
    // Chama o metodo do model para inativar um evento
    //
    public function inativar($id)
    {
        $dados['id_evento_link']  =  $id;
        $dados['status']          =  0;

        $this->links_model->inativar_eventos($dados);	
    }

    //
    // Chama o metodo do model para "exclusão" do registro
    //
    public function excluir($id)
    {
        $this->links_model->excluir($id);
    }

    //
    // Metodo que verifica se a URL é repetida
    //
    public function uniqueURL($URL)
    {
        if($this->links_model->get_url($URL) == false)
        {
            $this->form_validation->set_message('uniqueURL', 'Já existe um evento <strong>ATIVO</strong> com o endereço <strong>'.mb_strtoupper($URL).'</strong>.');
            return false;	            
        }
        else 
        {
            return true;
        }
    }

    //
    // Metodo que exibe a janela com os dados dos eventos que vai ser duplicado
    //
    public function duplicar($id)
    {
        $this->data['AddCss']               =   load_css(array('financeiro/conteudos'));
        $this->data['AddJavascripts']       =   load_js(array('links/alterar'));
        $this->data['DataBody'] 	        =   $this->links_model->get_eventos($id);
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search(0);
        
        $this->data['AddGateway']           =   array(0 => 'Defina quantidade de parcelas',
                                                      99 => 'Parcela Automática', 
                                                      1 => 'Pagamento à vista', 
                                                      2 => '2 parcelas', 
                                                      3 => '3 parcelas', 
                                                      4 => '4 parcelas',
                                                      5 => '5 parcelas', 
                                                      6 => '6 parcelas',
                                                      7 => '7 parcelas',
                                                      8 => '8 parcelas',
                                                      9 => '9 parcelas',
                                                      10 => '10 parcelas',
                                                      11 => '11 parcelas',
                                                      12 => '12 parcelas');   

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

        $this->usable('links/duplicar_evento');
    }

    //
    // Metodo que salva a alteração de um evento duplicado para salvar
    //
    public function salvar_duplicar_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->duplicar($this->input->post('ID'));	
        }
        else
        {
            //
            // Inclusão do Arquivo
            //
            $Folder    =   'assets/upload';

            //
            // Upload do Arquivo
            //
            if($_FILES['str_Arquivo'])
            {
                if(move_uploaded_file($_FILES['str_Arquivo']['tmp_name'], $Folder . '/' . $_FILES['str_Arquivo']['name']))
                {			 
                    $PathFiles        =    pathinfo($_FILES['str_Arquivo']['name']);
                    $File_Name        =    sanitize_title_with_dashes($PathFiles['filename']) . '.' . deepLower($PathFiles['extension']);
                    
                    $Data['header']   =   $File_Name;
                }
            }

            $Data['id_evento']       =   $this->input->post('Eventos');
            $Data['titulo']          =   trim($this->input->post('str_Titulo'));
            $Data['url']    	     =   sanitize_title_with_dashes($this->input->post('str_Url'));
            $Data['texto']    	     =   $this->input->post('editor');
            $Data['parcelas']        =   $this->input->post('int_Gateway');
            $Data['data_evento']     =   data_br_to_us($this->input->post('Data_Evento'));
            $Data['fim_evento']      =   ($this->input->post('Fim_Evento') == '')? NULL : data_br_to_us($this->input->post('Fim_Evento'));
            $Data['validade']        =   data_br_to_us($this->input->post('Data_Limite'));
            $Data['valor']    	     =   num_to_db($this->input->post('str_Valor'));
            $Data['limite']          =   $this->input->post('int_Limite');
            $Data['adicionais']      =   $this->input->post('int_Adicionais');            
            $Data['transporte']      =   num_to_db($this->input->post('str_Transporte'));
            $Data['header']    	     =   (isset($File_Name))? $File_Name: $this->input->post('str_Arquivo_Pronto');
            $Data['criado_em'] 	     =   date('Y-m-d');
            
            //
            // Efetua a persistência no banco de dados
            //	                
            $this->links_model->set_eventos($Data);
            
            $Admin['ID_Admin']       =   $this->session->userdata('idUsuario');
            $Admin['ID_Evento']      =   $this->db->insert_id();
            
            //
            // Efetua a persistência no banco de dados
            //	                 
            $this->links_model->set_admin_eventos($Admin);

            redirect(base_url() . 'links', 'refresh');						
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