<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Participantes_model extends CI_Model {
	
    private $eventos        =  'tbl_eventos_links';
    private $inscricao      =  'tbl_participantes';
    private $cartao         =  'tbl_pagamentos';
	
    public function __construct()
    {
        parent::__construct();
    } 

    /*
     *  Atualiza o registro do cartão na hora da compra
     */
    public function update_payment($Data)
    {
        return $this->db
                    ->where('pedido', $Data['order_number'])
                    ->update($this->cartao, $Data);
    }
	
    /*
     *  Atualiza somente o status na hora da mudança
     */
    public function update_status($Data, $Check)
    {
        return $this->db
                    ->where('checkout_cielo_order_number', $Check['checkout_cielo_order_number'])
                    ->update($this->cartao, $Data);
    }

    /*
     *  Atualizar os itens adquiridos (cadeiras)
     */
    public function update_cart($Data)
    {
        return $this->db
                    ->where('id_evento', $Data['id_evento'])
                    ->where('id_inscrito', $Data['id_inscrito'])
                    ->update($this->carrinho, array('pago' => $Data['pago']));
    }    

    /*
     *  Verifica se a mesma URL já é existente
     */
    public function get_url($URL)
    {
        $query = $this->db
                      ->where('url', $URL)
                      ->where('status', 1)
                      ->get($this->eventos);          
        
        if($query->num_rows() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }   
    
    /*
     *  Retorna o nome do evento
     */
    public function get_name($Order_number)
    {
        $query = $this->db
                      ->select('t3.titulo')
                      ->where('t1.pedido', $Order_number)
                      ->where('t1.id_inscrito = t2.id_Inscrito')
                      ->where('t2.ID_Evento = t3.id_eventos')
                      ->get($this->cartao . ' t1, ' . $this->inscricao . ' t2, ' . $this->eventos . ' t3');          
        
        if($query->num_rows() > 0)
        {
            return $query->row()->titulo;
        }
        else
        {
            return 'Bola de Neve';
        }
    }
    
    /*
     *  Retorna o nome do evento
     */
    public function get_id_dados_general($Order_number)
    {
        $query = $this->db
                      ->select('t1.id_inscrito, t2.int_Proprio AS presenca, t2.id_Evento, t3.tipo_evento, t3.titulo, t3.alerta')
                      ->where('t1.pedido', $Order_number)
                      ->where('t1.id_inscrito = t2.id_Inscrito')
                      ->where('t2.ID_Evento = t3.id_eventos')
                      ->get($this->cartao . ' t1, ' . $this->inscricao . ' t2, ' . $this->eventos . ' t3');          
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 'Bola de Neve';
        }
    } 
    
    /*
     *  Retorna o nome do evento
     */
    public function get_id_dados_general_checkout($checkout_cielo_order_number)
    {
        $query = $this->db
                      ->select('t1.id_inscrito, t2.id_Evento, t3.tipo_evento, t3.titulo')
                      ->where('t1.checkout_cielo_order_number', $checkout_cielo_order_number)
                      ->where('t1.id_inscrito = t2.id_Inscrito')
                      ->where('t2.ID_Evento = t3.id_eventos')
                      ->get($this->cartao . ' t1, ' . $this->inscricao . ' t2, ' . $this->eventos . ' t3');          
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 'Bola de Neve';
        }
    }     

    /*
     *  Busca um evento diverso
     */
    public function get_acompanhantes($order)
    {
        $query = $this->db
                      ->select('id_inscrito')
                      ->where('order_number', $order)
                      ->get($this->cartao);
					  
        if($query->num_rows() > 0)
        {
            $query2 = $this->db
                           ->select('str_Nome, str_CPF')
                           ->where('id_Inscrito', $query->row()->id_inscrito)
                           ->get($this->dependentes);			
			
            if($query2->num_rows() > 0)
            {
                return $query2->result();
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }					  
    }

    /*
     *  Busca as cadeiras adquiridas pelo comprador
     */
    public function get_cadeiras($order)
    {
        $query = $this->db
                      ->select('id_inscrito')
                      ->where('order_number', $order)
                      ->get($this->cartao);
					  
        if($query->num_rows() > 0)
        {
            $query2 = $this->db
                           ->select('str_setor, str_cadeira, valor_cadeira')
                           ->where('id_inscrito', $query->row()->id_inscrito)
                           ->get($this->carrinho);			
			
            if($query2->num_rows() > 0)
            {
                return $query2->result();
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }					  
    }    

    /*
     *  Busca os cartoes por usuario
     */
    public function get_cartao_by_status($checkout_cielo_order_number)
    {
        $query = $this->db
                      ->where('checkout_cielo_order_number', $checkout_cielo_order_number)
                      ->get($this->cartao);
        
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
    //  Busca os dados do cartão para exibir ao usuário
    //
    public function get_dados_card($pedido)
    {
        $query = $this->db
                      ->where('order_number', $pedido)
                      ->get($this->cartao);
        
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
     *  Busca os cartoes por id de inscrito
     */
    public function get_cartao_by_id($id_inscrito)
    {
        $query = $this->db
                      ->where('id_inscrito', $id_inscrito)
                      ->get($this->cartao);
        
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
    public function get_count_inscritos_by_evento($id_Evento)
    {
        $this->db->select('t1.str_Nome, t1.str_CPF, t1.int_Proprio, (SELECT COUNT(id_Inscrito) FROM tbl_dependentes_conferencia b WHERE b.id_Inscrito = t1.id_Inscrito) AS int_Dependentes');
        $this->db->where('t3.payment_status', 2);
        $this->db->order_by('t3.customer_name', 'ASC');
        $this->db->where('t1.id_Evento', $id_Evento);
        
        $query = $this->db
                      ->from('tbl_inscricao_conferencia t1')
                      ->join('tbl_cartao t3', 't1.id_Inscrito = t3.id_Inscrito')
                      ->get();

        if($query->num_rows() > 0)
        {
            $x = 0;
            
            foreach($query->result() as $pessoas)
            {
                if($pessoas->int_Proprio == 1)
                {
                    $x++;
                }
                if($pessoas->int_Dependentes > 0)
                {
                    $x += $pessoas->int_Dependentes;
                }               
            }
            
            return $x;
        }
        else
        {
            return 0;
        }
    }    
    
    /*
     *  Inclui os dados no banco
     */
    public function set_admin_eventos($dados)
    {
        return $this->db->insert($this->admin_eventos, $dados);
    }

    /*
     *  Altera os dados do conteudo
     */
    public function update_eventos_forever_fake($dados)
    {
        return $this->db
                    ->where('id_eventos', $dados['id_eventos'])
                    ->update($this->eventos, $dados);
    }    
	
    /*
     *  Excluir o cara de vez e dane-se
     */
    public function excluir($id)
    {
        $query = $this->db
                      ->where('id_Evento', $id)
                      ->get($this->inscricao);
        
        if($query->num_rows() == 0)
        {
            $this->db->where('id_eventos', $id)->delete($this->eventos);
            $this->db->where('ID_Evento', $id)->delete($this->admin_eventos);
        }

        return true;
    }
	
    /*
     *  Altera os dados na tabela eventos
     */
    public function ativar_eventos($dados)
    {
        return $this->db
                    ->where('id_eventos', $dados['id_eventos'])
                    ->update($this->eventos, $dados);
    }

    /*
     *  Altera os dados na tabela eventos
     */
    public function inativar_eventos($dados)
    {
        return $this->db
                    ->where('id_eventos', $dados['id_eventos'])
                    ->update($this->eventos, $dados);
    }	

    //
    // Busca um inscrito
    //
    public function get_inscrito($id)
    {
        $query = $this->db
                      ->where('Id_Inscrito', $id)
                      ->get($this->inscricao);
        
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
    // Busca os seus dependentes
    //
    public function get_pedido($order)
    {
        $query = $this->db
                      ->where('Pedido', $order)
                      ->get($this->cartao);
        
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
     *  Busca os seus dependentes por ID
     */    
    public function get_dependentes_by_id($id)
    {
        $query = $this->db
                      ->where('Id_Dependente', $id)
                      ->get($this->dependentes);
        
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return 0;
        }        
    }

    //
    // Excluir o inscrito e o dependente
    //
    public function excluir_geral($id)
    {
        $this->db->where('id_Inscrito', $id)->delete($this->inscricao);

        return true;
    } 

    /*
     *  Atualiza os dados de cartão de crédito
     */
    public function atualizar_pagamento($id, $valor)
    {
        return $this->db
                    ->where('id_inscrito', $id)
                    ->update($this->cartao, $valor);
    } 
    
    //
    // Atualiza os dados do inscrito
    //
    public function update_inscrito($data)
    {
        return $this->db
                    ->where('id_Inscrito', $data['id_Inscrito'])
                    ->update($this->inscricao, $data);
    } 

    /*
     *  Atualiza os dados do dependente
     */
    public function update_dependente($data)
    {
        return $this->db
                    ->where('id_Dependente', $data['id_Dependente'])
                    ->update($this->dependentes, $data);
    }     
    
    /*
     *  Atualiza os dados de cartão
     */
    public function update_pagamento($data)
    {
        return $this->db
                    ->where('id_inscrito', $data['id_inscrito'])
                    ->update($this->cartao, $data);
    } 
    
    //
    // Verifica se existem duplicidades
    //
    public function check_duplicidade_evento_validation($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['ID'])
                      ->where('CPF', $dados['CPF'])
                      ->where('Pago IS NOT NULL')
                      ->get($this->inscricao);

        if($query->num_rows() > 1)
        {
            // Tem duplicidade e não pode continuar
            return 1;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return 0;
        }
    } 
    
    //
    // Verifica se existem duplicidades
    //
    public function check_duplicidade_evento_validation_v2($dados)
    {
        $query = $this->db
                      ->where('id_Evento', $dados['ID'])
                      ->where('CPF', $dados['CPF'])
                      ->where('Pago', 1)
                      ->get($this->inscricao);

        if($query->num_rows() == 1)
        {
            // Tem duplicidade e não pode continuar
            return 1;
        }
        else
        {
            // Não existe duplicidade e pode continuar
            return 0;
        }
    }     
}

?>