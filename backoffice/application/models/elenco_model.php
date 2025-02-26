<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Elenco_model extends CI_Model {
	
    private $atletas       =    'tb_atletas';
    private $pagamentos    =    'tb_pagamentos';
    private $tipo          =    'tb_tipo_pagamentos';
	private $posicao 	   =    'tb_posicao';
	private $jogos 	       =    'tb_jogos';
	
	public function __construct()
	{
        parent::__construct();
	} 
	
	//
	//  Busca os jogadores por time
	//
    public function get_players_of_team()
	{
		$Body = '';
		
		$Body .= '<div id="accordion">';

		//
		// Carrega as posições
		//
		$query = $this->db
					  ->order_by('Id', 'ASC')
					  ->get($this->posicao);

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $aResultado)
			{
				$query = $this->db
							  ->where('Inativo', 0)			  
							  ->where('Posicao', $aResultado->PosicaoSelect)
							  ->order_by('Nome', 'ASC')
							  ->get($this->atletas);				
				
				$Body .= '
					
					<div class="card">
						<div class="card-header" id="heading'.$aResultado->Id.'">
							<h5 class="mb-0">
								<button class="btn btn-link" data-toggle="collapse" data-target="#collapse'.$aResultado->Id.'" aria-expanded="false" aria-controls="collapse'.$aResultado->Id.'">
									'.$aResultado->Posicao.'
								</button>
							</h5>
						</div>

						<div id="collapse'.$aResultado->Id.'" class="collapse" aria-labelledby="heading'.$aResultado->Id.'" data-parent="#accordion">
							<div class="card-body">
							
								<table class="table"><tbody>';

								foreach ($query->result() as $sAddAtletas)
								{
									$Nome = split_name($sAddAtletas->Nome);

									$Alcunha = ($sAddAtletas->Apelido == '')? $Nome['first_name'] : $sAddAtletas->Apelido;

									$Body  .=  '<tr>
													<td><h5>'.$Alcunha.'</h5></td>
												</tr>
												<tr>
													<td>
														<p>'.$sAddAtletas->Nome.'</p>	
														<span class="badge badge-light mb-3">Nascido em '.data_us_to_br($sAddAtletas->DataNascimento).'</span>
													</td>
												</tr>';	
								}
								
						$Body .= '
								
								</tbody></table></div>
						</div>
					</div>';
			}
			
			$Body .= '</div>';

			return $Body;
		}
    }
	
	//
    // Mostra os aniversariantes do mês
    //
    public function Widget_Birthday($Month)
    {
        $htmlBody  =  '';
        
		$htmlBody .= 
		
			'<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-body">
							
							<table class="table table-striped table-responsive-sm">
							<thead>
							<tr>
								<th scope="col">Aniversariante</th>
								<th scope="col">Dia</th>
							</tr>
							</thead>';

						foreach($this->get_birthday_month($Month) as $sBirthday): 
							
							$Nome = ($sBirthday->Apelido == '')? $sBirthday->Nome : $sBirthday->Nome . ' (' . $sBirthday->Apelido . ')';

							$htmlBody .= 
								
								'<tr>
									<td>'.$Nome.'</td>
									<td>'.$sBirthday->Mes.'</td>
								</tr>';
						
						endforeach;
						
						$htmlBody .= '</tbody></table>

					</div>
				</div>
			</div>
		</div>';

        echo $htmlBody;
    }
	
    //
    // Busca os dados do atleta
    //
    public function get_birthday_month($Month)
    {
        $query = $this->db
                      ->select('Nome, Apelido, CONCAT(SUBSTRING(DataNascimento, 9, 12), "/", SUBSTRING(DataNascimento, 6, 2)) AS Mes', FALSE)
                      ->where('Inativo', 0)
					  ->where('SUBSTRING(DataNascimento, 6, 2) =', $Month)
					  ->order_by('Mes', 'ASC')
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
}

?>