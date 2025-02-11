<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Inscricao_model extends CI_Model {
	
    private $participantes    =   'enr_Participantes';
	
	public function __construct()
	{
        parent::__construct();
    } 

	//
	// Inclui o Participante
	//
	public function insert_cadastro($Data)
	{
		return $this->db->insert($this->participantes, $Data);
	}

    //
    // Verifica se existem duplicidades
    //
    public function Get_Duplicidades($Data)
    {
        $this->db->cache_off();

        $query = $this->db
                      ->where('CPF', $Data['CPF'])
                      ->get($this->participantes);

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
}

?>