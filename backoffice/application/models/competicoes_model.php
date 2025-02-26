<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Competicoes_model extends CI_Model {
	
    private $atletas       =    'tb_atletas';
    private $jogos         =    'tb_jogos';
    private $artilheiros   =    'tb_artilheiros';
    private $checkin       =    'tb_checkin';
    	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Incluir o registro do jogo
    //
    public function insert_jogos($Data)
    {
        $this->db->insert($this->jogos, $Data);
    }

    //
    // Incluir o registro do artilheiro
    //
    public function insert_artilheiro($Data)
    {
        $this->db->insert($this->artilheiros, $Data);
    }    
    
    //
    // Excluir o registro do jogo
    //    
    public function excluir_jogo($IdJogo)
    {
        $this->db->where('Id', $IdJogo)->delete($this->jogos);
    }

    //
    // Excluir uma lista de jogo
    //    
    public function excluir_sumula($IdJogo)
    {
        $this->db->where('IdJogo', $IdJogo)->delete($this->checkin);
    }    

    //
    // Excluir o registro do artilheiro
    //    
    public function excluir_artilheiro($IdArtilheiro)
    {
        $this->db->where('Id', $IdArtilheiro)->delete($this->artilheiros);
    }    

    //
    // Alterar o registro do jogo
    //
    public function update_jogo($Data)
    {
        return $this->db
                    ->where('Id', $Data['Id'])
                    ->update($this->jogos, $Data);    
    }

    //
    // Alterar o registro do artilheiro
    //
    public function update_artilheiro($Data)
    {
        return $this->db
                    ->where('Id', $Data['Id'])
                    ->update($this->artilheiros, $Data);    
    }    
    
    //
    // Busca um jogo recebendo o ID
    //
    public function get_jogo($IdJogo)
    {
        $query = $this->db
                      ->where('Id', $IdJogo)
                      ->get($this->jogos);
        
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
    // Busca um artilheiro recebendo o ID
    //
    public function get_artilheiro($IdArtilheiro)
    {
        $query = $this->db
                      ->where('Id', $IdArtilheiro)
                      ->get($this->artilheiros);
        
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
    // Mostra as partidas da temporada
    //
	public function Widget_Matches($Year)
	{
        $htmlBody  =  '';
        
		$htmlBody .= 
		
			'<div class="row mb-3">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-body">
							
							<table class="table table-striped table-responsive-sm">
							<thead>
							<tr>
								<th scope="col"></th>	
								<th scope="col">Data</th>
								<th scope="col">Mandante</th>
								<th scope="col">Visitante</th>
								<th scope="col">Placar</th>
								<th scope="col">Gols <img src="assets/images/football.png" width="24" height="24" /></th>
							</tr>
							</thead>';

						foreach($this->get_matches_year($Year) as $sMatches): 
							
							$Links   =  ($this->session->userdata('Admin') == 0)? 'Jogo Nº ' . $sMatches->Id : '<a title="Excluir Jogo" href="'.$sMatches->Id.'" data-id="'.$sMatches->Id.'" data-toggle="modal" data-target="#confirm-delete"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_jogo/'.$sMatches->Id.'" title="Alterar Jogo"><img src="assets/images/pencil.gif" width="17" height="17"></a>';
							
							//competicoes/detalhes/'.$sMatches->Id.'

                            $Gols = $this->get_goals_of_match($sMatches->Id);

							$htmlBody .= 
								
								'<tr>
                                    <td>'.$Links.'</td>
									<td>'.data_us_to_br($sMatches->Data).'</td>
									<td>'.$sMatches->Mandante.'</td>	
									<td>'.$sMatches->Visitante.'</td>
									<td>'.$sMatches->GolsMandante.'x'.$sMatches->GolsVisitante.'</td>
                                    <td class="goals">'.$Gols.'</td>
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
    // Busca as partidas realizadas
    //
    public function get_matches_year($Year)
    {
        $query = $this->db
                      ->where('Ano', $Year)
					  ->order_by('Data', 'DESC')
                      ->get($this->jogos);
        
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
    // Busca as partidas realizadas
    //
    public function get_goals_of_match($IdJogo)
    {
        $query = $this->db
                      ->select('b.Nome, b.Apelido, a.Gols')
                      ->where('a.IdAtleta = b.Id')
                      ->where('a.IdJogo', $IdJogo)
                      ->where('a.Gols !=', 0)
					  ->order_by('a.Gols', 'DESC')
                      ->order_by('b.Nome', 'ASC')
                      ->get($this->artilheiros . ' a, ' . $this->atletas . ' b');
        
        if($query->num_rows() > 0)
        {
            $table = '';
            
            foreach ($query->result() as $aRow)
            {
                $Nome = split_name($aRow->Nome);

                $Alcunha = ($aRow->Apelido == '')? $Nome['first_name'] : $aRow->Apelido;                
                
                $gols = ($aRow->Gols == 1)? 'gol' : 'gols';
                
                $table .= '<p>'.$Alcunha.' - '.$aRow->Gols.' '.$gols.'</p>';
            }
            
            return $table;
        }
    }    

    //
    // Mostra os artilheiros
    //
	public function Widget_Artilheiros($Year, $Page)
	{
        $htmlBody  =  '';
        $Posicao   =  1;
        
        if($this->get_score_year($Year) == 0)
        {
            $htmlBody .= '<div class="alert alert-warning" role="alert">Ainda não possui artilheiros :) bora fazer gols?</div>';
        }
        elseif($Year == 2099)
        {
            //
            // Visão para usuários
            //
            $Line  =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<th scope="col"></th>'; 
            
            $htmlBody .= 
        
            '<div class="row mb-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <table class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                '.$Line.'	
                                <th scope="col">Nome</th>
                                <th scope="col">Gols</th>
                            </tr>
                            </thead>';

                        foreach($this->get_score_year($Year) as $sScored): 
                            
                            $Nome     =  split_name($sScored->Nome);
                            $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                            $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<td><a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a></td>';
                            
                            $htmlBody .= 
                                
                                '<tr>
                                    '.$Links.'
                                    <td>'.$Posicao . ' - ' . $Alcunha.'</td>	
                                    <td>'.$sScored->Gols.'</td>
                                </tr>';
                            
                            $Posicao++;
                        endforeach;
                        
                        $htmlBody .= '</tbody></table>

                        </div>
                    </div>
                </div>
            </div>';            
        }
        else
        {
            $Mensagem = '<div class="alert alert-warning mb-3" role="alert">
                            Em 2023 foram considerados apenas gols a partir do dia 07/07/2023.
                        </div>';
            
            if($this->session->userdata('Admin') == 1 && $Page != 'Home')
            {
                //
                // Visão para admin
                //                
                $htmlBody .= 
		
                '<div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">';
    
                                if($Year == 2023) echo $Mensagem;
                            
                  $htmlBody .= '<table class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col"></th>	
                                    <th scope="col">Jogo</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Gols</th>
                                    <th scope="col">Assistências</th>
                                </tr>
                                </thead>';
    
                            foreach($this->get_score_year_for_admin($Year) as $sScored): 
                                
                                $Nome     =  split_name($sScored->Nome);
                                $Jogo     =  'Jogo Nº ' . $sScored->IdJogo . ' - ' . data_us_to_br($sScored->Data) . ' - ' . $sScored->GolsMandante . ' x ' . $sScored->GolsVisitante;
                                $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                                $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? $Posicao : '<a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a>';
    
                                $htmlBody .= 
                                    
                                    '<tr>
                                        <td>'.$Links.'</td>
                                        <td>'.$Jogo.'</td>	
                                        <td>'.$Alcunha.'</td>	
                                        <td>'.$sScored->Gols.'</td>
                                        <td>'.$sScored->Assistencias.'</td>
                                    </tr>';
                                
                                $Posicao++;
                            endforeach;
                            
                            $htmlBody .= '</tbody></table>
    
                            </div>
                        </div>
                    </div>
                </div>';                
            }
            else
            {
                //
                // Visão para usuários
                //
                $Line  =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<th scope="col"></th>'; 
                
                $htmlBody .= 
		
                '<div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">';
    
                                if($Year == 2023) echo $Mensagem;
            
                  $htmlBody .= '<table class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    '.$Line.'	
                                    <th scope="col">Nome</th>
                                    <th scope="col">Gols</th>
                                </tr>
                                </thead>';

                            foreach($this->get_score_year($Year) as $sScored): 
                                
                                $Nome     =  split_name($sScored->Nome);
                                $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                                $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<td><a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a></td>';
                                
                                $htmlBody .= 
                                    
                                    '<tr>
                                        '.$Links.'
                                        <td>'.$Posicao . ' - ' . $Alcunha.'</td>	
                                        <td>'.$sScored->Gols.'</td>
                                    </tr>';
                                
                                $Posicao++;
                            endforeach;
                            
                            $htmlBody .= '</tbody></table>

                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        echo $htmlBody;
	}

    //
    // Mostra as assistencias
    //
	public function Widget_Assistencias($Year, $Page)
	{
        $htmlBody  =  '';
        $Posicao   =  1;
        
        if($this->get_assists_year($Year) == 0)
        {
            $htmlBody .= '<div class="alert alert-warning" role="alert">Ainda não possui assistências :) bora meter uns passes para os atacantes?</div>';
        }
        elseif($Year == 2099)
        {
            //
            // Visão para usuários
            //
            $Line  =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<th scope="col"></th>'; 
            
            $htmlBody .= 
        
            '<div class="row mb-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <table class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                '.$Line.'	
                                <th scope="col">Nome</th>
                                <th scope="col">Assistências</th>
                            </tr>
                            </thead>';

                        foreach($this->get_assists_year($Year) as $sScored): 
                            
                            $Nome     =  split_name($sScored->Nome);
                            $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                            $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<td><a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a></td>';
                            
                            $htmlBody .= 
                                
                                '<tr>
                                    '.$Links.'
                                    <td>'.$Posicao . ' - ' . $Alcunha.'</td>	
                                    <td>'.$sScored->Assistencias.'</td>
                                </tr>';
                            
                            $Posicao++;
                        endforeach;
                        
                        $htmlBody .= '</tbody></table>

                        </div>
                    </div>
                </div>
            </div>';            
        }
        else
        {
            if($this->session->userdata('Admin') == 1 && $Page != 'Home')
            {
                //
                // Visão para admin
                //                
                $htmlBody .= 
		
                '<div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">';
    
                                if($Year == 2023) echo $Mensagem;
                            
                  $htmlBody .= '<table class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col"></th>	
                                    <th scope="col">Jogo</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Assistências</th>
                                </tr>
                                </thead>';
    
                            foreach($this->get_assists_year_for_admin($Year) as $sScored): 
                                
                                $Nome     =  split_name($sScored->Nome);
                                $Jogo     =  'Jogo Nº ' . $sScored->IdJogo . ' - ' . data_us_to_br($sScored->Data) . ' - ' . $sScored->GolsMandante . ' x ' . $sScored->GolsVisitante;
                                $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                                $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? $Posicao : '<a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a>';
    
                                $htmlBody .= 
                                    
                                    '<tr>
                                        <td>'.$Links.'</td>
                                        <td>'.$Jogo.'</td>	
                                        <td>'.$Alcunha.'</td>	
                                        <td>'.$sScored->Assistencias.'</td>
                                    </tr>';
                                
                                $Posicao++;
                            endforeach;
                            
                            $htmlBody .= '</tbody></table>
    
                            </div>
                        </div>
                    </div>
                </div>';                
            }
            else
            {
                //
                // Visão para usuários
                //
                $Line  =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<th scope="col"></th>'; 
                
                $htmlBody .= 
		
                '<div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">';
    
                                if($Year == 2023) echo $Mensagem;
            
                  $htmlBody .= '<table class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    '.$Line.'	
                                    <th scope="col">Nome</th>
                                    <th scope="col">Assistências</th>
                                </tr>
                                </thead>';

                            foreach($this->get_assists_year($Year) as $sScored): 
                                
                                $Nome     =  split_name($sScored->Nome);
                                $Alcunha  =  ($sScored->Apelido == '')? $Nome['first_name'] : $sScored->Apelido;
                                $Links    =  ($this->session->userdata('Admin') == 0 || $Page == 'Home')? '' : '<td><a title="Excluir Artilheiro" href="'.$sScored->Id.'" data-id="'.$sScored->Id.'" data-toggle="modal" data-target="#confirm-delete-atilheiro"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="competicoes/alterar_artilheiro/'.$sScored->Id.'" title="Alterar Artilheiro"><img src="assets/images/pencil.gif" width="17" height="17"></a></td>';
                                
                                $htmlBody .= 
                                    
                                    '<tr>
                                        '.$Links.'
                                        <td>'.$Posicao . ' - ' . $Alcunha.'</td>	
                                        <td>'.$sScored->Assistencias.'</td>
                                    </tr>';
                                
                                $Posicao++;
                            endforeach;
                            
                            $htmlBody .= '</tbody></table>

                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        echo $htmlBody;
	}    

    //
    // Busca os artilheiros
    //
    public function get_score_year_for_admin($Year)
    {
        $query = $this->db
                      ->select('a.Id, b.Nome, b.Apelido, a.Gols, a.Assistencias, c.Id AS IdJogo, c.Data, c.GolsMandante, c.GolsVisitante')
                      ->where('a.IdAtleta = b.Id')
                      ->where('a.IdJogo = c.Id')              
                      ->where('a.Ano', $Year)
                      ->order_by('IdJogo', 'DESC')
                      ->get($this->artilheiros . ' a, ' . $this->atletas . ' b, ' . $this->jogos . ' c');
        
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
    // Busca os artilheiros
    //
    public function get_score_year($Year)
    {
        if($Year != 2099)
        {
            $this->db->where('a.Ano', $Year);
        }
        
        $query = $this->db
                      ->select('a.Id, b.Nome, b.Apelido, SUM(a.Gols) AS Gols')
                      ->where('a.IdAtleta = b.Id')
                      ->where('a.Gols != 0')              
                      ->group_by('b.Nome')
                      ->order_by('Gols', 'DESC')
                      ->order_by('a.Id', 'ASC')
                      ->get($this->artilheiros . ' a, ' . $this->atletas . ' b');
        
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
    // Busca os maiores em assistencias
    //
    public function get_assists_year_for_admin($Year)
    {
        $query = $this->db
                      ->select('a.Id, b.Nome, b.Apelido, a.Assistencias, c.Id AS IdJogo, c.Data, c.GolsMandante, c.GolsVisitante')
                      ->where('a.IdAtleta = b.Id')
                      ->where('a.IdJogo = c.Id')              
                      ->where('a.Ano', $Year)
                      ->where('a.Assistencias != 0')
					  ->order_by('IdJogo', 'DESC')
                      ->get($this->artilheiros . ' a, ' . $this->atletas . ' b, ' . $this->jogos . ' c');
        
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
    // Busca os maiores em assistencias
    //
    public function get_assists_year($Year)
    {
        if($Year != 2099)
        {
            $this->db->where('a.Ano', $Year);
        }
        
        $query = $this->db
                      ->select('a.Id, b.Nome, b.Apelido, SUM(a.Assistencias) AS Assistencias')
                      ->where('a.IdAtleta = b.Id')
                      ->where('a.Assistencias != 0')              
                      ->group_by('b.Nome')
                      ->order_by('Assistencias', 'DESC')
                      ->order_by('a.Id', 'ASC')
                      ->get($this->artilheiros . ' a, ' . $this->atletas . ' b');
        
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
    public function get_atletas_dropdown()
    {
        $query = $this->db
                      ->select('Id, Nome, Apelido')
                      ->where('Inativo', 0)
                      ->order_by('Nome', 'ASC')
                      ->get($this->atletas);
        
        if($query->num_rows() > 0)
        {
            $row[0]  =  "Selecione o Jogador";

            foreach ($query->result() as $aRow)
            {
                $Nome = ($aRow->Apelido == '')? $aRow->Nome : $aRow->Nome . ' (' . $aRow->Apelido . ')';
                
                $row[$aRow->Id] = $Nome;
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
    public function get_jogos_dropdown()
    {
        $query = $this->db
                      ->select('Id, Data, GolsMandante, GolsVisitante')
                      ->order_by('Data', 'DESC')
                      ->get($this->jogos);
        
        if($query->num_rows() > 0)
        {
            $row[0]  =  "Selecione o Jogo";

            foreach ($query->result() as $aRow)
            {
                $Nome = 'Jogo do dia ' . data_us_to_br($aRow->Data) . ' - ' . $aRow->GolsMandante . ' x ' . $aRow->GolsVisitante;
                
                $row[$aRow->Id] = $Nome;
            }

            return $row; 
        }
        else
        {
            return array();
        }
    }
}

?>