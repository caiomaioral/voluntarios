<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Gateway_model extends CI_Model {
	
    private $eventos        =  'tbl_eventos_gerais';
    private $igrejas        =  'tbl_igrejas';
    private $inscricao      =  'tbl_participantes';
    private $cartao         =  'tbl_pagamentos';
	
    public function __construct()
    {
        parent::__construct();
    } 
    
    //
    // Retorna ID e Nome para Select List
    //
    public function get_igrejas_dropdown_search()
    {
        $query = $this->db
                      ->select('id_igreja, str_nome')
                      ->order_by('str_nome', 'ASC')
                      ->get($this->igrejas);
        
        if($query->num_rows() > 0)
        {
            $row[0] = "SELECIONE A IGREJA PARA FILTRAR";

            foreach ($query->result() as $aRow)
            {
                $row[$aRow->id_igreja] = mb_strtoupper($aRow->str_nome);
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }

    /*
     *  Busca os dados para o tipo de cartão selecionado
     */
    public function get_dados_cartoes($Type)
    {
        $query = $this->db
		              ->where('Type', $Type)
                      ->order_by('OrderCard', 'ASC')
                      ->get($this->meus_cartoes);
        
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
     *  Busca os dados para o tipo de cartão selecionado
     */
    public function get_dados_cartoes_by_flag($Card)
    {
        $query = $this->db
		              ->where('CardImage', $Card)
                      ->get($this->meus_cartoes);
        
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
     *  Busca os dados do evento para exibir ao usuário
     */
    public function get_dados_evento($Id)
    {
        $query = $this->db
                      ->where('id_evento', $Id)
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 0;
        }
    }
    
    /*
     *  Busca os dados do evento para exibir ao usuário
     */
    public function get_dados_inscrito($id_inscrito)
    {
        $query = $this->db
                      ->select('*, (SELECT COUNT(id_Inscrito) FROM tbl_dependentes_conferencia b WHERE b.id_Inscrito = tbl_inscricao_conferencia.id_Inscrito) AS Quantidade')
                      ->where($this->inscricao . '.id_Inscrito = ' . $this->cartao . '.id_inscrito')      
                      ->where($this->inscricao . '.id_Inscrito', $id_inscrito)
                      ->get($this->inscricao . ', ' . $this->cartao);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 0;
        }
    }    

    /*
     *  Busca o ID atual da tabela usando o antigo molde
     */
    public function get_id_inscricao()
    {
        $query = $this->db
		              ->select_max('id_Inscrito')
                      ->get($this->inscricao);
        
        if($query->row()->id_Inscrito == '')
        {
            return 1;
        }
        else
        {
            return $query->row()->id_Inscrito + 1;
        }
    }    

    /*
     *  Busca o ID atual da tabela usando o antigo molde
     */
    public function get_id_dependente()
    {
        $query = $this->db
		              ->select_max('id_Dependente')
                      ->get($this->dependente);
        
        if($query->row()->id_Dependente == '')
        {
            return 1;
        }
        else
        {
            return $query->row()->id_Dependente + 1;
        }
    }

    /*
     *  Cria o nome do pedido baseado no auto_increment
     */      
    public function create_order_number()
    {
        $nosso_numero = $this->db
                             ->query('SHOW TABLE STATUS LIKE \'' . $this->cartao . '\'')
                             ->row()
                             ->Auto_increment;

        return str_pad($nosso_numero, 8, '0', STR_PAD_LEFT);							  
    }    
    
    /*
     *  Inclui um inscrito
     */
    public function insert_inscrito($dados)
    {
        $this->db->insert($this->inscricao, $dados);
        
        return true;
    }

    /*
     *  Inclui o cartão
     */
    public function insert_cartao($dados)
    {
        $this->db->insert($this->cartao, $dados);
        
        return true;  
    }  
    
    /*
     *  Inclui um adicional
     */
    public function insert_adicional($dados)
    {
        $this->db->insert($this->dependente, $dados);
    }
    
    /*
     *  Inclui o nome do cara que vai estar presente ou não no evento
     */
    public function insert_presenca($dados)
    {
        $this->db->insert($this->convite, $dados);
    }    
    
    /*
     *  Verifica se existem duplicidades
     */
    public function check_duplicidade_evento($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['ID'])
                      ->where('str_CPF', $dados['CPF'])
                      ->get($this->inscricao);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return false;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return true;
        }
    }

    /*
     *  Verifica se existem duplicidades
     */
    public function check_duplicidade_adicionais_evento($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['ID'])
                      ->where('str_CPF', $dados['CPF'])
                      ->get($this->dependente);

        if($query->num_rows() > 0)
        {
            // Tem duplicidade e não pode continuar
            return false;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return true;
        }
    }    

    /*
     *  Verifica se existem duplicidades
     */
    public function check_return_id($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['id_Evento'])
                      ->where('str_CPF', $dados['str_CPF'])
                      ->get($this->inscricao);

        if($query->num_rows() > 0)
        {
            return $query->row()->id_Inscrito;
        }
        else
        {
            return 0;
        }
    }      
    
    //
    // Verifica o pagamento e mostra para o usuário
    //    
    public function payment_finder($dados)
    {
         $query = $this->db
                      ->where('a.id_Inscrito = b.id_inscrito')
                      ->where('a.str_CPF', $dados['str_CPF'])
                      ->where('a.id_Evento', $dados['id_Evento'])
                      ->get($this->inscricao . ' a, ' . $this->cartao . ' b');

        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return (object) array('payment_status' => 404);
        }       
    }
    
    //
    // Lista os boletos que ainda não foram compensados
    //
    public function listar_eventos_json()
    {
        if($this->session->userdata('intTipo') <> 0)
        {
            $this->db->where('id_evento', $this->session->userdata('intTipo')); 
        }

        $query = $this->db
                      ->where('inativo', 0)
                      ->order_by('id_evento', 'DESC')
                      ->get($this->eventos);            
									
        //
        // CONTA as linhas para o JSON
        //
        if($query->num_rows() > 0)
        {
            $output = array("aaData" => array());

            foreach ($query->result() as $aRow)
            {
                $row = array($aRow->id_evento, $aRow->titulo);

                $output['aaData'][] = $row;
            }

            return @json_encode($output);
        }
        else
        {
            $row = array(0, 0);

            $output = array("aaData" => array());

            return @json_encode($output);
        }
    }    

    //
    // Busca um evento diverso
    //
    public function get_eventos($id_evento)
    {
        $query = $this->db
                      ->where('id_evento', $id_evento)
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return array();
        }
    }

    //
    // Inclui os dados no banco
    //
    public function set_eventos($dados)
    {
        return $this->db->insert($this->eventos, $dados);
    }

    //
    // Altera os dados do conteudo
    //
    public function update_eventos($dados)
    {
        return $this->db
                    ->where('id_evento', $dados['id_evento'])
                    ->update($this->eventos, $dados);
    } 
    
    //
    // Excluir os dados do conteudo
    //    
    public function excluir($id)
    {
        return $this->db
                    ->where('id_evento', $id)
                    ->update($this->eventos, array('inativo' => 1));
    }

}

?>