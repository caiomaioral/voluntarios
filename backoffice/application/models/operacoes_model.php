<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Operacoes_model extends CI_Model {

    private $tableCaixa          = "tb_caixa";
    private $tableCaixaMensal    = "tb_caixa_mensal";
	private $kardex			     = "tb_kardex";
	private $estudantes		     = "tb_estudantes";
	private $boletos			 = "tb_boletos";
	private $igrejas             = "tb_igrejas";
    
    public function __construct()
	{
        parent::__construct();
    }

    // Verifica se o caixa já foi aberto    
    public function cash_flow_open()
	{
		return  $this->db
					 ->where('idUsuario', $this->session->userdata('idUsuario'))
				     ->where('intSituacao', 0)
				     ->get($this->tableCaixa)
				     ->row(); 
    }
    
    // Efetua a abertura do caixa      
    public function open_cash_flow($dblValor)
	{
        // Retorna o numero de caixa mensal
		$idMovimentacao = $this->getMonthlyCash()->idMovimentacao;
        
        // Utiliza a função do Help Many para a conversão de moedas.
        $openFlow = array( 	 	 	
	                        'idCaixaMensal'     =>  $idMovimentacao,			 	 	 	 	 	 	
	                        'idUsuario'         =>  $this->session->userdata('idUsuario'), 	 	 	 	
	                        'dtCulto'		    =>  date('Y-m-d'), 	 	 	 	 	 	
	                        'dblValorAbertura'	=>  num_to_db($dblValor),	 	 	 	 	 	 	
	                        'intSituacao'		=>  0, 	 	 	 	 	 	
	                        'intCompensado'     =>  0
	                     );
	                
		return $this->db->insert($this->tableCaixa, $openFlow);
    }
    
    // Retorna o nome da igreja em que a abertura foi feita
    public function getChurch($idIgreja)
	{
		$query = $this->db
					 ->where('Id', $idIgreja)
					 ->get($this->igrejas);
	
		if($query->num_rows() > 0)
		{
			return $query->row()->Nome;
		}
		else
		{
			return 'NÃO LOCALIZADO';
		}
    }

    // Retorna o NN
    public function getNN($idKardex)
	{
       $query = $this->db
                     ->where('Id', $idKardex)
                     ->get($this->boletos);

        if($query->num_rows() > 0)
		{
            return $query->row()->NossoNumero;
        }
		else
		{
            return 'NÃO LOCALIZADO';
        }
    }

    // Extrato de vendas pela operação
    public function getStatement()
	{
       $id = $this->cash_flow_open()->idCaixa;
     
       $query = $this->db
                     ->select('tb_kardex.idKardex, tb_natureza_operacao.strDescricao, tb_kardex.flValor')
                     ->where('tb_kardex.intCancelado = 0' )
                     ->where('tb_kardex.idNatureza', VENDA )
                     ->where('tb_kardex.idCaixa',$id )
					 ->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
					 ->order_by('tb_kardex.idKardex', 'DESC')
                     ->get('tb_kardex, tb_natureza_operacao');
                
		if($query->num_rows() > 0)
		{
			$output = array(
				"aaData" => array()
			);

			foreach ($query->result() as $aRow)
			{
			    $f = (
			             $aRow->intFormaPagto == DINHEIRO ? "Dinheiro" : 
			             (
			                 $aRow->intFormaPagto == DEBITO ? "Cartão de Débito" : 
			                 (
			                    $aRow->intFormaPagto == CREDITO ? "Cartão de Crédito" : "Cheque")));

				$row = array('<span>
				                <a id="detalhes" href="javascript:fnDetails('.$aRow->idKardex.')">
				                    <img src="'.base_url().'assets/images/ico_zoom.gif" width="17" height="17" title="Resumo: Detalhes" />
				                </a>
				              </span>
        				      <span>
        				        <a id="deletar" href="javascript:fnDelete('.$aRow->idKardex.')">
        				            <img src="'.base_url().'assets/images/nao_checado.gif" width="17" height="17" title="Resumo: Excluir" />
        				        </a>
        				      </span>'.
				                str_pad($aRow->idKardex, 10, "0", STR_PAD_LEFT), 
				                $aRow->strDescricao, 
				                $f, 
				                'R$ ' . num_to_user($aRow->flValor));
				
				
				$output['aaData'][] = $row;
			}
            
			return json_encode($output); 
        }
		else
		{
			$row = array(0, 0, 0, 0, 0);
			
			$output = array(
				"aaData" => array()
			);
			
			return json_encode($output);
        }
    }
    
    // Extrato SOMADO de vendas pela operação
    public function getSumStatement()
	{
       $id = $this->cash_flow_open()->idCaixa;
       
	   $sum = array();
       
       $query = $this->db
                     ->select('tb_kardex.idKardex, tb_natureza_operacao.strDescricao, tb_kardex.flValor')
                     ->where('tb_kardex.intCancelado = 0' )
                     ->where('tb_kardex.idNatureza', VENDA )
                     ->where('tb_kardex.idCaixa',$id )
					 ->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
                     ->get('tb_kardex, tb_natureza_operacao');
                
		if($query->num_rows() > 0)
		{
		    $sum = array(DINHEIRO => 0, DEBITO  => 0, CREDITO => 0,CHEQUE => 0, 'TOTAL' => 0);
			    
			foreach ($query->result() as $aRow)
			{
			    if($aRow->intFormaPagto == DINHEIRO)
				{
			        $sum[DINHEIRO] += $aRow->flValor;
			    }
				elseif($aRow->intFormaPagto == DEBITO)
				{
			        $sum[DEBITO] += $aRow->flValor;
			    }
				elseif($aRow->intFormaPagto == CREDITO)
				{
			        $sum[CREDITO] += $aRow->flValor;
			    }
				else
				{
			        $sum[CHEQUE] += $aRow->flValor;
			    }
			    
			    $sum['TOTAL'] += $aRow->flValor;
			}
        }
        
		return $sum; 
    }

    // Retorna informações sobre o kardex
    function getKardex($idKardex)
	{
       return $this->db
                   ->where('idkardex', $idKardex)
                   ->get('tb_kardex')
                   ->row();
    }
   
    // Retorna informações sobre os itens kardex
    function getItensKardex($idKardex)
	{
       return $this->db
                   ->select('
							 tb_produtos.idProduto, 
							 tb_produtos.strDescricao, 
							 tb_item_kardex.intQuantidade, 
							 tb_item_kardex.dblValorUnitario,
							 tb_item_kardex.dblValorUnitario * tb_item_kardex.intQuantidade AS dblValorTotal 
                            ')
                   ->where('idkardex', $idKardex)
                   ->where('tb_item_kardex.idProduto = tb_produtos.idProduto')
                   ->get('tb_item_kardex, tb_produtos')
                   ->result();
    }
    
    // Inabiliza o Kardex
    function deleteKardexSell($idKardex)
	{
        // Invoca o model de operações
		$this->load->model('produtos_model');
		
        $products  = $this->getItensKardex($idKardex);
        
        foreach($products as $product):
            
			$this->produtos_model->fix_stock($product->idProduto, $product->intQuantidade, ENTRADA);
			
        endforeach;
        
      	$this->db
		     ->where('idKardex', $idKardex)
      	     ->update('tb_kardex', array('intCancelado' => 1)); 
    }
    
    // Extrato de vendas pela operação para impressão
    public function getStatementImpressPDF($idCaixaMensal, $dtInicio, $dtFim)
	{
       	return $this->db
                    ->select('tb_kardex.idKardex, 
                              CONCAT_WS(" - ", tb_natureza_operacao.strDescricao, tb_kardex.strDescricao) AS strDescricao,
                              tb_caixa_mensal.flValorAnterior,
						      tb_kardex.flValor,
							  tb_kardex.dtOperacao,
							  tb_kardex.idBoleto,
                              tb_natureza_operacao.idNatureza,
                              tb_natureza_operacao.intTipo', FALSE)
                    ->where('tb_kardex.idCaixaMensal = tb_caixa_mensal.idMovimentacao')
					->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
					->where('tb_kardex.intCancelado = 0')
                    ->where('tb_kardex.idCaixaMensal', $idCaixaMensal)
				    ->where('tb_kardex.dtOperacao >=', $dtInicio)
				    ->where('tb_kardex.dtOperacao <=', $dtFim)
                    ->get('tb_kardex, tb_natureza_operacao, tb_caixa_mensal')
					->result();
    }

    // Extrato de vendas pela operação para impressão
    public function getStatementImpress($idCaixaMensal)
	{
       	return $this->db
                    ->select('tb_kardex.idKardex, 
                              CONCAT_WS(" - ", tb_natureza_operacao.strDescricao, tb_kardex.strDescricao) AS strDescricao,
                              tb_caixa_mensal.flValorAnterior,
						      tb_kardex.flValor,
							  tb_kardex.dtOperacao,
							  tb_kardex.idBoleto,
                              tb_natureza_operacao.idNatureza,
                              tb_natureza_operacao.intTipo', FALSE)
                    ->where('tb_kardex.idCaixaMensal = tb_caixa_mensal.idMovimentacao')
					->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
					->where('tb_kardex.intCancelado = 0')
                    ->where('tb_kardex.idCaixaMensal', $idCaixaMensal)
                    ->get('tb_kardex, tb_natureza_operacao, tb_caixa_mensal')
					->result();
    }
 
    // Extrato Financeiro Mensal
    public function getStatementMonthly()
	{
       $idCaixaMensal = $this->getMonthlyCash()->idMovimentacao;
       
       $query = $this->db
                     ->select('tb_natureza_operacao.idNatureza,
                               tb_natureza_operacao.strDescricao,
							   CONCAT_WS(" - ", tb_natureza_operacao.strDescricao, tb_kardex.strDescricao) AS strDescricao,
                               tb_natureza_operacao.intTipo,
							   tb_kardex.idKardex,
							   tb_kardex.idBoleto,
                               tb_kardex.flValor,
							   tb_kardex.dtOperacao,
                               tb_kardex.intCompensado', FALSE)
                     ->where('tb_kardex.idCaixaMensal = tb_caixa_mensal.idMovimentacao')
					 ->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
					 ->where('tb_kardex.intCancelado = 0')
                     ->where('tb_kardex.idCaixaMensal', $idCaixaMensal)
                     ->order_by('tb_kardex.dtOperacao', 'ASC')
					 ->get('tb_kardex, tb_natureza_operacao, tb_caixa_mensal');

		if($query->num_rows() > 0)
		{
			$output = array( "aaData" => array() );

			foreach ($query->result() as $aRow)
			{
			    /* Verifica se a movimentação se trata de uma venda. Para fins de exibição. */
			    if($aRow->idNatureza == BOLETOS)
				{
                	$f = LimitaStr(deepHigher($this->getNamePeople($aRow->idBoleto)), 50);
					$n = "BDN " . $this->getChurch($this->getIdChurch($aRow->idBoleto));
					$c = 0;
					$m = $this->getNN($aRow->idBoleto);
                }
				else
				{
                    $f = LimitaStr(deepHigher($aRow->strDescricao), 60);
                    $n = "SECRETARIA SEDE";
					/*
                        Caso não for uma venda o flag de compensação não precisa existir,
                        porque, somente as vendas que não foram feitas à dinheiro 
                        necessitam ser compensadas
                    */
                    $c = (int) $aRow->intCompensado;
					$m = "SEM NUMERO";
                }
                
				$row = array(   $aRow->idKardex,
								str_pad($aRow->idKardex, 10, "0", STR_PAD_LEFT),
								$m, 
				                $f,
								$n, 
				                "R$ " . num_to_user($aRow->flValor),
				                ($aRow->intTipo == 0 ?  "CRE" : "DES"),
				                data_us_to_br($aRow->dtOperacao),
				                $c
				            );		
				
				$output['aaData'][] = $row;
			}
        }
		else
		{
			$row = array(0, 0, 0, 0, 0, 0, 0, 0);
			$output = array( "aaData" => array() );
        }

        return json_encode($output); 
    }
    
	// Retorna o nome do cara do boleto
	public function getNamePeople($IdBoleto)
	{
        $query = $this->db
					  ->where('tb_boletos.IdEstudante = tb_estudantes.Id')
					  ->where('tb_boletos.Id', $IdBoleto)
					  ->get($this->estudantes . ', ' . $this->boletos);
        
        if($query->num_rows() > 0)
		{
            return $query->row()->Nome;
        }
		else
		{
            return 'NÃO LOCALIZADO';
        }
	}
	
	// Retorna o id da igreja
	public function getIdChurch($IdBoleto)
	{
		 $query = $this->db
                       ->select('tb_estudantes.IdIgreja')
					   ->where('tb_boletos.IdEstudante = tb_estudantes.Id')
					   ->where('tb_boletos.Id', $IdBoleto)
                       ->get($this->estudantes . ', ' . $this->boletos);

        if($query->num_rows() > 0)
		{
            return $query->row()->IdIgreja;
        }
		else
		{
            return 0;
        }
	}
	
    // Retorna o Id do caixa mensal ativo
    public function getMonthlyCash()
	{
		return $this->db
                    ->where('intEncerrado', 0)
                    ->get($this->tableCaixaMensal)
                    ->row();
    }

    // Retorna o Id do caixa mensal ativo
    public function getMonthlyCashChurch()
	{
		return $this->db
                    ->where('idIgreja', $this->session->userdata('idIgreja'))
                    ->where('intEncerrado', 0)
                    ->get($this->tableCaixaMensal)
                    ->row();
    }
		
	// Retorna o valor do boleto
	public function getPayCash($idBoleto)
	{
		return $this->db
                    ->where('Id', $idBoleto)
                    ->get($this->boletos)
                    ->row()
					->ValorBoleto;
	}
	
    // Extrato SOMADO de vendas pela operação
    public function getSumStatementMonthly()
	{
        $sum = array();
       
        // Retorna o ID do Caixa Mensal
        $idCaixaMensal = $this->getMonthlyCash()->idMovimentacao;
	   
	    // Retorna o valor anterior do ultimo caixa
		$flValorAnterior = $this->getMonthlyCash()->flValorAnterior;

		$sum  =  array(
						'SALDOMESANTERIOR'      => 0,
						'TOTALCREDITOS'         => 0,
						'TOTALDESPESAS'         => 0,
						'SALDOABERTURA'         => 0,
						'SALDOATUAL'            => 0		                    
					  );
       
       $query = $this->db
                     ->select('tb_kardex.idKardex, 
                               CONCAT_WS(" - ", tb_natureza_operacao.strDescricao, tb_kardex.strDescricao) AS strDescricao,
							   tb_kardex.flValor,
                               tb_natureza_operacao.idNatureza,
                               tb_natureza_operacao.intTipo', FALSE)
                     ->where('tb_kardex.idCaixaMensal = tb_caixa_mensal.idMovimentacao')
					 ->where('tb_natureza_operacao.idNatureza = tb_kardex.idNatureza')
					 ->where('tb_kardex.intCancelado = 0')
                     ->where('tb_kardex.idCaixaMensal', $idCaixaMensal)
                     ->get('tb_kardex, tb_natureza_operacao, tb_caixa_mensal');
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $aRow)
			{
				switch ($aRow->idNatureza)
			    {
			        // Calcula os Créditos pelo avido de débito e credito
			    	case DESPESA :
			            $sum['TOTALDESPESAS']  +=  $aRow->flValor;
			            $sum['SALDOATUAL']     -=  $aRow->flValor;
			        break;
			        			        
			        // Calcula os Créditos pelo avido de débito e credito
			        case RECEITA :
			            $sum['TOTALCREDITOS']  +=  $aRow->flValor;
			            $sum['SALDOATUAL']     +=  $aRow->flValor;
			        break;

			        // Calcula os Créditos pelo avido de débito e credito
			        case BOLETOS :
			            $sum['TOTALCREDITOS']  +=  $aRow->flValor;
			            $sum['SALDOATUAL']     +=  $aRow->flValor;
			        break;
				}   		        
			}
		}

		// Adiciona o valor anterior ao valor atual
		$sum['SALDOMESANTERIOR']  +=  $flValorAnterior;
		$sum['SALDOATUAL']        +=  $flValorAnterior;
		
		return $sum; 
    }

    // Fecha o Caixa Mensal
    function closeFlowMounthy($ValorAbertura)
	{
		// Retorna o codigo do caixa Mensal aberto.
        $idMovimentacao = $this->getMonthlyCash()->idMovimentacao;

		// Data final do caixa Aberto
		$dtFinalCaixa = $this->getMonthlyCash()->dtFim;
		
		// Pega o valor total para ser o anterior
		$flValorAnterior = $ValorAbertura;
		
		// Altera o Flag para informar que o caixa esta fechado.
		$this->db->where('idMovimentacao', $idMovimentacao)->update($this->tableCaixaMensal, array('intEncerrado' => 1));   
		   
		// Abre um novo Caixa
		$datetime1 = new DateTime($dtFinalCaixa);
		
		// Adiciona um mes a mais a data | $pData = $datetime1->modify('+1 month');
		$pData = $datetime1->modify('+1 day');
		
		// Retorna o ultimo dia do mes escolhido
		$data = array(
						'idIgreja'      	=> $this->session->userdata['idIgreja'] ,
						'dtInicio'      	=> $pData->format('Y-m-01') ,
						'dtFim'         	=> $pData->format('Y-m-t'),
						'flValorAnterior'   => $flValorAnterior,
						'intEncerrado'  	=> 0
					);

		$this->db->insert($this->tableCaixaMensal, $data); 
		
		return true;
    }
	
	// Efetua o extorno do boleto
	public function returnPayments($idKardex)
	{
		$idBoleto    =  $this->getKardex($idKardex)->idBoleto;
		$idNatureza  =  $this->getKardex($idKardex)->idNatureza;
		
		$this->db->where('idKardex', $idKardex)->delete($this->kardex);

		if($idNatureza == 8)
		{
			$this->db
				 ->where('Id', $idBoleto)
				 ->update($this->boletos, array('Pago' => 0));
		}
		
		return true;		
	}
}