<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Estamos usando a classe QRCode do namespace QRCodeExamples
//
use chillerlan\QRCode\{QRCode, QROptions};

class Relatorios extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('relatorios_model');
        $this->load->model('operacoes_model');
        $this->load->model('financeiro_model');
    }
	 
    //
    // Index do controlador de relatórias
    //
    public function index()
    {
        redirect(base_url() . 'home', 'refresh');
    }

    //
    // Retorno do filtro
    //
    public function filtro_lote($id_Evento)
    {
        $js = 'id="Lotes_Eventos" placeholder="Busque por um lote de evento..." style="width:600px;"';
        
        $AddLotesEventos   =   $this->relatorios_model->get_lotes_eventos_dropdown_search($id_Evento);
                                
        echo form_dropdown('Lotes_Eventos', $AddLotesEventos, set_value('Lotes_Eventos', ''), $js);          
    }

    //
    // Metodo para o relatório de pagantes para o financeiro
    //
    public function pagantes_eventos()
    {
        $this->data['AddCss']               =   load_css(array('relatorios/relatorios'));
        $this->data['AddJavascripts']       =   load_js(array('relatorios/financeiro_filial'));
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search(0);

        $this->usable('relatorios/financeiro_pagantes');
    }

    //
    // Metodo para o relatório de pagantes para o ministério de eventos
    //
    public function participantes_eventos()
    {
        $this->data['AddCss']               =   load_css(array('relatorios/relatorios'));
        $this->data['AddJavascripts']       =   load_js(array('relatorios/participantes_eventos'));
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search($this->session->userdata('intTipo'));

        $this->usable('relatorios/participantes_eventos');
    }    

    //
    // Metodo para o relatório de vendas no balcão
    //
    public function pagantes_eventos_balcao()
    {
        $this->data['AddCss']               =   load_css(array('relatorios/relatorios'));
        $this->data['AddJavascripts']       =   load_js(array('relatorios/financeiro_filial_balcao'));
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search($this->session->userdata('intTipo'));

        $this->usable('relatorios/balcao_pagantes');
    }
    
    //
    // Metodo para o relatório de vendas na bilheteria
    //
    public function pagantes_eventos_bilheteria()
    {
        $this->data['AddCss']               =   load_css(array('relatorios/relatorios'));
        $this->data['AddJavascripts']       =   load_js(array('relatorios/financeiro_filial_bilheteria'));
        $this->data['AddEventos']           =   $this->relatorios_model->get_eventos_dropdown_search($this->session->userdata('intTipo'));

        $this->usable('relatorios/bilheteria_pagantes');
    }     

    //
    // Evento para gerar o PDF em si
    //
    public function pdf_financeiro_eventos()
    {
        // CEL - Escreve no PDF 
        // Largura: 0
        // Altura da célula: 6
        // Conteudo: $titulo
        // Borda: 0 - sem borda
        // Quebra de linha: 0
        // Alinhamento: R (Right - Direita)

        // LN - Preenche a tabela
        // Posicao inicial: 40
        // Altura: 10
        // Conteudo: texto que será impresso.
        // Borda: 1 - mostra a borda
        // Quebra de linha: 0
        // Alinhamento: C - centralizado

        $this->load->helper('path');
        $this->load->library('fpdf');

        // Verifica se existe um POST se não cai fora
        //if(!$this->input->post('Situacao')) $this->fpdf->Output();

        // ID da Igreja, Nome, Inicio e Fim
        $Data['Situacao']    =  $this->input->post('Situacao');
        $Data['IdEvento']    =  $this->input->post('Eventos');
        $Data['Forma']       =  $this->input->post('Forma');
        $Data['Inscricao']   =  $this->input->post('Inscricao');
        $Data['Inicio']      =  $this->input->post('Inicio');
        $Data['Fim']         =  $this->input->post('Fim');

        // Chama o model para carregar os dados
        $NomeIgreja  =  $this->relatorios_model->get_nome_evento($Data['IdEvento']);

        // Posição vertical inicial
        $this->fpdf->SetY('-1');

        // Complemento do título
        if($Data['Forma'] == 4)
        {
            $statusTit = '(Pagamentos em Cartão e Dinheiro consolidados)';
        }
        else if($Data['Forma'] == 3)
        {
            $statusTit = '(Pagamentos em Dinheiro)';
        }	        
        else
        {
            if($Data['Situacao'] == 1)
            {
                if($Data['Inscricao'] == 2)
                {
                    $statusTit = '(Cartões pendentes onde inscrito não finalizou pagamento)';
                }
                else
                {
                    $statusTit = '(Cartões pendentes onde inscrito finalizou pagamento)';
                }
            }		
    
            if($Data['Situacao'] == 2)
            {
                $statusTit = '(Cartões quitados)';
            }			
        }

        $statusTot = ($Data['Situacao'] == 1)? 'TOTAL EM ABERTO BRUTO  -  R$ ' : 'TOTAL PAGO BRUTO  -  R$ ';
        $statusTotLiq = ($Data['Situacao'] == 1)? 'TOTAL EM ABERTO LIQUIDO  -  R$ ' : 'TOTAL PAGO LIQUIDO  -  R$ ';
        $statusGen = ($Data['Situacao'] == 1)? utf8_decode('TOTAL DE INSCRIÇÕES - ') : 'TOTAL DE PARTICIPANTES - ';

        // Texto do título
        $titulo = utf8_decode('Relatório Financeiro por Evento ' . $statusTit);

        $this->fpdf->AddPage("L");
        $this->fpdf->SetTitle($titulo);
        $this->fpdf->Cabecalho($titulo);
        $this->fpdf->Igreja(utf8_decode($NomeIgreja));
        
        $this->fpdf->Cell(58, 5, utf8_decode('Legenda: (CRE - Crédito / DEB - Débito / DIN - Dinheiro).'), 0, 0, 'L');  
        
        if($Data['Inicio'] != '' || $Data['Fim'] != '')
        {
            $this->fpdf->ln(5);
            $this->fpdf->Cell(58, 5, utf8_decode('Filtro por período De: ' . $Data['Inicio'] . ' Até: ' . $Data['Fim']), 0, 0, 'L');  
            $this->fpdf->ln(10);
        }
        else
        {
            $this->fpdf->ln(10);
        }
        
        $NumLinhas      =  1;
        $NumInscritos   =  0;
        $ValorTotal     =  0;
        $ValorTotalLiq  =  0;
        $status         =  '';	

        $Cartao  =  $this->relatorios_model->get_cartoes($Data);

        if($Cartao == 0) 
        {
            $this->fpdf->ln(10);
            $this->fpdf->Cell(275, 10, 'Nenhum registro foi encontrado.', 1, 0, 'C');
        }
        else
        {
            $this->fpdf->Cell(20, 5, 'Pedido', 1, 0, 'C');    
            $this->fpdf->Cell(70, 5, 'Inscrito', 1, 0, 'C');
            $this->fpdf->Cell(22, 5, 'CPF', 1, 0, 'C');
            $this->fpdf->Cell(70, 5, 'Dependente', 1, 0, 'C');
            $this->fpdf->Cell(40, 5, 'Status da Cielo', 1, 0, 'C');
            $this->fpdf->Cell(10, 5, 'Pagto.', 1, 0, 'C');
            $this->fpdf->Cell(20, 5, 'V. Bruto', 1, 0, 'C');
            $this->fpdf->Cell(20, 5, 'V. Liquido', 1, 0, 'C');
            $this->fpdf->ln(5);

            $Proprio = 0;
            $CPF = 0;

            foreach($Cartao as $vCartao)
            {
                if($NumLinhas >= 25) 
                {	
                    $NumLinhas = 1;

                    $this->fpdf->AddPage("L");
                    $this->fpdf->Cabecalho($titulo);
                    $this->fpdf->Igreja(utf8_decode($NomeIgreja));
                    $this->fpdf->Cell(58, 5, utf8_decode('Legenda: (CRE - Crédito / DEB - Débito / DIN - Dinheiro).'), 0, 0, 'L');  
                    $this->fpdf->ln(5);
                    $this->fpdf->Cell(58, 5, utf8_decode('Filtro por período De: ' . $Data['Inicio'] . ' Até: ' . $Data['Fim']), 0, 0, 'L');  
                    $this->fpdf->ln(5);

                    $this->fpdf->Cell(20, 5, 'Pedido', 1, 0, 'C');    
                    $this->fpdf->Cell(70, 5, 'Inscrito', 1, 0, 'C');
                    $this->fpdf->Cell(22, 5, 'CPF', 1, 0, 'C');
                    $this->fpdf->Cell(70, 5, 'Dependente', 1, 0, 'C');
                    $this->fpdf->Cell(40, 5, 'Status da Cielo', 1, 0, 'C');
                    $this->fpdf->Cell(10, 5, 'Pagto.', 1, 0, 'C');
                    $this->fpdf->Cell(20, 5, 'V. Bruto', 1, 0, 'C');
                    $this->fpdf->Cell(20, 5, 'V. Liquido', 1, 0, 'C');
                    $this->fpdf->ln(5);
                }

                if($vCartao->payment_status <= 0)
                {
                    $status    =  utf8_decode('Não Finalizou o Pagamento');
                }

                if($vCartao->payment_status == 1)
                {
                    $status    =  utf8_decode('Pendente na Cielo');
                }

                if($vCartao->payment_status == 2)
                {
                    //
                    // Para tratar pagamento em dinheiro
                    //
                    if($vCartao->tipo_pagamento == 3)
                    {
                        $status    =  utf8_decode('Pagamento em Dinheiro');        
                    }
                    else
                    {
                        $status    =  utf8_decode('Aprovado na Cielo');
                    }
                }

                if($vCartao->payment_status == 3)
                {
                    $status    =  utf8_decode('Negado na Cielo');
                }

                if($vCartao->payment_status == 4)
                {
                    $status    =  utf8_decode('Expirado na Cielo');
                }                  

                if($vCartao->payment_status == 5)
                {
                    $status    =  utf8_decode('Cancelado na Cielo');
                }															

                if($vCartao->payment_status == 6)
                {
                    $status    =  utf8_decode('Não Finalizado na Cielo');
                }

                if($vCartao->payment_status == 7)
                {
                    $status    =  utf8_decode('Autorizado na Cielo');
                }

                if($vCartao->tipo_pagamento == 0)
                {
                    $pagto   =  '-';
                } 

                if($vCartao->tipo_pagamento == 1)
                {
                    $pagto   =  utf8_decode('CRE');
                }                

                if($vCartao->tipo_pagamento == 3)
                {
                    $pagto   =  utf8_decode('DIN');
                }
                
                if($vCartao->tipo_pagamento == 4)
                {
                    $pagto   =  utf8_decode('DEB');
                }

                ###
                # 1 - N
                # 1 - 1
                # 1 - 0
                # 0 - N
                # 0 - 1
                # 0 - 0

                if($vCartao->int_Proprio == 1)
                {
                    if($vCartao->CPF != $CPF)
                    {
                        $valor = ($vCartao->tipo_pagamento == 3)? '-' : calcula_liquido($vCartao->valor_liquido, $vCartao->tipo_pagamento, $vCartao->parcelas);
                        
                        $this->fpdf->Cell(20, 5, gera_matricula($vCartao->pedido), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, utf8_decode(mb_strtoupper($vCartao->Pagante)), 1, 0, 'L');
                        $this->fpdf->Cell(22, 5, formatCPF_CNPJ($vCartao->CPF), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, utf8_decode(mb_strtoupper($vCartao->Pagante)), 1, 0, 'L');
                        $this->fpdf->Cell(40, 5, $status, 1, 0, 'L');
                        $this->fpdf->Cell(10, 5, $pagto, 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, 'R$ ' . num_to_user($vCartao->valor), 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, 'R$ ' . num_to_user($valor), 1, 0, 'C');
                        $this->fpdf->ln();
                        $NumInscritos++;
                        $NumLinhas++;

                        $ValorTotal += $vCartao->valor;
                        $ValorTotalLiq += calcula_liquido($vCartao->valor_liquido, $vCartao->tipo_pagamento, $vCartao->parcelas);
                    }

                    if($vCartao->str_Nome <> NULL)
                    {
                        $this->fpdf->Cell(20, 5, gera_matricula($vCartao->pedido), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, utf8_decode(mb_strtoupper($vCartao->Pagante)), 1, 0, 'L');
                        $this->fpdf->Cell(22, 5, formatCPF_CNPJ($vCartao->CPF), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, ($vCartao->str_Nome == NULL)? utf8_decode(mb_strtoupper($vCartao->Pagante)) : utf8_decode(mb_strtoupper($vCartao->str_Nome)), 1, 0, 'L');
                        $this->fpdf->Cell(40, 5, 'Dependente', 1, 0, 'L');
                        $this->fpdf->Cell(10, 5, '-', 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, '-', 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, '-', 1, 0, 'C');
                        $this->fpdf->ln();
                        $NumInscritos++;
                        $NumLinhas++;
    
                        $CPF = $vCartao->CPF;
                    }
                }

                if($vCartao->int_Proprio == 0)
                {
                    if($vCartao->CPF != $CPF)
                    {
                        $valor = ($vCartao->tipo_pagamento == 3)? '-' : calcula_liquido($vCartao->valor_liquido, $vCartao->tipo_pagamento, $vCartao->parcelas);
                        
                        $this->fpdf->Cell(20, 5, gera_matricula($vCartao->pedido), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, utf8_decode(mb_strtoupper($vCartao->Pagante)), 1, 0, 'L');
                        $this->fpdf->Cell(22, 5, formatCPF_CNPJ($vCartao->CPF), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, ($vCartao->str_Nome == NULL)? utf8_decode(mb_strtoupper($vCartao->Pagante)) : utf8_decode(mb_strtoupper($vCartao->str_Nome)), 1, 0, 'L');
                        $this->fpdf->Cell(40, 5, $status, 1, 0, 'L');
                        $this->fpdf->Cell(10, 5, $pagto, 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, 'R$ ' . num_to_user($vCartao->valor), 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, 'R$ ' . num_to_user($valor), 1, 0, 'C');
                        $this->fpdf->ln();
                        $NumInscritos++;
                        $NumLinhas++;

                        $ValorTotal += $vCartao->valor;
                        $ValorTotalLiq += calcula_liquido($vCartao->valor_liquido, $vCartao->tipo_pagamento, $vCartao->parcelas);
                    }
                   else
                   {
                        $this->fpdf->Cell(20, 5, gera_matricula($vCartao->pedido), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, utf8_decode(mb_strtoupper($vCartao->Pagante)), 1, 0, 'L');
                        $this->fpdf->Cell(22, 5, formatCPF_CNPJ($vCartao->CPF), 1, 0, 'C');
                        $this->fpdf->Cell(70, 5, ($vCartao->str_Nome == NULL)? utf8_decode(mb_strtoupper($vCartao->Pagante)) : utf8_decode(mb_strtoupper($vCartao->str_Nome)), 1, 0, 'L');
                        $this->fpdf->Cell(40, 5, 'Dependente', 1, 0, 'L');
                        $this->fpdf->Cell(10, 5, '-', 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, '-', 1, 0, 'C');
                        $this->fpdf->Cell(20, 5, '-', 1, 0, 'C');
                        $this->fpdf->ln();
                        $NumInscritos++;
                        $NumLinhas++;						
                    }

                    $CPF = $vCartao->CPF;
                }

                if($NumLinhas >= 25) $this->fpdf->RodapePaisagem();
            }

            $this->fpdf->Cell(185, 10, $statusTot . num_to_user($ValorTotal), 0, 0, 'L');
            
            if($vCartao->tipo_pagamento != 3) 
            {    
                $this->fpdf->ln(5); 
                $this->fpdf->Cell(185, 10, $statusTotLiq . num_to_user($ValorTotalLiq), 0, 0, 'L'); 
                
            }

            $this->fpdf->ln(5);
            $this->fpdf->Cell(185, 10, $statusGen . $NumInscritos, 0, 0, 'L');			
        }

        $this->fpdf->RodapePaisagem();
        $this->fpdf->Output();
    }

    /*
     *  Evento para gerar o PDF em si
     */
    public function pdf_financeiro_eventos_bilheteria()
    {
        $this->load->helper('path');
        $this->load->library('fpdf');

        //
        // Chama o model para carregar os dados
        //
        $NomeEvento  =  $this->relatorios_model->get_nome_evento($this->session->userdata('intTipo'));

        //
        // Posição vertical inicial
        //
        $this->fpdf->SetY('-1');

        //
        // Texto do título
        //
        $titulo = utf8_decode('Relatório Financeiro de Bilheteria por Evento ' . $NomeEvento);

        $this->fpdf->SetTitle($titulo);
        $this->fpdf->Cabecalho($titulo);

        //
        // Query das somas dos eventos
        //
        $Cartao  =  $this->relatorios_model->get_total_bilheteria_dia($this->session->userdata('intTipo'));  

        if($Cartao == 0) 
        {
            $this->fpdf->ln(10);
            $this->fpdf->Cell(185, 10, 'Nenhum registro foi encontrado.', 1, 0, 'C');
        }
        else
        {        
            $this->fpdf->SetFont('Arial', 'B', 10);

            foreach($Cartao as $vCartao)
            {        
                if($vCartao->Tipo_Pagamento == 1)
                {
                    $payment_method_type = 'CRÉDITO';
                }

                if($vCartao->Tipo_Pagamento == 2)
                {
                    $payment_method_type = 'DÉBITO';
                }
                
                if($vCartao->Tipo_Pagamento == 3)
                {
                    $payment_method_type = 'DINHEIRO';
                }

                $this->fpdf->ln(5);
                $this->fpdf->Cell(185, 10, utf8_decode('TOTAL EM '.$payment_method_type.'  -  R$ ') . num_to_user($vCartao->Valor), 0, 0, 'L');
                $this->fpdf->ln(5);
            }
        }

        $this->fpdf->Rodape();
        $this->fpdf->Output();
    } 
    
    //
    // Evento para gerar o PDF em si
    //    
    public function gerar_pdf($order_number)
    {
        $this->load->helper('path');
        $this->load->library('fpdf');

        //
        // Posição vertical inicial
        //
        $this->fpdf->SetY('-1');        

        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $array_Evento  =  $this->financeiro_model->get_id_dados_general($order_number);        
        
        //
        // Busco a informação do tipo do Evento e do nome do Evento
        //
        $Card  =  $this->financeiro_model->get_dados_card($order_number);

        //
        // Variáveis de suporte
        //
        $evento    =  $array_Evento->titulo;

        //
        // Variáveis de suporte
        //
        $status    =  '';

        //
        // Pega o status da compra
        //
        $status  =  paymentStatus($Card->Status_Pagamento, 'Status');

        //
        // Tipo de pagamento
        //
        $Legenda = '';

        if($Card->Tipo_Pagamento == 1)
        {
            $Legenda = 'CARTÃO DE CRÉDITO';
        }

        if($Card->Tipo_Pagamento == 4)
        {
            $Legenda = 'CARTÃO DE DÉBITO';   
        }

        if($Card->Tipo_Pagamento == 3)
        {
            $Legenda = 'DINHEIRO';    
        } 
        
        //
        // Texto do título
        //
        $titulo = utf8_decode('Comprovante de Pagamento'); 
        
        //
        // Imagem do título
        //
        $this->fpdf->Image('assets/images/wmb_logo.jpg', 8, 5, 18, 18);

        $this->fpdf->SetTitle($titulo);
        $this->fpdf->Cabecalho($titulo);        
        $this->fpdf->ln(10);
        $this->fpdf->Cabecalho('Dados do Pagador');        
        $this->fpdf->ln(10);
        $this->fpdf->ln(10);

        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->ln(10);
        $this->fpdf->Cell(185, 10, utf8_decode('Evento comprado: '.$evento), 0, 0, 'L');
        $this->fpdf->ln(10);
        $this->fpdf->Cell(185, 10, utf8_decode('Status do Pagamento: '.$status), 0, 0, 'L');
        $this->fpdf->ln(10);
        $this->fpdf->Cell(185, 10, utf8_decode('Numero do Pedido: '.gera_matricula($Card->Pedido)), 0, 0, 'L');
        $this->fpdf->ln(5);  
        $this->fpdf->Cell(185, 10, utf8_decode('Nome Completo: '.$Card->Nome), 0, 0, 'L');
        $this->fpdf->ln(5);  
        $this->fpdf->Cell(185, 10, utf8_decode('CPF: '.formatCPF_CNPJ($Card->CPF)), 0, 0, 'L');
        $this->fpdf->ln(5);  
        $this->fpdf->Cell(185, 10, utf8_decode('E-mail: '.$Card->Email), 0, 0, 'L');
        $this->fpdf->ln(5);  
        $this->fpdf->Cell(185, 10, utf8_decode('Tipo de Pagamento: '.$Legenda), 0, 0, 'L');

        $this->fpdf->ln(5);
        $this->fpdf->Cell(185, 10, utf8_decode('Valor Total: '.num_to_user($Card->Valor)), 0, 0, 'L');            
        
        $this->fpdf->ln(5);
        $this->fpdf->Cell(185, 10, utf8_decode('Data da compra: '.data_time_us_to_br($Card->Data_Pagamento)), 0, 0, 'L');

        //
        // Emissão do QR Code do Comprador
        //        
        if($Card->Status_Pagamento == 2)
        {
            //
            // Codigo do Evento
            //
            $Codigo = $Card->CPF . '&' . $Card->id_Evento . '&' . $Card->Status_Pagamento;

            $this->QrCode($Codigo);
        }

        //
        // Verifica se existe ou não acompanhantes
        //
        $acompanhantes  =  $this->financeiro_model->get_participantes_email($Card->Pedido);
        
        if($acompanhantes > 0)
        {
            $this->fpdf->ln(20);
            $this->fpdf->SetFont('Arial', '', 12);
            $this->fpdf->Cell(0, 12, 'Dados do(s) Inscrito(s)', 0, 0, 'C');
            $this->fpdf->SetFont('Arial', 'B', 10);

            $this->fpdf->ln(10);
            
            $NumLinhas = 0;

            foreach($acompanhantes as $sAcompanhantes):

                if($NumLinhas >= 1) 
                {	
                    //
                    // Texto do título
                    //
                    $titulo = utf8_decode('Comprovante de Pagamento'); 
                    
                    //
                    // Imagem do título
                    //
                    $this->fpdf->Image('assets/images/wmb_logo.jpg', 8, 5, 18, 18);

                    $this->fpdf->AddPage('P');
                    
                    $this->fpdf->SetTitle($titulo);
                    $this->fpdf->Cabecalho($titulo);                     
                    $this->fpdf->SetFont('Arial', 'B', 10);
                }                
                
                $this->fpdf->ln(10);
                $this->fpdf->Cell(185, 10, utf8_decode('Nome do Acompanhante: '.$sAcompanhantes->Nome), 0, 0, 'L');
                
                if($sAcompanhantes->CPF != NULL)
                {
                    $this->fpdf->ln(5);
                    $this->fpdf->Cell(185, 10, utf8_decode('CPF do Acompanhante: '.formatCPF_CNPJ($sAcompanhantes->CPF)), 0, 0, 'L');                    
                    
                    //
                    // Emissão do QR Code
                    //        
                    if($Card->Status_Pagamento == 2)
                    {
                        //
                        // Codigo do Evento
                        //
                        $Codigo = $sAcompanhantes->CPF . '&' . $Card->id_Evento . '&' . $Card->Status_Pagamento;

                        $this->QrCode($Codigo);

                        $this->fpdf->ln(10);
                        $this->fpdf->Cell(185, 10, utf8_decode('QR Code para validar sua presença!'), 0, 0, 'L');
                        $this->fpdf->ln(10);
                        $this->fpdf->Cell(185, 10, $this->fpdf->Image('../assets/qrcodes/'.$Codigo.'.png', $this->fpdf->GetX(), $this->fpdf->GetY(), 33.78), 0, 0, 'L');
                    }                    
                }
                
                $NumLinhas++;

            endforeach;
        } 

        $this->fpdf->Rodape();
        $this->fpdf->Output();
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
}