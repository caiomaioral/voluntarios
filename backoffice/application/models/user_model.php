<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class User_model extends CI_Model {

    private $administradores = 'tbl_usuarios';
	
    public function __construct()
    {
        parent::__construct();
    }

    /*
     *  Busca o login para autentica��o
     */
    public function get_login($Data)
    {
        $this->db->where('strLogin', $Data['Login']);
        $this->db->where('strSenha', $Data['Senha']);

        $query = $this->db->get($this->administradores);

        if($query->num_rows() > 0)
        {
            return $query->row_array();
        } 
        return false;
    }
    
    /*
     *  Busca o login para autentica��o com o id
     */
    public function get_login_id($id)
    {
        $this->db->where('idUsuario', $id);
        $query = $this->db->get($this->administradores);

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return array();
        }
    }

    /*
     *  Inclui um administrador
     */
    public function set_user($dados)
    {
        return $this->db->insert($this->administradores, $dados); 
    }
   
    /*
     *  Altera o administrador
     */
    public function alter_user($dados)
    {
        $this->db->where('idUsuario', $this->session->userdata('idUsuario'));
        
        return $this->db->update($this->administradores, $dados);
    }        

    /*
     *  Busca a senha do cara
     */
    public function alter_pwd($dados)
    {
        $data['strSenha'] = $dados['pass'];
        $this->db->where('strLogin', $dados['strLogin']);
        
        return $this->db->update($this->administradores, $data); 
    }
    
    /*
     *  Verifica se o usuario é igual a senha
     */
    public function check_pass($user)
    {
        $this->db->where('idUsuario', $user);
        $query = $this->db->get($this->administradores);
        
        foreach($query->result() as $u)
        {
            if($u->strLogin == $u->strSenha)
            {
                return true;
            }
        }
        return true;
    }
}