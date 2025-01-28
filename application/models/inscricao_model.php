<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Inscricao_model extends CI_Model {
	
    private $doadores      =   'Doadores';
    private $pagamentos    =   'Pagamentos';
    private $projetos      =   'Projetos';
	
	public function __construct()
	{
        parent::__construct();
    } 

	//
	// Inclui o doador
	//
	public function insert_doador($Doador)
	{
		return $this->db->insert($this->doadores, $Doador);
	}

	//
	// Inclui o Pagamento
	//
	public function insert_pagamento($Pagamento)
	{
		return $this->db->insert($this->pagamentos, $Pagamento);
	}    
    
    //
    // Verifica se existem duplicidades
    //
    public function check_donation($CPF)
    {
        $query = $this->db
                      ->select('Id, Nome, Email, Telefone')
					  ->where('CPF', $CPF)
                      ->get($this->doadores);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return $query->row();
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return false;
        }
    }

    //
    // Retorna o ID do doador
    //
    public function get_id_doador($CPF)
    {
        $query = $this->db
					  ->where('CPF', $CPF)
                      ->get($this->pagamentos);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return $query->row()->Pedido;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return false;
        }
    }    

	//
    // Verifica se existem duplicidades
    //
    public function check_person_donation($CPF)
    {
        $query = $this->db
					  ->where('CPF', $CPF)
                      ->get($this->doadores);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não inclui
            return false;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return true;
        }
    }	

	//
	// Busca o Auto Increment
	//
	public function Get_Pedido()
	{
        $query = $this->db->query('SHOW TABLE STATUS LIKE "' . $this->cartoes . '"');
        
		if($query->num_rows() > 0)
		{
            return $query->row()->Auto_increment;
        }
		else
		{
            return 0;
        }
    }
    
	//
	// Função para pegar os espertos
	//
	public function Get_Duplicidades($CPF)
	{
        $query = $this->db
					  ->where('CPF', $CPF)
                      ->where('StatusPagamento != 0')
                      ->where('DataCadastro', date('Y-m-d'))
                      ->get($this->pagamentos);        

		if($query->num_rows() == 0)
		{
            echo 'true';
        }
		else
		{
            echo 'false';
        }
    }

	//
	// Função para pegar os espertos
	//
	public function Get_Fraudes($CPF, $Email)
	{
        $query = $this->db->query('SELECT customer_identity FROM dizimos.tb_ofertas WHERE (customer_identity = "' . $CPF . '" OR customer_email = "' . $Email . '") AND (payment_status != 2 AND payment_status != 0)');
        
		if($query->num_rows() >= 2)
		{
            return 1;
        }
		else
		{
            return 0;
        }
    }    
    

    
}

?>