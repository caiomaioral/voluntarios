<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Ofertas_model extends CI_Model {
	
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
		return $this->db->insert($this->ofertas, $Doador);
	}

	//
	// Inclui o cartão para o cara doar
	//
	public function insert_pagamento($Cartao)
	{
		return $this->db->insert($this->pagamentos, $Cartao);	
	}	

    //
    // Incluir o status do cartão no banco
    //
    public function update_payment($Data)
    {
        return $this->db
                    ->where('pedido', $Data['order_number'])
                    ->update($this->cartoes, $Data);    
    }	 

    //
    // Verifica se existem duplicidades
    //
    public function check_donation($CPF)
    {
        $query = $this->db
                      ->select('id_doador, str_nome, str_email')
					  ->where('str_CPF', $CPF)
                      ->get($this->ofertas);

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
                      ->select('id_doador')
					  ->where('str_CPF', $CPF)
                      ->get($this->ofertas);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return $query->row()->id_doador;
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
                      ->select('str_nome')
					  ->where('str_CPF', $CPF)
                      ->get($this->ofertas);

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
    // Retorna ID e Nome para Select List
    //
    public function get_projetos_dropdown()
    {
        $query = $this->db
                      ->order_by('Descricao', 'ASC')
                      ->get($this->projetos);
        
        if($query->num_rows() > 0)
        {
            //$row[0] = "Escolha seu projeto social para fazer sua oferta";
            
            $row[0] = "Selecione o Projeto";

            foreach ($query->result() as $aRow)
            {
                $row[$aRow->Id] = mb_strtoupper($aRow->Descricao);
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }
    
	//
	// Função para pegar os espertos
	//
	public function Get_Duplicidades($CPF)
	{
        $query = $this->db->query('SELECT customer_identity FROM tb_ofertas WHERE customer_identity = "' . $CPF . '" AND payment_status != 0 AND created_date LIKE "%' . date('Y-m-d') . '%"');
        
		if($query->num_rows() == 0)
		{
            return 0;
        }
		else
		{
            return 1;
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