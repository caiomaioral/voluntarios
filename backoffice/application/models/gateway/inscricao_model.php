<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Inscricao_model extends CI_Model {
	
    private $cartoes    =  'tb_ofertas';
    private $doacoes    =  'tb_doador';
    private $igrejas    =  'tb_igrejas';
    private $estado     =  'tbl_estado';
	
	public function __construct()
	{
        parent::__construct();
    } 

    //
    // Retorna ID e Nome para Select List
    //
    public function get_estados_dropdown()
    {
        $query = $this->db
                      ->order_by('UF', 'ASC')
                      ->get($this->estado);
        
        if($query->num_rows() > 0)
        {
            $row[''] = "Selecione o seu Estado";

            foreach ($query->result() as $aRow)
            {
                $row[$aRow->UF] = $aRow->Nome;
            }

            return $row; 
        }
        else
        {
            return array();
        }
    } 

	//
	// Inclui o doador
	//
	public function insert_doador($Doador)
	{
		return $this->db->insert($this->doacoes, $Doador);
	}

	//
	// Inclui o cartão para o cara doar
	//
	public function insert_cartao($Cartao)
	{
		return $this->db->insert($this->cartoes, $Cartao);	
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
                      ->get($this->doacoes);

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
                      ->get($this->doacoes);

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
                      ->get($this->doacoes);

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
    public function get_igrejas_dropdown()
    {
        $query = $this->db
                      ->order_by('str_nome', 'ASC')
                      ->get($this->igrejas);
        
        if($query->num_rows() > 0)
        {
            $row[0] = "Escolha sua Igreja";

            foreach ($query->result() as $aRow)
            {
                $row[$aRow->id_igreja] = ucwords(mb_strtolower($aRow->str_nome));
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }    
}

?>