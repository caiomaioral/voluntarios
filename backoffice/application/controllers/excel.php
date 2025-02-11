<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();
    }

    //
    // Evento para gerar o XLSX em si
    //
    public function index()
    {
        $Data['IdEvento']   =  $this->input->post('Eventos');
    }

    //
    //  Evento para gerar o XLSX em si
    //
    public function financeiro()
    {
        require_once APPPATH . 'third_party/PHPExcel.php';        
        
        //
        // Starting the PHPExcel library
        //
        $Data['IdEvento']   =  $this->input->post('Eventos');
        $Data['Forma']      =  $this->input->post('Forma');
        $Data['Situacao']   =  $this->input->post('Situacao');
        $Data['Eventos']    =  $this->input->post('Eventos');
        $Data['Inicio']     =  $this->input->post('Inicio');
        $Data['Fim']        =  $this->input->post('Fim');

        //
        // FILTRO por data de inicio e fim 
        //
        if($Data['Inicio'] != '' && $Data['Fim'] != '')
        {
            if($Data['Inicio'] == $Data['Fim'])
            {
                $this->db->where('Data_Cadastro', data_br_to_us($Data['Inicio']));    
            }
            else
            {
                $this->db->where('Data_Cadastro >=', data_br_to_us($Data['Inicio']));
                $this->db->where('Data_Cadastro <=', data_br_to_us($Data['Fim']));
            }
        }          

        // PIX
        if($Data['Forma'] == 2)
        {
            $this->db->where('Tipo_Pagamento', 2);
        }

        // GRANA
        if($Data['Forma'] == 3)
        {
            $this->db->where('Tipo_Pagamento', 3);
        }

        // FILTRO da SITUAÇÃO NÃO QUITADO
        if($Data['Situacao'] == 1)
        {
            $this->db->select('Pedido, Pedido AS Pedido, Nome, CPF, Tipo_Pagamento, Parcelas, Valor, Valor AS Valor_Liquido, Email, Cidade, UF, Quantidade_Ingressos, Status_Pagamento, Data_Cadastro', FALSE);

            $this->db->where('Status_Pagamento !=', 2);
            $this->db->order_by('Pedido', 'ASC');
        }
        
        // FILTRO da SITUAÇÃO QUITADO
        if($Data['Situacao'] == 2)
        {
            $this->db->select('Pedido, Pedido AS Pedido, Nome, CPF, Tipo_Pagamento, Parcelas, Valor, Valor AS Valor_Liquido, Email, Cidade, UF, Quantidade_Ingressos, Status_Pagamento, Data_Cadastro', FALSE);

            $this->db->where('Status_Pagamento', 2);
            $this->db->order_by('Pedido', 'ASC');
        }

        // FILTRO com evento
        if($Data['IdEvento'] > 0)
        {
            $this->db->where('id_Evento', $Data['IdEvento']);
        }

        // EXECUTA a query
        $query = $this->db
                      ->from('tbl_pagamentos')
                      ->get();
                      
        //
        // Create new PHPExcel object
        //
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('Relatorio Financeiro de Evento');
        $objPHPExcel->setActiveSheetIndex(0);

        //
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //
        $objPHPExcel->setActiveSheetIndex(0);        
        
        //
        // Field names in the first row
        //
        $fields = $query->list_fields();
        $col = 0;

        unset($fields[0]);

        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        //
        // Fetching the table data
        //
        $row = 2;
        
        foreach($query->result() as $data)
        {
            $col = 0;
            
            foreach ($fields as $field)
            {
                if($field == 'Pedido')
                {
                    $pedido = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, gera_matricula($pedido));
                }
                elseif($field == 'Status_Pagamento')
                {
                    if($data->$field == 0)
                    {
                        $status    =  'Não Finalizou Pagamento';
                    }                    
                    if($data->$field == 1)
                    {
                        $status    =  'Pedido Pendente';
                    }
                    if($data->$field == 2)
                    {
                        $status    =  'Pedido Pago';
                    }
                    if($data->$field == 3)
                    {
                        $status    =  'Negado';
                    }
                    if($data->$field == 4)
                    {
                        $status    =  'Expirado';
                    }                    
                    if($data->$field == 5)
                    {
                        $status    =  'Cancelado';
                    }															
                    if($data->$field == 6)
                    {
                        $status    =  'Não finalizada';
                    }
                    if($data->$field == 7)
                    {
                        $status    =  'Autorizada';
                    }

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $status);
                }
                elseif($field == 'Email')
                {
                    $email = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $email);
                }
                elseif($field == 'Tipo_Pagamento')
                {
                    if($data->$field == 0)
                    {
                        $tipo    =  'Não realizado pelo inscrito';
                        $type    =  0;
                    }
                    if($data->$field == 1)
                    {
                        $tipo    =  'Cartão de Crédito';
                        $type    =  1;
                    }
                    if($data->$field == 2)
                    {
                        $tipo    =  'PIX';
                        $type    =  2;
                    }                    
                    if($data->$field == 3)
                    {
                        $tipo    =  'Dinheiro';
                        $type    =  3;
                    } 
                    if($data->$field == 4)
                    {
                        $tipo    =  'Cartão de Débito';
                        $type    =  4;
                    }                                       
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $tipo);
                }
                elseif($field == 'Parcelas')
                {
                    $parcelas = $data->$field;
                                        
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                elseif($field == 'Valor')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                elseif($field == 'Valor_Liquido')
                {
                    $valor = ($type == 3)? $data->$field : calcula_liquido($data->$field, $type, $parcelas);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, round($valor));
                }                 
                else
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                $col++;
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Relatorio-Eventos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');      
    }

    //
    //  Evento para gerar o XLSX em si
    //
    public function eventos()
    {
        require_once APPPATH . 'third_party/PHPExcel.php';        
        
        //
        // Starting the PHPExcel library
        //
        $Data['IdEvento']   =  $this->input->post('Eventos');
        $Data['Situacao']   =  $this->input->post('Situacao');
        $Data['Eventos']    =  $this->input->post('Eventos');
        $Data['Lotes']      =  $this->input->post('Lotes_Eventos');
        $Data['Inicio']     =  $this->input->post('Inicio');
        $Data['Fim']        =  $this->input->post('Fim');

        //
        // FILTRO por data de inicio e fim 
        //
        if($Data['Inicio'] != '' && $Data['Fim'] != '')
        {
            if($Data['Inicio'] == $Data['Fim'])
            {
                $this->db->where('a.Data_Cadastro', data_br_to_us($Data['Inicio']));    
            }
            else
            {
                $this->db->where('a.Data_Cadastro >=', data_br_to_us($Data['Inicio']));
                $this->db->where('a.Data_Cadastro <=', data_br_to_us($Data['Fim']));
            }
        }
        
        // FILTRO do Lote
        if($Data['Lotes'] != 0)
        {
            $this->db->where('a.id_evento_link', $Data['Lotes']);
        }        

        // FILTRO da SITUAÇÃO NÃO QUITADO
        if($Data['Situacao'] == 1)
        {

            $this->db->select('a.id_Inscrito, b.titulo AS Titulo, a.Pedido, a.Nome, c.Email, a.CPF, a.RG, a.Nascimento, a.Cidade, a.UF, c.Celular, b.transporte AS Transporte, b.valor AS Valor, a.Modal, a.Pago, a.Presenca, a.Data_Hora_Presenca, a.Data_Cadastro', FALSE);
            $this->db->where('a.Pago IS NULL');
            $this->db->order_by('a.Pedido', 'ASC');
        }
        
        // FILTRO da SITUAÇÃO QUITADO
        if($Data['Situacao'] == 2)
        {
            
            $this->db->select('a.id_Inscrito, b.titulo AS Titulo, a.Pedido, a.Nome, c.Email, a.CPF, a.RG, a.Nascimento, a.Cidade, a.UF, c.Celular, b.transporte AS Transporte, b.valor AS Valor, a.Modal, a.Pago, a.Presenca, a.Data_Hora_Presenca, a.Data_Cadastro, CONCAT( a.CPF, "&", a.id_evento , "&" , 2) AS QRCode', FALSE);
            $this->db->where('a.Pago', 1);
            $this->db->order_by('a.Pedido', 'ASC');
        }

        // FILTRO com evento
        if($Data['IdEvento'] > 0)
        {
            $this->db->where('a.id_Evento', $Data['IdEvento']);
        }

        // EXECUTA a query
        $query = $this->db
                      ->from('tbl_participantes a')
                      ->join('tbl_eventos_links b', 'a.id_evento_link = b.id_evento_link')
                      ->join('tbl_pagamentos c', 'a.Pedido = c.Pedido')
                      ->get();

        //
        // Create new PHPExcel object
        //
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('Relatorio de Eventos');
        $objPHPExcel->setActiveSheetIndex(0);

        //
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //
        $objPHPExcel->setActiveSheetIndex(0);        
        
        //
        // Field names in the first row
        //
        $fields = $query->list_fields();
        $col = 0;

        unset($fields[0]);

        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        //
        // Fetching the table data
        //
        $row = 2;

        foreach($query->result() as $data)
        {
            $col = 0;
            
            //
            // ID do Inscrito
            //
            $inscrito = $data->id_Inscrito;

            foreach ($fields as $field)
            {
                if($field == 'Pedido')
                {
                    $pedido = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, gera_matricula($pedido));
                }
                elseif($field == 'Email')
                {
                    $email = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $email);
                }
                elseif($field == 'RG')
                {
                    $RG = ($data->$field == '' || $data->$field == NULL)? '' : $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $RG);
                } 
                elseif($field == 'Nascimento')
                {
                    $Nascimento = ($data->$field == '' || $data->$field == '0000-00-00')? '' : data_us_to_br($data->$field);
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $Nascimento);
                }
                elseif($field == 'Celular')
                {
                    $celular = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $celular);
                }
                elseif($field == 'Transporte')
                {
                    $valor_transporte = $data->$field;
                    
                    if($valor_transporte == '0.00')
                    {
                        $transporte  =  'Não';
                    }
                    else
                    {
                        $transporte  =  'Sim';
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $transporte);
                }                
                elseif($field == 'Valor')
                {
                    if($valor_transporte == '0.00')
                    {
                        $Valor = $data->$field;
                    }
                    else
                    {
                        $Valor = $data->$field + $valor_transporte;
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $Valor);
                }
                elseif($field == 'Pago')
                {
                    $Pago = $data->$field;
                    
                    if($data->$field == 0)
                    {
                        $Pago  =  'Não';
                    }
                    if($data->$field == 1)
                    {
                        $Pago  =  'Sim';
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $Pago);
                }                
                elseif($field == 'Modal')
                {
                    $IfUser = ($data->$field == 'site')? 'Inscrição On-line' : 'Inscrição Balcão';
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $IfUser);
                }
                elseif($field == 'Presenca')
                {
                    $Presenca = ($data->$field == 0)? 'Não' : 'Sim';
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $Presenca);
                }
                elseif($field == 'Data_Hora_Presenca')
                {
                    $Presenca = data_time_us_to_br($data->$field);
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $Presenca);
                }                                
                else
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                $col++;
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Relatorio-Eventos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');      
    }    

    //
    //  Evento para gerar o XLSX em si
    //
    public function eventos_balcao()
    {
        require_once APPPATH . 'third_party/PHPExcel.php';        
        
        //
        // Starting the PHPExcel library
        //
        $Data['IdEvento']   =  $this->input->post('Eventos');
        $Data['Forma']      =  $this->input->post('Forma');

        //
        // GRANA
        //
        if($Data['Forma'] == 2)
        {
            $this->db->where('Tipo_Pagamento', 2);
        }

        //
        // GRANA
        //
        if($Data['Forma'] == 3)
        {
            $this->db->where('Tipo_Pagamento', 3);
        }

        $this->db->select('Pedido, Pedido AS Pedido, Nome, CPF, Valor, Email, Cidade, UF, Tipo_Pagamento, Quantidade_Ingressos, Parcelas, Modal, Status_Pagamento, Data_Pagamento', FALSE);
        $this->db->where('Status_Pagamento', 2);
        $this->db->where('Modal', 'balcao');
        $this->db->order_by('Pedido', 'ASC');

        //
        // FILTRO com evento
        //
        if($Data['IdEvento'] > 0)
        {
            $this->db->where('id_Evento', $Data['IdEvento']);
        }

        // EXECUTA a query
        $query = $this->db
                      ->from('tbl_pagamentos')
                      ->get();
                      
        //
        // Create new PHPExcel object
        //
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('Relatorio Financeiro de Evento');
        $objPHPExcel->setActiveSheetIndex(0);

        //
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //
        $objPHPExcel->setActiveSheetIndex(0);        
        
        //
        // Field names in the first row
        //
        $fields = $query->list_fields();
        $col = 0;

        unset($fields[0]);

        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        //
        // Fetching the table data
        //
        $row = 2;
        
        foreach($query->result() as $data)
        {
            $col = 0;
            
            $status_cartao = '';

            foreach ($fields as $field)
            {
                if($field == 'Pedido')
                {
                    $pedido = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, gera_matricula($pedido));
                }
                elseif($field == 'Status_Pagamento')
                {
                    if($data->$field == 0)
                    {
                        $status    =  'Não Finalizou Pagamento';
                    }                    
                    if($data->$field == 1)
                    {
                        $status    =  'Pendente';
                    }
                    if($data->$field == 2)
                    {
                        //
                        // Para tratar pagamento em dinheiro
                        //
                        if($Data['Forma'] == 3)
                        {
                            $status    =  'Pago em Dinheiro';        
                        }
                        else
                        {
                            $status    =  'Pago em Cartão';
                        }                        
                    }
                    if($data->$field == 3)
                    {
                        $status    =  'Negado';
                    }
                    if($data->$field == 4)
                    {
                        $status    =  'Expirado';
                    }                    
                    if($data->$field == 5)
                    {
                        $status    =  'Cancelado';
                    }															
                    if($data->$field == 6)
                    {
                        $status    =  'Não finalizada';
                    }
                    if($data->$field == 7)
                    {
                        $status    =  'Autorizada';
                    }

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $status);
                }
                elseif($field == 'Email')
                {
                    $email = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $email);
                }
                elseif($field == 'Tipo_Pagamento')
                {
                    if($data->$field == 0)
                    {
                        $tipo    =  'Não realizado pelo inscrito';
                        $type    =  0;
                    }
                    if($data->$field == 1)
                    {
                        $tipo    =  'Cartão de Crédito';
                        $type    =  1;
                    }
                    if($data->$field == 4)
                    {
                        $tipo    =  'Cartão de Débito';
                        $type    =  2;
                    }
                    if($data->$field == 3)
                    {
                        $tipo    =  'Dinheiro';
                        $type    =  3;
                    }                    
                    
                    $status_cartao = $data->$field;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $tipo);
                }
                elseif($field == 'Parcelas')
                {
                    $parcelas = $data->$field;
                                        
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                elseif($field == 'Valor')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                else
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                }
                $col++;
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Relatorio-Eventos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');      
    }    
}