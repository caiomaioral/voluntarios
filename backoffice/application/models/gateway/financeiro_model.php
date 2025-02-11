<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Financeiro_model extends CI_Model {
	
    private $eventos_links  =  'tbl_eventos_links';
    private $eventos        =  'tbl_eventos_gerais';
    private $gateway        =  'tbl_gateway';
    private $inscricao      =  'tbl_participantes';
    private $cartao         =  'tbl_pagamentos';
    private $meus_cartoes   =  'tbl_meus_cartoes';
	
    public function __construct()
    {
        parent::__construct();
    } 
    
    //
    // Atualiza somente o status na hora da mudança
    //
    public function update_status($Data)
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

        return true;
    }    
    
    //
    //  Busca os dados do evento para exibir ao usuário
    //
    public function get_dados_inscrito($id_inscrito)
    {
        $query = $this->db
                      ->where($this->inscricao . '.id_Inscrito = ' . $this->cartao . '.id_inscrito')      
                      ->where($this->inscricao . '.id_Inscrito', $id_inscrito)
                      ->get($this->inscricao . ', ' . $this->cartao);
        
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
    //  Busca um evento diverso
    //
    public function get_acompanhantes($order)
    {
        $query = $this->db
                      ->select('Nome, CPF')
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
}

?>