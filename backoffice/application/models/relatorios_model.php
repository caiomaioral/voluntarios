<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Relatorios_model extends CI_Model {
	
    private $inscricao        =  'tbl_participantes';
    private $cartao           =  'tbl_pagamentos';
    private $eventos          =  'tbl_eventos_links';
    private $eventos_gerais   =  'tbl_eventos_gerais';
	
    public function __construct()
    {
        parent::__construct();
    } 

    /*
     *  Busca os dados para o relatório
     */
    public function get_cartoes($Data)
    {
        //
        // Pega o valor original do evento
        //
        $valorEvento = $this->get_valor_eventos($Data['IdEvento']);
        
        //
        // FILTRO por data de inicio e fim 
        //
        if($Data['Inicio'] != '' && $Data['Fim'] != '')
        {
            if($Data['Inicio'] == $Data['Fim'])
            {
                $this->db->where('t3.payment_date', data_br_to_us($Data['Inicio']));    
            }
            else
            {
                $this->db->where('t3.payment_date >=', data_br_to_us($Data['Inicio']));
                $this->db->where('t3.payment_date <=', data_br_to_us($Data['Fim']));
            }
        }          
        
        //
        // GRANA
        //
        if($Data['Forma'] == 3)
        {
            $this->db->where('t3.payment_method_type', 3);
        }

        //
        // FILTRO da SITUAÇÃO NÃO QUITADO
        //
        if($Data['Situacao'] == 1)
        {
            $this->db->select('t3.pedido, t1.str_Nome AS Pagante, t1.str_CPF AS CPF, t2.str_Nome, t2.str_CPF, t1.str_UF, t1.str_Email, t1.str_Cidade, t1.str_Celular, t3.created_date, t3.valor, t3.valor AS valor_liquido, t3.payment_status, t1.int_Proprio, t3.payment_method_type AS tipo_pagamento, t3.tid, t3.payment_installments AS parcelas');
            $this->db->where('t3.payment_status !=', 2);
            $this->db->order_by('t3.customer_name', 'ASC');
        }

        //
        // FILTRO da SITUAÇÃO QUITADO
        //
        if($Data['Situacao'] == 2)
        {
            $this->db->select('t3.pedido, t1.str_Nome AS Pagante, t1.str_CPF AS CPF, t2.str_Nome, t2.str_CPF, t1.str_UF, t1.str_Email, t1.str_Cidade, t1.str_Celular, t3.created_date, t3.valor, t3.valor AS valor_liquido, t3.payment_status, t1.int_Proprio, t3.payment_method_type AS tipo_pagamento, t3.tid, t3.payment_installments AS parcelas');
            $this->db->where('t3.payment_status', 2);
            $this->db->order_by('t3.customer_name', 'ASC');
        }

        //
        // FILTRO com evento
        //
        if($Data['IdEvento'] > 0)
        {
            $this->db->where('t1.id_Evento', $Data['IdEvento']);
        }

        //
        // FILTRO que valida se o cara fechou o pagamento na Cielo ou não
        //        
        if($Data['Forma'] == 1)
        {
            if($Data['Inscricao'] == 1)
            {
                $this->db->where('t3.tid IS NOT NULL');
            }
            
            if($Data['Inscricao'] == 2)
            {
                $this->db->where('t3.tid IS NULL');    
            }
        }

        //
        // EXECUTA a query
        //
        $query = $this->db
                      ->from('tbl_inscricao_conferencia t1')
                      ->join('tbl_cartao t3', 't1.id_Inscrito = t3.id_Inscrito')
                      ->join('tbl_dependentes_conferencia t2', 't1.id_Inscrito = t2.id_Inscrito', 'left')
                      ->get();

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return 0;
        }
    }

    //
    // Retorna ID e Nome para Select List
    //
    public function get_eventos_dropdown_search($id_igreja)
    {
        if($id_igreja > 0)
        {
            $this->db->where('id_evento', $id_igreja);    
        }
        
        $query = $this->db
                      ->select('id_evento, titulo', false)
                      ->order_by('id_evento', 'DESC')
                      ->order_by('titulo', 'ASC')
                      ->get($this->eventos_gerais);
        
        if($query->num_rows() > 0)
        {
            $row[0] = "SELECIONE O EVENTO PARA FILTRAR";

            foreach ($query->result() as $aRow)
            {
                $row[$aRow->id_evento] = mb_strtoupper($aRow->titulo);
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }

    //
    // Retorna ID e Nome para Select List
    //
    public function get_lotes_eventos_dropdown_search($id_evento)
    {
        $query = $this->db
                      ->select('id_evento_link, titulo', false)
                      ->where('id_evento', $id_evento)
                      ->order_by('id_evento_link', 'DESC')
                      ->order_by('titulo', 'ASC')
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $aRow)
            {
                $row[$aRow->id_evento_link] = mb_strtoupper($aRow->titulo);
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }    
	
    /*
     *  Busca um evento diverso
     */
    public function get_nome_evento($id_evento)
    {
        $query = $this->db
                      ->select('titulo')
                      ->where('id_evento', $id_evento)
                      ->get($this->eventos_gerais);
        
        if($query->num_rows() > 0)
        {
            return $query->row()->titulo;
        }
        else
        {
            return array();
        }
    }	

	/*
	 *  Busca um evento diverso
	 */
    public function get_eventos($id_evento)
    {
        $query = $this->db
                      ->where('id_eventos', $id_evento)
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

	/*
	 *  Busca um evento diverso
	 */
    public function get_valor_eventos($id_evento)
    {
        $query = $this->db
                      ->select('valor')
                      ->where('id_eventos', $id_evento)
                      ->get($this->eventos);
        
        if($query->num_rows() > 0)
        {
            return $query->row()->valor;
        }
	    else
	    {
            return array();
        }
    } 
    
    /*
     *  Busca os dados totais para bilheteria 
     */    
    public function get_total_bilheteria_dia($id_Evento)
    {
        $query = $this->db
                      ->select_sum('Valor')
                      ->select('Tipo_Pagamento')
                      ->where('id_Evento', $id_Evento)
                      ->like('Data_Pagamento', date('Y-m-d'))
                      ->where('Email', 'bilheteria@boladeneve.com')
                      ->where('Status_Pagamento', 2)
                      ->group_by('Tipo_Pagamento', 'ASC')
                      ->get($this->cartao);

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return 0;
        }            
    }    
}

?>