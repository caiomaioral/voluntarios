<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Pedidos_model extends CI_Model {
	
	private $cep           =  'tb_cep'; 
	private $pedidos       =  'tb_pedidos';
	private $pedidos_tmp   =  'tb_pedidos_tmp';
	private $config        =  'tb_config';	
	
    public function __construct()
	{
        parent::__construct();
		
		$this->load->library('SimpleXLSX');
    } 

	/*
	 *  Lista os boletos vazio no grid
	 */
    public function listar_itens_json_vazio()
	{
		$row = array(0, 0, 0, 0, 0, 0);
		
		$output = array("aaData" => array());
		
		return @json_encode($output);
    }	
	
	/*
	 *  Lista os pedidos que ainda não foram compensados
	 */
    public function listar_itens_excel_historic_json($Data)
	{
		// Filtro das datas de inicio e fim
		if($Data['Inicio'] != "" && $Data['Fim'] != "")
		{
			$this->db->where('datetime >=', data_br_to_us($Data['Inicio']));
			$this->db->where('datetime <=', data_br_to_us($Data['Fim']));
		}
					  
		// Executa a query
		$query = $this->db
					  ->order_by('ID', 'ASC')
					  ->get($this->pedidos);	
									
        // Conta as linhas para o JSON
		if($query->num_rows() > 0)
		{
			$output = array("aaData" => array());

			foreach ($query->result() as $aRow)
			{
				$row = array($aRow->ID, $aRow->delivery, mb_strtoupper($aRow->customer_name), formataCEP($aRow->postalcode), mb_strtoupper($aRow->address1) . ', ' . $aRow->number, 'Status ' . $aRow->statusMessageGeolocation, 'Match ' . $aRow->matchQuality, 'Status ' . $aRow->statusMessageBooking);
				
				$output['aaData'][] = $row;
			}
            
			return @json_encode($output);
        }
		else
		{
			$row = array(0, 0, 0, 0, 0, 0);
			
			$output = array("aaData" => array());
			
			return @json_encode($output);
        }
    }

	/*
	 *  Lista os pedidos que ainda não foram compensados
	 */
    public function listar_itens_excel_json()
	{
		// EXECUTA a query
		$query = $this->db
					  ->order_by('ID', 'ASC')
					  ->get($this->pedidos_tmp);	
									
        // CONTA as linhas para o JSON
		if($query->num_rows() > 0)
		{
			$output = array("aaData" => array());

			foreach ($query->result() as $aRow)
			{
				$row = array($aRow->ID, $aRow->delivery, mb_strtoupper($aRow->customer_name), formataCEP($aRow->postalcode), mb_strtoupper($aRow->address1), mb_strtoupper($aRow->city));
				
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
    public function set_file_all($File)
	{
		$data = array();
		
		/*
		 *  Instancia do primeiro excel
		 */		
		$xlsx = new SimpleXLSX( $File['tmp_name'] );
		
		list($cols,) = $xlsx->dimension();

		$numero = 1;
		
		foreach( $xlsx->rows() as $k => $r )
		{	
			if($k == 0) continue;
			
			$Data['activityName']     =   'order_' . date('Ymd') . '_' . $numero;
			$Data['delivery']         =   $r[0];
			$Data['serviceArea']      =   $r[5];
			$Data['customer_name']    =   $r[1];
			$Data['postalcode']       =   $r[17];
			$Data['address1']         =   $r[2];
			$Data['complemento']      =   $r[19];
			$Data['number']           =   $r[18];
			$Data['city']             =   $r[3];
			$Data['weight_value']     =   $r[6];
			$Data['volume_value']     =   $r[7];
			$Data['monetary_value']   =   $r[8];
			$Data['startDateTime1']   =   trim($r[9]);
			$Data['endDateTime1']     =   trim($r[10]);
			$Data['startDateTime2']   =   (trim($r[11]) == "")? "NULL" : trim($r[11]);
			$Data['endDateTime2']     =   (trim($r[12]) == "")? "NULL" : trim($r[12]);
			$Data['startDateTime3']   =   (trim($r[13]) == "")? "NULL" : trim($r[13]);
			$Data['endDateTime3']     =   (trim($r[14]) == "")? "NULL" : trim($r[14]);
			$Data['startDateTime4']   =   (trim($r[15]) == "")? "NULL" : trim($r[15]);
			$Data['endDateTime4']     =   (trim($r[16]) == "")? "NULL" : trim($r[16]);
			$Data['typeGood']         =   trim($r[20]);
			$Data['carrier']          =   trim($r[21]);
			$Data['datetime']         =   date('Y-m-d');
			
			$this->set_pedidos_tmp($Data);
			
			if($this->check_duplicidade($Data['delivery']))
			{
				$this->set_pedidos($Data);
			}
			else
			{
				$this->update_pedidos($Data);
			}
			
			$numero++;
		}
	}

	/*
	 *  Inclui os dados no banco
	 */
    public function set_pedidos($dados)
	{
		return $this->db->insert($this->pedidos, $dados);
    }

	/*
	 *  Inclui os dados no banco
	 */
    public function set_pedidos_tmp($dados)
	{
		return $this->db->insert($this->pedidos_tmp, $dados);
    }
	
	/*
	 *  Altera os dados do pedido
	 */
    public function update_pedidos($dados)
	{
		return $this->db
                    ->where('delivery', $dados['delivery'])
                    ->update($this->pedidos, $dados);
    }

	/*
	 *  Altera os dados do pedido
	 */
    public function update_pedidos_tmp($dados)
	{
		return $this->db
                    ->where('delivery', $dados['delivery'])
                    ->update($this->pedidos_tmp, $dados);
    }
	
	/*
	 *  Deleta geral a temporária
	 */
    public function truncate_tmp()
	{
		return $this->db->truncate($this->pedidos_tmp); 
    }	
	
	/*
	 *  Buscar os CEPS da base
	 */
	public function Get_CEP_DB($CEP)
	{
        $CEP = formataCEP($CEP);
		
		$query = $this->db
					  ->where('CEP', $CEP)
                      ->get($this->cep);
        
        if($query->num_rows() > 0)
		{
			foreach ($query->result() as $aRow)
			{
				return $dados = array('cidade' => trim($aRow->Localidade), 'logradouro' => $aRow->LOGRADOURO . ' ' . $aRow->Nome);
			}
		}
		else
		{
			return false;
		}
	}

	/*
	 *  Buscar os CEPS do serviço
	 */
	public function Get_CEP_SL($CEP)
	{
		$dataCep = array(
			'cep'  => $CEP
		);	
		
		$service_url = 'http://localhost/PHP/correios-cep-master/?' . http_build_query($dataCep);
	
		$curl = curl_init($service_url);
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
		$curl_response = curl_exec($curl);
		
		$geolocation = json_decode($curl_response);
		
		foreach($geolocation as $sEndereco)
		{
			$arrcidade     = trim($sEndereco->cidade);
			$arrlogradouro = explode("-", $sEndereco->logradouro);
			
			return $dados = array('cidade' => trim($arrcidade), 'logradouro' => trim($arrlogradouro[0]));
		}
	}
	
	/*
	 *  Retorna os numeros dos pedidos
	 */
    public function GetPedidosTmp()
	{
       $query = $this->db
	   				 ->order_by('ID', 'ASC')
                     ->get($this->pedidos_tmp);

		if($query->num_rows() > 0)
		{
			return $query->result();
        }
    }
	
	/*
	 *  Retorna os numeros dos pedidos para reenvio
	 */
    public function GetPedidos($Delivery)
	{
       $query = $this->db
	   				 ->where('delivery', $Delivery)
                     ->get($this->pedidos);

		if($query->num_rows() > 0)
		{
			return $query->result();
        }
    }	
	
	/*
	 *  Retorna os numeros dos pedidos
	 */
    public function GetConfiguration()
	{
       $query = $this->db
                     ->get($this->config);

		if($query->num_rows() > 0)
		{
			return $query->row();
        }
    }	

	/*
	 *  Retorna os numeros atuais dos pedidos
	 */
    public function count_atual_tmp()
	{
        $query = $this->db
	   				  ->where('statusMessageBooking !=', '')
                      ->get($this->pedidos_tmp);

		if($query->num_rows() > 0)
		{
			return $query->num_rows();
        }
		else
		{
			return 0;
		}
    }	

	/*
	 *  Retorna os numeros totais dos pedidos
	 */
    public function count_total_tmp()
	{
       $query = $this->db
                     ->get($this->pedidos_tmp);

		if($query->num_rows() > 0)
		{
			return $query->num_rows();
        }
		else
		{
			return 0;
		}
	}

	/*
	 *  Retorna os campos do pedidos
	 */
	public function getDados($id)
	{
       $query = $this->db
                     ->where('ID', $id)
					 ->get($this->pedidos);

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
	 *  Retorna os valores de configurações
	 */
	public function getConfig()
	{
       $query = $this->db->get($this->config);

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
	 *  Altera os dados do config
	 */
    public function update_config($dados)
	{
		return $this->db
                    ->where('ID', 1)
                    ->update($this->config, $dados);
    }

	/*
	 *  Verifica se existem duplicidades
	 */
	public function check_duplicidade($dados)
	{
        $query = $this->db
					  ->where('delivery', $dados)
					  ->get($this->pedidos);
        
        if($query->num_rows() > 0)
		{
			return false;
        }
		else
		{
            return true;
        }
	}	
}

?>