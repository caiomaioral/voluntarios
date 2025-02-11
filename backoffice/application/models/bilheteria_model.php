<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Bilheteria_model extends CI_Model {
	
    private $eventos        =  'tbl_eventos_links';
    private $inscricao      =  'tbl_participantes';
    private $dependente     =  'tbl_dependentes_conferencia';
    private $cartao         =  'tbl_pagamentos';
	
    public function __construct()
    {
        parent::__construct();
    } 
    
    //
    // Busca os dados do evento para exibir ao usuário
    //
    public function get_dados_evento($Id)
    {
        $query = $this->db
                      ->where('id_evento_link', $Id)
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
    // Inclui um inscrito
    //
    public function insert_inscrito($dados)
    {
        $this->db->insert($this->inscricao, $dados);
        
        return true;
    }

    //
    // Busca um evento diverso
    //
    public function get_eventos($id_evento)
    {
        $query = $this->db
                      ->where('id_evento', $id_evento)
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return array();
        }
    }
}

?>