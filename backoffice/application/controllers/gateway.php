<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Gateway extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('session');
        
        $this->load->model('gateway/eventos_model');
        $this->load->model('gateway/inscricao_model');
        $this->load->model('gateway/financeiro_model');
    }

    //
    // Chama o metodo para incluir o participante que vai pagar no balcão
    //    
    public function incluir_participante($id_evento)
    {
        //
        // Mostrar os dados do evento para incluir no form
        //
        $this->data['AddBody']    =    $this->eventos_model->get_dados_evento_by_id($id_evento);

        //
        // Mostrar os dados do evento para incluir no form
        //        
        $Numero_Inscritos     =    $this->data['AddBody']->vendidos;
        $Numero_Maximo        =    $this->data['AddBody']->limite;

        if($Numero_Inscritos >= $Numero_Maximo)
        {
            $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
            
            $this->usable_form('gateway/encerrado');            
        }
        else
        {
            $this->data['AddJavascripts']    =    load_js(array('form/form_eventos'));
            
            $this->usable_form('gateway/form_eventos');                   
        }
    }
    
    //
    // Chama o metodo NOVO para incluir o participante que vai pagar no balcão
    //
    public function enviar_participante_evento()
    {
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir_participante($this->input->post('id_evento_link'));
        }
        else
        {         
            //
            // Tabela de Inscritos
            //
            $Order['id_Evento']               =     $this->input->post('id_evento');
            $Order['id_Evento_Link']          =     $this->input->post('id_evento_link');
            $Order['Valor'] 		          =     $this->input->post('ValorTotal');
            $Order['Nome']                    =     trim(mb_strtoupper($this->input->post('str_Nome')));
            $Order['CPF']    	              =     trim(str_replace('-', '', str_replace('.', '', $this->input->post('str_CPF'))));
            $Order['Email']    	              =     trim(mb_strtolower($this->input->post('str_Email')));
            $Order['Celular']    	          =     trim($this->input->post('str_Celular'));
            $Order['Cidade']           	   	  =     trim(mb_strtoupper($this->input->post('str_Cidade')));
            $Order['UF']           	   	      =     $this->input->post('id_UF');
            $Order['Tipo_Pagamento']    	  =     $this->input->post('sel_Pagamento');
            $Order['Parcelas']    	          =     1;
            $Order['Modal']                   =     'balcao';
            $Order['Status_Pagamento']    	  =     2;
            $Order['Comprovante']    	      =     1;
            $Order['Quantidade_Ingressos']    =     $this->input->post('sel_Adicionais');
            $Order['Data_Cadastro']    	      =     date('Y-m-d H:i');
            $Order['Data_Pagamento']   	      =     date('Y-m-d H:i'); 
            
            //
            //  Bota para dentro o Pagamento em Cartão
            // 
            $Participante['Pedido']           =     $this->eventos_model->insert_cartao($Order);
            $Participante['Modal']            =     'balcao';
            $Participante['Pago']             =     1;
            $Participante['Data_Cadastro']    =     $Order['Data_Cadastro'];
            
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
                    // Recebe post
                    //
                    $v_Nome = $this->input->post('Nome_Adicional');
                    
                    //
                    // Chama os arrays
                    //
                    $Participante['Nome'] = trim(mb_strtoupper($v_Nome[$x]));
                    
                    //
                    // Recebe post
                    //
                    $v_CPF = $this->input->post('CPF_Adicional');
                    
                    //
                    // Chama os arrays
                    //
                    $Participante['CPF'] = str_replace('-', '', str_replace('.', '', $v_CPF[$x])); 
                    
                    //
                    // Info nova Cidade e UF
                    //
                    $v_Cidade       =   $this->input->post('Cidade_Adicional');
                    $v_UF           =   $this->input->post('UF_Adicional');
                    
                    $Participante['Cidade']        =    trim($v_Cidade[$x]);
                    $Participante['UF']            =    trim($v_UF[$x]);

                    //
                    // Info nova RG e Nascimento
                    //                  
                    $v_Numero_RG    =   $this->input->post('RG_Transporte_Adicional');
                    $v_Nascimento   =   $this->input->post('Data_Nascimento_Adicional');
                    
                    $Participante['RG']            =    trim($v_Numero_RG[$x]);
                    $Participante['Nascimento']    =    data_br_to_us($v_Nascimento[$x]);

                    //
                    //  Bota para dentro o Adicional
                    //                    
                    $this->eventos_model->insert_inscrito($Participante);
                }

                //
                // Mensagem de sucesso
                //
                redirect(base_url() . 'gateway/sucesso/' . $this->input->post('id_evento_link') . '/' . $Participante['Pedido']);
            }
        }         
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
                                        <td style="padding-left:9px; padding-top: 5px"><input name="Nome_Adicional['.$x.']" type="text" value="" size="50" maxlength="64" class="Nome_Adicional required"></td>
                                     </tr>
                                     <tr><td height="20"></td></tr>
                                     <tr>
                                         <td style="padding-left:9px">CPF</td>
                                     </tr>
                                     <tr>
                                        <td style="padding-left:9px; padding-top: 5px"><input name="CPF_Adicional['.$x.']" type="text" size="12" value="" maxlength="14" class="CPF_Adicional required" unique="currency"></td>
                                     </tr>
                                     <tr>
                                        <td>
                                            <table width="100%" border="0">
                                            <tr>
                                            <td height="20"></td>
                                            </tr>                                                             
                                            <tr>
                                                <td style="padding-left:9px">RG</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px"><input name="RG_Transporte_Adicional['.$x.']" type="text" value="" size="10" maxlength="20" class="RG_Transporte_Adicional required"></td>
                                            </tr>
                                            <tr><td height="20"></td></tr>                           
                                            <tr>
                                                <td style="padding-left:9px">Nascimento</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px"><input name="Data_Nascimento_Adicional['.$x.']" type="text" size="8" value="" maxlength="10" class="Data_Nascimento_Adicional required"></td>
                                            </tr>
                                        
                                            </table>                                                        
                                        </td>
                                     </tr>
                                     <tr><td height="20"></td></tr>
                                     <tr>
                                        <td style="padding-left:6px">Cidade</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:6px; padding-top: 5px">
                                        
                                            <input name="Cidade_Adicional['.$x.']" type="text" value="" size="50" maxlength="64" class="Cidade_Adicional required">
                                            
                                        </td>
                                    </tr>
                                    <tr><td height="20"></td></tr>                                                                                                                                    
                                    <tr>
                                        <td style="padding-left:6px">UF</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:6px; padding-top: 5px">
                                            
                                            <select name="UF_Adicional['.$x.']" class="UF_Adicional required">
                                            <option value="">Selecione um Estado</option>
                                            <option value="AC">ACRE (AC)</option>
                                            <option value="AL">ALAGOAS (AL)</option>
                                            <option value="AM">AMAZONAS (AM)</option>
                                            <option value="AP">AMAPÁ (AP)</option>
                                            <option value="BA">BAHIA (BA)</option>
                                            <option value="CE">CEARÁ (CE)</option>
                                            <option value="DF">DISTRITO FEDERAL (DF)</option>
                                            <option value="ES">ESPÍRITO SANTO (ES)</option>
                                            <option value="GO">GOIÁS (GO)</option>
                                            <option value="MA">MARANHÃO (MA)</option>
                                            <option value="MG">MINAS GERAIS (MG)</option>
                                            <option value="MS">MATO GROSSO DO SUL (MS)</option>
                                            <option value="MT">MATO GROSSO (MT)</option>
                                            <option value="PA">PARÁ (PA)</option>
                                            <option value="PB">PARAÍBA (PB)</option>
                                            <option value="PE">PERNAMBUCO (PE)</option>
                                            <option value="PI">PIAUÍ (PI)</option>
                                            <option value="PR">PARANÁ (PR)</option>
                                            <option value="RJ">RIO DE JANEIRO (RJ)</option>
                                            <option value="RN">RIO GRANDE DO NORTE (RN)</option>
                                            <option value="RO">RONDÔNIA (RO)</option>
                                            <option value="RR">RORAIMA (RR)</option>
                                            <option value="RS">RIO GRANDE DO SUL (RS)</option>
                                            <option value="SC">SANTA CATARINA (SC)</option>
                                            <option value="SE">SERGIPE (SE)</option>
                                            <option value="SP">SÃO PAULO (SP)</option>
                                            <option value="TO">TOCANTINS (TO)</option>
                                            </select>

                                        </td>
                                    </tr>                                     
                                     <tr>
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
        $Participante   =  ($this->input->get('participante') == 1)? 1 : $this->input->get('participante');
        $Valor_Total    =  0.00;

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
        
        if($Quantidade == 1)
        {
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
        
        if($Quantidade >= 2)
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

        echo $_return = '<font style="font-size:20px"><strong>R$ '.number_format($Valor_Total, 2, ',', '.').'</strong></font>
                         <input id="ValorTotal" name="ValorTotal" type="hidden" value="'.$Valor_Total.'" /><br />Valor total a ser pago.';
    }
    
    //
    // Metodo que exibe uma mensagem de sucesso ao fim do cadastro do participante
    //    
    public function sucesso($id_evento, $pedido)
    {
        $this->data['AddCss']            =    load_css(array('BreadCrumb', 'form/style'));
        $this->data['AddJavascripts']    =    load_js(array('form/mensagens'));
        
        //
        // Busco os dados para ir para o e-mail
        // 
        $this->data['AddBody']           =    $this->eventos_model->get_dados_evento_by_id($id_evento);      
        $this->data['AddUser']           =    $this->financeiro_model->get_dados_card($pedido);
        
        $this->usable_form('gateway/sucesso_participante');   
    }
    
    //
    // Metodo que finaliza o pagamento
    //
    public function pdf_recibo()
    {    
        $this->load->helper('path');
        $this->load->library('fpdf');

        // Posição vertical inicial
        $this->fpdf->SetY('-1');

        // Texto do título
        $titulo = utf8_decode('Recibo de Pagamento de Evento');

        // Variáveis
        $Pedido  =  $this->input->post('Pedido');

        // Carega os dados das alunos e suas notinhas
        $Dados  =  $this->eventos_model->get_dados_pedido($Pedido);
        
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

            if($Dados->Tipo_Pagamento == 1)
            {
                $Legenda = utf8_decode('CARTÃO DE CRÉDITO');
            }

            if($Dados->Tipo_Pagamento == 4)
            {
                $Legenda = utf8_decode('CARTÃO DE DÉBITO');   
            }

            if($Dados->Tipo_Pagamento == 3)
            {
                $Legenda = 'DINHEIRO';    
            }
            
            // Numero de linhas para quebra de página
            $NumLinhas       =  0;
            $Total_Cartao    =  $Dados->Valor;

            $this->fpdf->ln(8);

            $this->fpdf->SetFont('Arial', 'B', 10);
            $this->fpdf->Cell(50, 6, 'NUMERO PEDIDO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, gera_matricula($Dados->Pedido), 0, 0, 'L');
            $this->fpdf->ln(8);            
            $this->fpdf->Cell(50, 6, 'PAGANTE:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, $Dados->Nome, 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'TOTAL PAGO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, 'R$ ' . num_to_user($Total_Cartao), 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'FORMA DE PAGAMENTO:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, $Legenda, 0, 0, 'L');
            $this->fpdf->ln(8);
            $this->fpdf->Cell(50, 6, 'DATA DA COMPRA:', 0, 0, 'L');
            $this->fpdf->Cell(70, 6, data_us_to_br($Dados->Data_Pagamento), 0, 0, 'L'); 

            //
            // Lista os acompanhantes
            //
            $acompanhantes  =  $this->financeiro_model->get_acompanhantes($Dados->Pedido);
            
            if($acompanhantes > 0)
            {
                $this->fpdf->ln(20);
                
                $this->fpdf->Cell(50, 6, 'DADOS DO(S) PARTICIPANTE(S):', 0, 0, 'L');
                $this->fpdf->ln(8);
                
                foreach($acompanhantes as $sAcompanhantes):
                    
                    $this->fpdf->Cell(50, 6, 'NOME:', 0, 0, 'L');
                    $this->fpdf->Cell(70, 6, $sAcompanhantes->Nome, 0, 0, 'L');
                    $this->fpdf->ln(8);

                    if($sAcompanhantes->CPF != '99999999999')
                    {
                        $this->fpdf->Cell(50, 6, 'CPF:', 0, 0, 'L');
                        $this->fpdf->Cell(70, 6, formatCPF_CNPJ($sAcompanhantes->CPF), 0, 0, 'L');
                        $this->fpdf->ln(8);                    
                    }        
    
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
