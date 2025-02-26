<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Atleta_model extends CI_Model {
	
    private $atletas       =    'tb_atletas';
    private $pagamentos    =    'tb_pagamentos';
	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Incluir o registro do atleta
    //
    public function insert_atleta($Data)
    {
        $this->db->insert($this->atletas, $Data);
    }
    
    //
    // Excluir o registro do atleta
    //    
    public function excluir_atleta($IdAtleta)
    {
        $this->db->where('Id', $IdAtleta)->delete($this->atletas);
        $this->db->where('IdAtleta', $IdAtleta)->delete($this->pagamentos);
    }

    //
    // Alterar o registro do atleta
    //
    public function update_atleta($Data)
    {
        return $this->db
                    ->where('Id', $Data['Id'])
                    ->update($this->atletas, $Data);    
    }	

    //
    // Busca os boletos por usuario
    //
    public function check_duplicidade_email($Email)
    {
        $query = $this->db
                      ->where('Email', $Email)
                      ->get($this->atletas);

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
    // Busca um atleta recebendo o ID
    //
    public function get_atleta($IdAtleta)
    {
        $query = $this->db
                      ->where('Id', $IdAtleta)
                      ->get($this->atletas);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return array();
        }
    } 
    
    //
    // Busca todos os atletas
    //
    public function get_all_atletas()
    {
        $query = $this->db
                      ->where('Posicao !=', 'Goleiro')
                      ->where('Inativo', 0)
                      ->order_by('Id', 'ASC')
                      ->get($this->atletas);
        
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return array();
        }
    }    
}

?>