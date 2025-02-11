<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Financeiro_model extends CI_Model {
	
    private $inscricao        =  'tbl_participantes';
    private $cartao           =  'tbl_pagamentos';
    private $eventos          =  'tbl_eventos_links';
    private $eventos_links    =  'tbl_eventos_links';
	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Lista os boletos vazio no grid
    //
    public function listar_pagamentos_json_vazio()
    {
        $row = array(0, 0, 0, 0, 0, 0);

        $output = array("aaData" => array());

        return @json_encode($output);
    }

    //
    // Lista os boletos que ainda não foram compensados
    //
    public function listar_eventos_json()
    {
        if($this->session->userdata('intTipo') <> 0)
        {
            $this->db->where('id_eventos', $this->session->userdata('intTipo')); 
        }

        $query = $this->db
                      ->order_by('id_eventos', 'DESC')
                      ->get($this->eventos);            
									
        //
        // CONTA as linhas para o JSON
        //
        if($query->num_rows() > 0)
        {
            $output = array("aaData" => array());

            foreach ($query->result() as $aRow)
            {
                if($this->session->userdata('intTipo') == 0)
                {
                    $status = ($aRow->status == 1)? '<a href="javascript:Disabled('.$aRow->id_eventos.')">Ativo</a>' : '<a href="javascript:Active('.$aRow->id_eventos.')">Inativo</a>';
                }
                else
                {
                    $status = ($aRow->status == 1)? '<a href="javascript:void(0)">Ativo</a>' : '<a href="javascript:void(0)">Inativo</a>';    
                }
                
                if($aRow->transporte == 0.00)
                {
                    $transporte = '<strong>Não teremos transporte</strong>';	
                }
                else
                {
                    $transporte = '<strong>Sim, teremos transporte no valor de R$ '.num_to_user($aRow->transporte).'</strong>';	
                }

                //
                // If para o tipo de Evento
                //
                if($aRow->tipo_evento == 1)
                {
                    $tipo_evento = 'Bola Eventos';
                    $valor = 'R$ ' . num_to_user($aRow->valor);
                }
                else
                {
                    $tipo_evento = 'Bola Teatro';   
                    $valor = 'Não tem valor';
                    $transporte = '<strong>Evento sem transporte</strong>';
                }

                $row = array($aRow->id_eventos, $aRow->titulo, $tipo_evento, $valor, $transporte, data_us_to_br($aRow->validade), $status);

                $output['aaData'][] = $row;
            }

            return @json_encode($output);
        }
        else
        {
            $row = array(0, 0, 0, 0, 0, 0);

            $output = array("aaData" => array());

            return @json_encode($output);
        }
    }
    
    //
    // Atualiza o registro do cartão na hora da compra
    //
    public function update_payment($Data)
    {
        return $this->db
                    ->where('pedido', $Data['order_number'])
                    ->update($this->cartao, $Data);
    }
	
    //
    // Atualiza somente o status na hora da mudança
    //
    public function update_status($Data, $Check)
    {
        return $this->db
                    ->where('checkout_cielo_order_number', $Check['checkout_cielo_order_number'])
                    ->update($this->cartao, $Data);
    }

    //
    // Atualiza somente o status na hora da mudança
    //
    public function update_pedido($Data)
    {
        $Data['Status_Pagamento']  =  paymentStatus($Data['Status_Pagamento'], 'Codigo');
        
        //
        // Verifica se o pagamento esta ok e garante as vendas
        //
        if($Data['Status_Pagamento'] == 2)
        {
            $this->db
                 ->where('Pedido', $Data['Pedido'])
                 ->update($this->inscricao, array('Pago' => 1));
        }
        else
        {
            $this->db
                 ->where('Pedido', $Data['Pedido'])
                 ->update($this->inscricao, array('Pago' => 0));   
        }

        return $this->db
                    ->where('Pedido', $Data['Pedido'])
                    ->update($this->cartao, $Data);
    }   

    //
    // Atualiza somente o status na hora da mudança
    //
    public function update_tickets($Data)
    {
        //
        // Verifica se o pagamento esta ok e garante as vendas
        //
        if($Data['status'] == 2)
        {
            $this->db->trans_start();
            $this->db->query('SELECT vendidos FROM tbl_eventos_links WHERE id_evento_link = ' . $Data['id_evento_link'] . ' FOR UPDATE');
            $this->db->query('UPDATE tbl_eventos_links SET vendidos = vendidos + ' . $Data['vendidos'] . ' WHERE id_evento_link = '. $Data['id_evento_link']);
            $this->db->trans_complete();            
        }
        else
        {
            $this->db->trans_start();
            $this->db->query('SELECT vendidos FROM tbl_eventos_links WHERE id_evento_link = ' . $Data['id_evento_link'] . ' FOR UPDATE');
            $this->db->query('UPDATE tbl_eventos_links SET vendidos = vendidos - ' . $Data['vendidos'] . ' WHERE id_evento_link = '. $Data['id_evento_link']);
            $this->db->trans_complete();                 
        }

        return true;
    }
    
    //
    //  Busca os dados do cartão para exibir ao usuário
    //
    public function get_dados_card($pedido)
    {
        $query = $this->db
                      ->where('Pedido', $pedido)
                      ->get($this->cartao);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 0;
        }
    }
    
    //
    // Busca um evento diverso
    //
    public function get_participantes_email($order)
    {
        $query = $this->db
                      ->where('Pedido', $order)
                      ->get($this->inscricao);
					  
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return 0;
        }
    }

    //
    // Retorna o nome do evento
    //
    public function get_id_dados_general($Order_number)
    {
        $query = $this->db
                      ->select('t1.id_Evento, t2.titulo, t2.alerta')
                      ->where('t1.id_Evento_Link = t2.id_evento_link')
                      ->where('t1.Pedido', $Order_number)
                      ->get($this->cartao . ' t1, ' . $this->eventos_links . ' t2');          
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 'Bola de Neve';
        }
    }    
    
    //
    // Excluir o inscrito e o dependente
    //
    public function excluir_geral($id)
    {
        $this->db->where('pedido', $id)->delete($this->cartao);

        return true;
    }     
}

?>