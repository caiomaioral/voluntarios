<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Escalacao_model extends CI_Model {
	
    private $atletas       =    'tb_atletas';
    private $checkin       =    'tb_checkin';
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('competicoes_model');
    } 

    //
    // Carrega a lista dos caras para o check-in
    //
    public function load_checkin()
    {
        $query = $this->db
                      ->select('Id, IdJogo')
                      ->where('Data', date('Y-m-d'))
                      ->get($this->checkin); 

        if($query->num_rows() == 0)
        {                      
            $query = $this->db
                        ->select('Id')
                        ->where('Inativo', 0)
                        ->get($this->atletas);
            
            if($query->num_rows() > 0)
            {
                //
                // Tabela de Jogo
                //
                $Data['Data']        	     =    date('Y-m-d');
                $Data['Mandante']            =    "PARA DEFINIR";
                $Data['Visitante']        	 =    "PARA DEFINIR";
                $Data['GolsMandante'] 		 =    0;
                $Data['GolsVisitante'] 		 =    0;
                $Data['Ano']        	     =    date('Y');

                //
                // Include the game and create the union
                //
                $this->competicoes_model->insert_jogos($Data); 
                
                $insert_id = $this->db->insert_id();
                
                foreach($query->result() as $sPlayers):

                    $Players['IdAtleta']   =   $sPlayers->Id;
                    $Players['IdJogo']     =   $insert_id;
                    $Players['Data']       =   date('Y-m-d');

                    $this->db->insert($this->checkin, $Players);

                endforeach;

                return $Players['IdJogo'];
            }
            else
            {
                return array();
            }
        }
        else
        {
            return $query->row()->IdJogo;
        }
    }

    //
    // Busca um atleta recebendo o ID
    //
    public function get_atleta($IdAtleta)
    {
        $query = $this->db
                      ->where('Id', $IdAtleta)
                      ->get($this->atletas);
        
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
    // Busca todos os atletas
    //
    public function get_goalkeaper_players()
    {
        $query = $this->db
                      ->select('a.Id, a.Apelido, a.Nome, a.Posicao, a.Nivel, b.Timestamp, (SELECT COUNT(IdAtleta) FROM tb_checkin WHERE IdAtleta = a.Id AND CheckIn = 1) AS QuantidadeJogos
                                FROM (tb_atletas a, tb_checkin b) 
                                WHERE a.Id = b.IdAtleta 
                                AND a.Inativo = 0 
                                AND b.CheckIn = 1 
                                AND a.Posicao = "GOLEIRO" 
                                AND b.Data = "'.date('Y-m-d').'"
                                ORDER BY b.Timestamp ASC, QuantidadeJogos DESC, RAND() LIMIT 2', null)
                      ->get();
        
        if($query->num_rows() > 0)
        {
			$x = 0;

            foreach ($query->result() as $aRow)
			{
                $nivel[$aRow->Id] = $aRow->Nivel;
                
                $Nome           =   split_name($aRow->Nome);
                $Alcunha        =   ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $row[$x] = $Alcunha;

                $x++;
			}

            $result = array('Posicao' => 'GOLEIRO', 
                            'Pontos' => array_sum($nivel),
                            'Jogadores' => $row,
                            'Quantidade' => $x);
			
			return $result; 
        }
        else
        {
            return array('Quantidade' => 0);
        }
    }

    //
    // Busca todos os atletas
    //
    public function get_side_defense_players()
    {
        $query = $this->db
                      ->select('a.Id, a.Apelido, a.Nome, a.Posicao, a.Nivel, b.Timestamp, ((SELECT COUNT(IdAtleta) FROM tb_checkin WHERE IdAtleta = a.Id AND CheckIn = 1) * 100) AS QuantidadeJogos
                                FROM tb_atletas a, tb_checkin b
                                WHERE a.Id = b.IdAtleta
                                AND (a.Posicao = "LATERAL" OR a.Posicao = "ZAGUEIRO" OR a.Posicao = "VOLANTE")
                                AND b.CheckIn = 1
                                AND b.Data = "'.date('Y-m-d').'"
                                ORDER BY a.Posicao <> "LATERAL" ASC,
                                         a.Posicao <> "ZAGUEIRO" ASC,
                                         a.Posicao <> "VOLANTE" ASC,
                                         b.Timestamp ASC,
                                         QuantidadeJogos ASC, 
                                         RAND()
                                LIMIT 4', null)
                      ->get();
        
        if($query->num_rows() > 0)
        {
			$x = 0;

            foreach ($query->result() as $aRow)
			{
                $nivel[$aRow->Id] = $aRow->Nivel;
                
                $Nome           =   split_name($aRow->Nome);
                $Alcunha        =   ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $row[$x] = $Alcunha;

                $x++;
			}

            $result = array('Posicao' => 'LATERAIS', 
                            'Pontos' => array_sum($nivel),
                            'Jogadores' => $row,
                            'Quantidade' => $x);
			
			return $result; 
        }
        else
        {
            return array('Quantidade' => 0);
        }
    }

    //
    // Busca todos os atletas
    //
    public function get_defense_players()
    {
        $query = $this->db
                      ->select('a.Id, a.Apelido, a.Nome, a.Posicao, a.Nivel, b.Timestamp, ((SELECT COUNT(IdAtleta) FROM tb_checkin WHERE IdAtleta = a.Id AND CheckIn = 1) * 100) AS QuantidadeJogos
                                FROM tb_atletas a, tb_checkin b
                                WHERE a.Id = b.IdAtleta
                                AND (a.Posicao = "ZAGUEIRO" OR a.Posicao = "VOLANTE" OR a.Posicao = "LATERAL")
                                AND b.CheckIn = 1
                                AND b.Data = "'.date('Y-m-d').'"
                                ORDER BY a.Posicao <> "ZAGUEIRO" ASC,
                                        a.Posicao <> "LATERAL" ASC,
                                        a.Posicao <> "VOLANTE" ASC,
                                        b.Timestamp ASC,
                                        QuantidadeJogos ASC, 
                                        RAND()
                                LIMIT 4', null)
                      ->get();
        
        if($query->num_rows() > 0)
        {
			$x = 0;

            foreach ($query->result() as $aRow)
			{
                $nivel[$aRow->Id] = $aRow->Nivel;
                
                $Nome           =   split_name($aRow->Nome);
                $Alcunha        =   ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $row[$x] = $Alcunha;

                $x++;
			}

            $result = array('Posicao' => 'ZAGUEIROS', 
                            'Pontos' => array_sum($nivel),
                            'Jogadores' => $row,
                            'Quantidade' => $x);
			
			return $result; 
        }
        else
        {
            return array('Quantidade' => 0);
        }
    }
    
    //
    // Busca todos os atletas
    //
    public function get_midfield_offensive_players()
    {
        $query = $this->db
                      ->select('a.Id, a.Apelido, a.Nome, a.Posicao, a.Nivel, b.Timestamp, ((SELECT COUNT(IdAtleta) FROM tb_checkin WHERE IdAtleta = a.Id AND CheckIn = 1) * 100) AS QuantidadeJogos
                                FROM tb_atletas a, tb_checkin b
                                WHERE a.Id = b.IdAtleta
                                AND (a.Posicao = "MEIA" OR a.Posicao = "VOLANTE")
                                AND b.CheckIn = 1
                                AND b.Data = "'.date('Y-m-d').'"
                                ORDER BY b.Timestamp ASC,
                                         a.Posicao <> "MEIA" ASC,
                                         a.Posicao <> "VOLANTE" ASC,
                                         QuantidadeJogos ASC, 
                                         RAND()
                                LIMIT 8', null)
                      ->get();        
        
        if($query->num_rows() > 0)
        {
			$x = 0;

            foreach ($query->result() as $aRow)
			{
                $nivel[$aRow->Id] = $aRow->Nivel;
                
                $Nome           =   split_name($aRow->Nome);
                $Alcunha        =   ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $row[$x] = $Alcunha;

                $x++;
			}

            $result = array('Posicao' => 'MEIAS', 
                            'Pontos' => array_sum($nivel),
                            'Jogadores' => $row,
                            'Quantidade' => $x);
			
			return $result; 
        }
        else
        {
            return array('Quantidade' => 0);
        }
    }
    
    //
    // Busca todos os atletas
    //
    public function get_forward_players()
    {
        $query = $this->db
                      ->select('a.Id, a.Apelido, a.Nome, a.Posicao, a.Nivel, b.Timestamp, ((SELECT COUNT(IdAtleta) FROM tb_checkin WHERE IdAtleta = a.Id AND CheckIn = 1) * 100) AS QuantidadeJogos
                                FROM tb_atletas a, tb_checkin b
                                WHERE a.Id = b.IdAtleta
                                AND (a.Posicao = "ATACANTE" OR a.Posicao = "CENTROAVANTE")
                                AND b.CheckIn = 1
                                AND b.Data = "'.date('Y-m-d').'"
                                ORDER BY b.Timestamp ASC,
                                         a.Posicao <> "ATACANTE" ASC,
                                         a.Posicao <> "CENTROAVANTE" ASC,
                                         a.Posicao <> "MEIA" ASC,
                                         QuantidadeJogos ASC, 
                                         RAND()
                                LIMIT 4', null)
                      ->get(); 
        
        if($query->num_rows() > 0)
        {
			$x = 0;

            foreach ($query->result() as $aRow)
			{
                $nivel[$aRow->Id] = $aRow->Nivel;
                
                $Nome           =   split_name($aRow->Nome);
                $Alcunha        =   ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $row[$x] = ($aRow->Apelido == '')? $Nome : $Alcunha;

                $x++;
			}

            $result = array('Posicao' => 'ATACANTES', 
                            'Pontos' => array_sum($nivel),
                            'Jogadores' => $row,
                            'Quantidade' => $x);

			return $result; 
        }
        else
        {
            return array('Quantidade' => 0);
        }
    }
    
    //
    // Mostra a lista dos atletas ativos
    //
	public function Widget_Players($IdJogo)
	{
        $htmlBody  =  '';
        
        $htmlBody .= 
		
			'<div class="row mb-3">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-body">

                            <span id="count_players" class="badge badge-light mb-3"></span>
							
							<table class="table table-striped table-responsive-sm">
							</thead>';

						foreach($this->get_players($IdJogo) as $sPlayers): 
							
							$Links  =  '<a title="Remover da Lista" href="'.$sPlayers->IdAtleta.'" data-id="'.$sPlayers->IdAtleta.'|'.$sPlayers->IdJogo.'" data-toggle="modal" data-target="#confirm-delete"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a>';
							
                            $Nome           =   split_name($sPlayers->Nome);
                            $Alcunha        =   ($sPlayers->Apelido == '')? $Nome['first_name'] : $sPlayers->Apelido;
                            $CheckIn        =   ($sPlayers->CheckIn == 1)? '<img src="/pyngaiada/assets/images/checked.png" alt="" width="30" height="30" />' : '<img src="/pyngaiada/assets/images/nochecked.png" alt="" width="30" height="30" />';
                            $ActionCheckIn  =   ($sPlayers->CheckIn == 1)? '<a id="CheckOut" data-id="'.$sPlayers->IdAtleta.'|'.$sPlayers->IdJogo.'" href="javascript:void(0)" title="Retirar Atleta" class="badge badge-danger mr-5">Retirar</a>' : '<a id="CheckIn" data-id="'.$sPlayers->IdAtleta.'|'.$sPlayers->IdJogo.'" href="javascript:void(0)" title="Escalar Atleta" class="badge badge-success mr-5">Escalar</a>';
                            
							$htmlBody .= 
								'<tr>
									<td>'.$Alcunha.'</td>	
									<td id="Atleta_'.$sPlayers->IdAtleta.'">'.$ActionCheckIn.$CheckIn.'</td>
								</tr>';
						
						endforeach;
						
						$htmlBody .= '</tbody></table>

                        <!--<span id="count_players" class="badge badge-light mt-3">0 de 22 atletas escalados</span>-->

					</div>
				</div>
			</div>
		</div>';

        echo $htmlBody;
	}
    
    //
    // Lista os atletas
    //
    public function get_players($IdJogo)
    {
        $query = $this->db
                      ->select('a.IdAtleta, a.IdJogo, b.Nome, b.Apelido, a.CheckIn')
                      ->where('b.Id = a.IdAtleta')
                      ->where('a.IdJogo', $IdJogo)
                      ->where('b.Inativo', 0)
					  ->order_by('b.Nome', 'ASC')
                      ->get($this->checkin . ' a, ' . $this->atletas . ' b');
        
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
    // Ação de persistir no banco
    //    
    public function add_checkin($Data)
    {
        $CheckIn = 1;
        
        $this->db
             ->where('IdAtleta', $Data['IdAtleta'])
             ->update($this->checkin, array('IdJogo' => $Data['IdJogo'],'CheckIn' => 1));        

        $StatusCheckIn  =  ($CheckIn == 1)? '<img src="/pyngaiada/assets/images/checked.png" alt="" width="30" height="30" />' : '<img src="/pyngaiada/assets/images/nochecked.png" alt="" width="30" height="30" />';

        $htmlBody = '';
        
        echo $htmlBody .=  '<a id="CheckOut" data-id="'.$Data['IdAtleta'].'|'.$Data['IdJogo'].'" href="javascript:void(0)" title="Retirar Atleta" class="badge badge-danger mr-5">Retirar</a> '.$StatusCheckIn;
    }

    //
    // Ação de remover no banco
    //    
    public function add_checkout($Data)
    {
        $CheckIn = 0;
        
        $this->db
             ->where('IdAtleta', $Data['IdAtleta'])
             ->update($this->checkin, array('IdJogo' => $Data['IdJogo'],'CheckIn' => 0));          

        $StatusCheckIn  =  ($CheckIn == 0)? '<img src="/pyngaiada/assets/images/nochecked.png" alt="" width="30" height="30" />' : '<img src="/pyngaiada/assets/images/checked.png" alt="" width="30" height="30" />';

        $htmlBody = '';
        
        echo $htmlBody .=  '<a id="CheckIn" data-id="'.$Data['IdAtleta'].'|'.$Data['IdJogo'].'" href="javascript:void(0)" title="Escalar Atleta" class="badge badge-success mr-5">Escalar</a> '.$StatusCheckIn;
    }
    
    //
    // Conta os atletas já escalados
    //
    public function count_checkout($IdJogo)
    {
        $query = $this->db
                      ->where('IdJogo', $IdJogo)
                      ->where('CheckIn', 1)
                      ->get($this->checkin);
        
        if($query->num_rows() > 0)
        {
            echo $query->num_rows();
        }
	    else
	    {
            return 0;
        }
    }
    
    //
    // Conta os atletas já escalados
    //
    public function count_checkout_internal($IdJogo)
    {
        $query = $this->db
                      ->where('IdJogo', $IdJogo)
                      ->where('CheckIn', 1)
                      ->get($this->checkin);
        
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        }
	    else
	    {
            return 0;
        }
    }     
}

?>