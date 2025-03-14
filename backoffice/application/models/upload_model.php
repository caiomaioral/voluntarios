<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Upload_model extends CI_Model {
	
    private $Participantes  =  'sig_Participantes';
	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Incluir o registro do atleta
    //
    public function insert_atleta($Data)
    {
        $this->db->insert($this->atletas, $Data);
    }
    
    //
    // Gera a leitura do Excel
    //
    public function set_lista_excel($File)
    {
        $this->load->library('SimpleXLSX');

        // Limpa a tabela temporária
        //$this->db->truncate($this->estudantes_tmp);

        //
        // Instancia do Excel
        //	
        $xlsx = new SimpleXLSX( $File['tmp_name'] );

        list($cols,) = $xlsx->dimension();

        foreach( $xlsx->rows() as $k => $r )
        {
            if($k == 0) continue;

            if($r[0] == NULL) continue;

            //
            // Pega para validar o e-mail
            //
            $Data['Email']    =    trim(mb_strtolower($r[0]));

            if(validaCPF($Data['CPF']))
            {
                if($this->check_duplicidade_estudantes($Data['CPF']) == FALSE)
                {
                    $Data['Descricao']    =    '<font color="red"><strong>Aluno já está cadastrado no TIA.</strong></font>';
                }
                
                if($this->check_duplicidade($Data['CPF']) == 0)
                {
                    $this->db->insert($this->estudantes_tmp, $Data);
                }
            }
        }

        debug($xlsx);
        
    }    
}

?>