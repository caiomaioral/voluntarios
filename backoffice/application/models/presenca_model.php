<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Presenca_model extends CI_Model {
	
    private $eventos        =  'tbl_eventos';
	private $links          =  'tbl_eventos_links';
    private $cartao         =  'tbl_cartao';
    private $convite        =  'tbl_convite_conferencia';
	private $notification   =  'tbl_notification_tmp';  
	private $convites       =  'tbl_convite_conferencia'; 
	private $participantes  =  'tbl_participantes';  
	
    public function __construct()
	{
        parent::__construct();
	} 
	
	//
	// Efetua o Check-in por CPF
	//
    public function GetParticipantByNumber($Codigo)
	{
		if(is_array($Codigo))
		{
			$query = $this->db
						  ->select('a.Nome, a.CPF, a.Pedido, a.id_Evento, a.id_Evento_Link, a.Presenca, b.titulo')
						  ->where('a.id_Evento_Link = b.id_Evento_Link')
						  ->where('a.CPF', $Codigo[0])	
						  ->where('a.id_Evento', $Codigo[1])
						  ->where('a.Pago', 1)
						  ->get($this->participantes . ' a, ' . $this->links . ' b');

			if($query->num_rows() > 0)
			{
				if($query->row()->Presenca == 1)
				{
					return '<h3 class="Play Normal"><h3 class="Play Normal">Presença já foi registrada para esse Evento.</h3><br />';	
				}
				else
				{
					//
					// Método para pegar os dados do aluno e fazer a confirmação
					//
					$Data['Pedido']               =   $query->row()->Pedido;
					$Data['CPF']                  =   $query->row()->CPF;
					$Data['id_Evento_Link']       =   $query->row()->id_Evento_Link;
					$Data['Presenca']             =   1;
					$Data['Data_Hora_Presenca']   =   date('Y-m-d H:i:s');

					//
					// Persiste no banco de dados
					//
					$this->update_frequency($Data);
					
					return '<h3 class="Play Normal"><h3 class="Play Normal">'.trim($query->row()->Nome).', acesso liberado! <br /><br />Lote: '.$query->row()->titulo.'</h3><br />';
				}
			}
			else
			{
				return '<h3 class="Play Red">Inscrito não encontrado.</h3>';
			}
		}
		else
		{
			return '<h3 class="Play Red">Inscrito não encontrado.</h3>';	
		}
		
		
	}
	
	//
	// Atualiza o banco
	//
    public function update_frequency($data)
	{
		$this->db
		     ->where('Pedido', $data['Pedido'])
		     ->where('CPF', $data['CPF'])
		     ->where('id_Evento_Link', $data['id_Evento_Link'])
			 ->update($this->participantes, $data);
			 
		return true;
	}
	
	//
	// Efetua o Check-in por QRCode
	//
    public function GetParticipantByCPF($Codigo)
	{
		$query = $this->db
					  ->select('a.Nome, a.Pedido, a.CPF, a.id_Evento, a.id_Evento_Link, a.Presenca, b.titulo')
					  ->where('a.id_Evento_Link = b.id_Evento_Link')
					  ->where('a.CPF', $Codigo['CPF'])	
					  ->where('a.id_Evento', $Codigo['Eventos'])
					  ->where('a.Pago', 1)
					  ->get($this->participantes . ' a, ' . $this->links . ' b');

		if($query->num_rows() > 0)
		{
			if($query->row()->Presenca == 1)
			{
				echo '<h3 class="Play Normal"><h3 class="Play Normal">Presença já foi registrada para esse Evento.</h3><br />';	
			}
			else
			{
				//
				// Método para pegar os dados do aluno e fazer a confirmação
				//
				$Data['Pedido']               =   $query->row()->Pedido;
				$Data['CPF']                  =   $query->row()->CPF;
				$Data['id_Evento_Link']       =   $query->row()->id_Evento_Link;
				$Data['Presenca']             =   1;
				$Data['Data_Hora_Presenca']   =   date('Y-m-d H:i:s');
				
				//
				// Persiste no banco de dados
				//
				$this->update_frequency($Data);
				
				echo '<h3 class="Play Normal"><h3 class="Play Normal">'.trim($query->row()->Nome).', acesso liberado! <br /><br />Lote: '.$query->row()->titulo.'</h3><br />';
			}
        }
		else
		{
			echo '<h3 class="Play Red">Inscrito não encontrado.</h3>';
		}
	}

	//
	// Atualiza o banco nos rollback
	//
    public function execute_rollback($id_Inscrito)
	{
		$this->db
		     ->where('id_Inscrito', $id_Inscrito)
			 ->update($this->participantes, array('Presenca' => 0, 'Data_Hora_Presenca' => NULL));
			 
		return true;
	}	
}

?>