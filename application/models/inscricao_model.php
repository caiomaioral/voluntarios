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
}

?>