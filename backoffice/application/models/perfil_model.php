<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Perfil_model extends CI_Model {
	
	private $administradores = "tbl_usuarios";
    
	public function __construct()
	{
        parent::__construct();
    } 

	/*
	 *  Retorna os dados do usuario para a tela de alteração
	 */
    public function get_usuarios($id)
	{
        $query = $this->db
					  ->select('idUsuario, strNome, strLogin, strSenha, strEmail')
					  ->where('idUsuario', $id)
					  ->get($this->administradores);
        
        if($query->num_rows() > 0)
		{
            return $query->row();
        }
		else
		{
            return array();
        }
    }

	/*
	 *  Busca a senha do cara
	 */
    public function get_senha($idUsuario)
	{
        $query = $this->db
                      ->select('strSenha')
                      ->where('idUsuario', $idUsuario)
                      ->get($this->administradores);
        
        if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				return $row->strSenha;
			}            
        }
		else
		{
            return array();
        }
    }

	/*
	 *  Muda a senha do cara
	 */
	public function update_perfil($dados)
	{
		return $this->db
                    ->where('idUsuario', $dados['idUsuario'])
                    ->update($this->administradores, $dados);
	}

	/*
	 *  Seta no banco o nome da imagem de avatar
	 */
	public function set_uploads_avatar($dados)
	{
		return $this->db
                    ->where('idUsuario', $dados['idUsuario'])
                    ->update($this->administradores, $dados);
	}
}

?>
