<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Perfil_model extends CI_Model {
	
    private $atletas   =  'tb_atletas';
	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Busca a senha do cara
    //
    public function get_senha($idUsuario)
    {
        $query = $this->db
                      ->select('Senha')
                      ->where('Id', $idUsuario)
                      ->get($this->atletas);
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                return $row->Senha;
            }            
        }
        else
        {
            return array();
        }
    }

    //
    // Muda a senha do cara
    //
    public function update_perfil($dados)
    {
        return $this->db
                    ->where('Id', $dados['Id'])
                    ->update($this->atletas, $dados);
    }
	
    //
    // Altera os dados na tabela cracha
    //	
    public function update_cracha($Data)
    {
        return $this->db
                    ->where('Id', $Data['Id'])
                    ->update($this->atletas, $Data);		
    }      		
}

?>
