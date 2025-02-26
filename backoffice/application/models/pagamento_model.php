<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Pagamento_model extends CI_Model {
	
    private $atletas       =    'tb_atletas';
    private $pagamentos    =    'tb_pagamentos';
    private $caixa         =    'tb_caixa';
    private $tipo          =    'tb_tipo_pagamentos';
    
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Busca os dados do atleta
    //
    public function get_pagamentos_por_atleta($IdAtleta)
    {
        $query = $this->db
                      ->select('b.Id, b.Valor, a.Periodo, b.Pago')
                      ->where('a.Id = b.Exercicio')
                      ->where('b.IdAtleta', $IdAtleta)
                      ->where('b.Ano', date('Y'))
                      ->get($this->tipo . ' a, ' . $this->pagamentos . ' b');
        
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
	    else
	    {
            return array();
        }
    }

    //
    // Busca os dados gerais de pagamento
    //
    public function get_pagamentos_geral($Year, $Filtros)
    {
        if($Filtros['Natureza'] <> '')
        {
            $this->db->where('a.NaturezaOperacao', $Filtros['Natureza']);
        } 
        
        if($Filtros['Periodo'] > 0)
        {
            $this->db->where('a.Exercicio', $Filtros['Periodo']);
        }

        if($Filtros['Pendentes'] == 1)
        {
            $this->db->where('a.Pago', 1);
        } 

        if($Filtros['Pendentes'] == 2)
        {
            $this->db->where('a.Pago', 0);
        } 

        $query = $this->db
                      ->select('a.Id, a.IdAtleta, a.Descricao, a.NaturezaOperacao, b.Nome, a.Valor, c.Periodo, a.Pago')
                      ->from($this->pagamentos . ' a')
                      ->join($this->atletas . ' b', 'a.IdAtleta = b.Id', 'left')
                      ->join($this->tipo . ' c', 'a.Exercicio = c.Id', 'left')
                      ->where('a.Pago', 1)
                      ->where('a.Ano', $Year)
                      ->order_by('a.Exercicio, b.Id', 'DESC')
                      ->get();
        
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
	    else
	    {
            return array();
        }
    }    

    //
    // Busca os dados gerais de pagamento
    //
    public function get_pagamentos_extras($IdPagamento)
    {
        $query = $this->db
                      ->where('Id', $IdPagamento)
                      ->get($this->pagamentos);
        
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
    // Baixa no pagamento
    //
    public function update_pagamento($Data)
    {
        return $this->db
                    ->where('Id', $Data['Id'])
                    ->update($this->pagamentos, $Data);
    }
    
    //
    // Busca todos os atletas ativos
    //
    public function get_all_players()
    {
        $query = $this->db
                      ->where('Inativo', 0)
                      ->where('Posicao !=', 'Goleiro')
                      ->order_by('Id', 'ASC')
                      ->get($this->atletas);
        
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
	    else
	    {
            return array();
        }
    }

    //
    // Busca os tipos de pagamento
    //
    public function get_all_tipos()
    {
        $query = $this->db
                      ->order_by('Id', 'ASC')
                      ->get($this->tipo);
        
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
	    else
	    {
            return array();
        }
    }
    
    //
    // Incluir o pagamento
    //
    public function insert_pagamento($Data)
    {
        return $this->db->insert($this->pagamentos, $Data);
    }

    //
    // Excluir o pagamento
    //
    public function excluir_pagamento($IdPagamento)
    {
        $this->db->where('Id', $IdPagamento)->delete($this->pagamentos);
    }

    //
    // Checa fluxo de caixa
    //
    public function check_flow()
    {
        $query = $this->db
                      ->where('Ano', date('Y') - 1)
                      ->get($this->caixa);
        
        if($query->num_rows() > 0)
        {
            return $query->row()->Fluxo;
        }
	    else
	    {
            return 0;
        }
    }    
    
    //
    // Mostra por ID os pagamentos feitos
    //
    public function Widget_Payment($IdAtleta)
    {
        $htmlBody  =  '';
        
        if($this->session->userdata('Posicao') == 'GOLEIRO')
        {
            $htmlBody .= '<p class="mt-3"><strong>GOLEIRO é isento de pagamento obrigatório.</strong></p>';
        }
        else
        {
            $htmlBody .= 
            
                '<div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <table class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">Período</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Status</th>
                                </tr>
                                </thead>';

                            foreach($this->get_pagamentos_por_atleta($IdAtleta) as $sPagamento): 
                                
                                $Status = ($sPagamento->Pago == 1)? '<strong>Pago</strong>' : 'Pendente';

                                $htmlBody .= 
                                    
                                    '<tr>
                                        <td>'.$sPagamento->Periodo.'</td>
                                        <td>R$ '.num_to_user($sPagamento->Valor).'</td>
                                        <td>'.$Status.'</td>
                                    </tr>';
                            
                            endforeach;
                            
                            $htmlBody .= '</tbody></table>

                        </div>
                    </div>
                </div>
            </div>';
        }

        echo $htmlBody;
    }    

    //
    // Mostra por ID os pagamentos feitos
    //
    public function Widget_General_Payment($Year)
    {
        $Filtros['Natureza']   =  $this->input->post('Natureza');
        $Filtros['Periodo']    =  $this->input->post('Periodo');
        $Filtros['Pendentes']  =  $this->input->post('Pendentes');

        $htmlBody  =  '';
        
        $htmlBody .= 
        
            '<div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <h5 class="mb-3">Extrato de Debito e Credito - Exercício de '.date('Y').'</h5>

                            <table class="table table-striped table-dark table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Ações</th> 
                                <th scope="col">Tipo Pagamento</th>        
                                <th scope="col">Período</th>
                                <th scope="col">Natureza</th>
                                <th scope="col">Valor</th>
                            </tr>';
                        
                        $CreditosValorTotal  =  0;
                        $RecebiveisValorTotal = 0;
                        $DebitosValorTotal  =  0;

                        foreach($this->get_pagamentos_geral($Year, $Filtros) as $sPagamento):

                            $Legenda     =  ($sPagamento->IdAtleta <> 0)? $sPagamento->Nome : $sPagamento->Descricao . ' - Mensalidade';
                            $Natureza    =  ($sPagamento->NaturezaOperacao == 'C')? 'Credito' : 'Debito';
                            $Status      =  ($sPagamento->Pago == 1)? 'Pago' : 'Pendente';
                            $Periodo     =  (is_null($sPagamento->Periodo))? '-' : $sPagamento->Periodo;
                            $Links       =  ($sPagamento->IdAtleta <> 0)? '<a id="pagamento" href="/pyngaiada/pagamento/quitar/'.$sPagamento->IdAtleta.'" title="Efetuar Pagamento"><img src="assets/images/tools.png" width="17" height="17"></a>' : '<a title="Excluir Lançamento" href="'.$sPagamento->Id.'" data-id="'.$sPagamento->Id.'" data-toggle="modal" data-target="#confirm-delete"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="financeiro/alterar_lancamento/'.$sPagamento->Id.'" title="Alterar Lançamento"><img src="assets/images/pencil.gif" width="17" height="17"></a>';

                            if($sPagamento->NaturezaOperacao == 'C' && $sPagamento->Pago == 1)
                            {
                                $Tipo  =  'bg-success';
                            }
                            else
                            {
                                $Tipo  =  'bg-warning';
                            }
                            
                            if($sPagamento->NaturezaOperacao == 'D')
                            {
                                $Tipo  =  'bg-danger';
                            }

                            $htmlBody .= 
                                
                                '<tr class="'.$Tipo.'">
                                    <td class="bg-light text-center">'.$Links.'</td>    
                                    <td>'.$Legenda.'</td>
                                    <td>'.$sPagamento->Periodo.'</td>
                                    <td>'.$Natureza.'</td>
                                    <td>R$ '.num_to_user($sPagamento->Valor).'</td>
                                </tr>';

                            $CreditosValorTotal    += ($sPagamento->NaturezaOperacao == 'C' && $sPagamento->Pago == 1)? $sPagamento->Valor : 0;
                            $DebitosValorTotal     += ($sPagamento->NaturezaOperacao == 'D')? $sPagamento->Valor : 0;

                        endforeach;

                        $FluxoDeCaixa = $this->check_flow();
                        
                        $htmlBody .= '</tbody></table>

                    </div>';

                    $FluxoDeCaixa = ($FluxoDeCaixa == 0)? $CreditosValorTotal - $DebitosValorTotal : $FluxoDeCaixa + ($CreditosValorTotal - $DebitosValorTotal);
                    
                    $htmlBody .= '<span class="badge badge-success"><h3>Creditos: R$ '.num_to_user($CreditosValorTotal).'</h3></span>
                                  <span class="badge badge-danger"><h3>Debitos: R$ '.num_to_user($DebitosValorTotal).'</h3></span>
                                  <span class="badge badge-dark"><h3>Fluxo de Caixa: R$ '.num_to_user($FluxoDeCaixa).'</h3></span>';
    $htmlBody .= '</div>
            </div>
        </div>';
        
        echo $htmlBody;
    }    
}

?>