<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Usuarios_model extends CI_Model {
	
    private $usuarios       =  'tbl_usuarios';
    private $eventos        =  'tbl_eventos_gerais';
	
    public function __construct()
    {
        parent::__construct();
    } 

    /*
     *  Lista os boletos que ainda não foram compensados
     */
    public function listar_usuarios_json()
    {
        if($this->session->userdata('intTipo') <> 0)
        {
            $this->db->where('id_evento', $this->session->userdata('intTipo')); 
        }

        $query = $this->db
                      ->where('intTipo !=', 0)
                      ->order_by('dtCadastro', 'DESC')
                      ->get($this->usuarios);            
									
        //
        // CONTA as linhas para o JSON
        //
        if($query->num_rows() > 0)
        {
            $output = array("aaData" => array());

            foreach ($query->result() as $aRow)
            {
                $row = array($aRow->idUsuario, $aRow->strLogin, $this->get_evento_nome($aRow->intTipo), data_us_to_br($aRow->dtCadastro));

                $output['aaData'][] = $row;
            }

            return @json_encode($output);
        }
        else
        {
            $row = array(0, 0, 0, 0);

            $output = array("aaData" => array());

            return @json_encode($output);
        }
    }

   /*
     *  Inclui os dados no banco
     */
    public function insert_usuario($dados)
    {
        return $this->db->insert($this->usuarios, $dados);
    }

    /*
     *  Altera os dados do conteudo
     */
    public function update_usuario($dados)
    {
        return $this->db
                    ->where('idUsuario', $dados['idUsuario'])
                    ->update($this->usuarios, $dados);
    }
	
    /*
     *  Excluir o cara de vez e dane-se
     */
    public function excluir($id)
    {
        $this->db->where('idUsuario', $id)->delete($this->usuarios);

        return true;
    }    

    /*
     *  Busca o usuário para alteração
     */
    public function get_usuario($id_usuario)
    {
        $query = $this->db
                      ->where('idUsuario', $id_usuario)
                      ->get($this->usuarios);
        
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
     *  Busca a string do nome de um evento diverso
     */
    public function get_evento_nome($id_evento)
    {
        $query = $this->db
                      ->where('id_evento', $id_evento)
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            return mb_strtoupper($query->row()->titulo);
        }
        else
        {
            return array();
        }
    }
}

?>