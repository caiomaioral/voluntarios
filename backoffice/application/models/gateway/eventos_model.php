<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Eventos_model extends CI_Model {
	
    private $eventos        =  'tbl_eventos_links';
    private $inscricao      =  'tbl_participantes';
    private $cartao         =  'tbl_pagamentos';
	
    public function __construct()
    {
        parent::__construct();
    } 
    
    //
    // Busca os dados do evento para exibir ao usuário
    //
    public function get_dados_evento($URL)
    {
        $query = $this->db
                      ->where('url', $URL)
                      ->get($this->eventos);
        
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
    // Busca os dados do evento para exibir ao usuário
    //
    public function get_dados_evento_by_id($id_link)
    {
        $query = $this->db
                      ->where('id_evento_link', $id_link)
                      ->get($this->eventos);
        
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
    //  Inclui o cartão
    //
    public function insert_cartao($dados)
    {
        $this->db->insert($this->cartao, $dados);
        
        return $this->db->insert_id();        
    } 
    
    //
    //  Inclui o inscrito
    //
    public function insert_inscrito($dados)
    {
        //
        // Não existe duplicidade e pode continuar
        //
        $this->db->insert($this->inscricao, $dados);
        
        return true;
    }    
    
    //
    // Verifica se existem duplicidades
    //
    public function check_duplicidade_evento($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['ID'])
                      ->where('CPF', $dados['CPF'])
                      ->where('Pago', 1)
                      ->get($this->inscricao);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return true;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return false;
        }
    }
    
    //
    // Busca os dados do pagamento para exibir ao usuário
    //
    public function get_dados_pedido($pedido)
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
}

?>