<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Autenticacao_model extends CI_Model {

    private $usuarios     =   'tb_usuarios';
    private $atletas      =   'tb_atletas';
	
    public function __construct()
    {
        parent::__construct();
    }

    //
    // Efetua login
    //
    public function get_login($Data)
    {
        $query = $this->db
                      ->where('Email', $Data['Login'])
                      ->where('Senha', $Data['Senha'])
                      ->where('Inativo', 0)
                      ->get($this->atletas);

        if($query->num_rows() > 0)
        {
            /*
             *  Inicia as Sessions
             */ 
            $this->load->library('session');

            $data = array('UltimoAcesso' => date('Y-m-d H:i:s'));

            return $query->row_array();
        } 

        return false;
    }

    /*
     *  Verifica se o email consta na base
     */
    public function get_email($data)
    {
        $query = $this->db
                      ->where('Email', $data)
                      ->get($this->usuarios);

        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return array();
        }		
    }
	
    /*
     *  Busca usuário pelo ID
     */    
    public function get_login_id($id)
    {
        $this->db->where('idUsuario', $id);
        $query = $this->db->get($this->usuarios);

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
     *  Inclui usuario na base
     */
    public function set_user($dados)
    {
        return $this->db->insert($this->usuarios, $dados); 
    }
   
    /*
     *  Altera o usuario
     */
    public function alter_user($dados)
    {
        $this->db->where('idUsuario', $this->session->userdata('idUsuario'));
        return $this->db->update($this->usuarios, $dados);
    }        

    /*
     *  Altera a senha
     */
    public function alter_pwd($dados)
    {
        $data['strSenha'] = $dados['pass'];
        $this->db->where('strLogin', $dados['strLogin']);
        return $this->db->update($this->usuarios, $data); 
    }
    
    /*
     *  Checa se a senha confere
     */
    public function check_pass($user)
    {
        $this->db->where('idUsuario', $user);
        $query = $this->db->get($this->usuarios);
        
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