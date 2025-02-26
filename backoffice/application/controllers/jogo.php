<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jogo extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('perfil_model');
        $this->load->model('pagamento_model');
        $this->load->model('elenco_model');
        $this->load->model('competicoes_model');
        $this->load->model('escalacao_model');
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['AddJavascripts']   =   load_js(array('jogos/jogos'));        

        $this->usable('pre-jogo');
    }

    //
    // Página principal
    //
    public function escalar()
    {
        $this->data['AddJavascripts']   =   load_js(array('jogos/jogos'));        
        $this->data['AddCheckIn']       =   $this->escalacao_model->load_checkin();

        $this->usable('jogo');
    }    

    //
    // Ação de checkin para escalação
    //    
    public function checkin($IdAtleta, $IdJogo)
    {
        $Data['IdAtleta']  =  $IdAtleta;
        $Data['IdJogo']    =  $IdJogo;

        return $this->escalacao_model->add_checkin($Data);
    }

    //
    // Ação de checkout para escalação
    //    
    public function checkout($IdAtleta, $IdJogo)
    {
        $Data['IdAtleta']  =  $IdAtleta;
        $Data['IdJogo']    =  $IdJogo;        
        
        return $this->escalacao_model->add_checkout($Data);
    }

    //
    // Ação de contar os já escalados
    //     
    public function count_checkin($IdJogo)
    {
        return $this->escalacao_model->count_checkout($IdJogo);
    }
    
    //
    // Ação de listar escalação
    //    
    public function list_team($IdJogo)
    {
        // Defesa = 1,3,3,2 = 12
        // Meio = 4,3,2,4 = 12
        // Ataque = 3,3 = 6
        // 30 + Goleiro = 35 numero máximo

        $goleiros        =   $this->escalacao_model->get_goalkeaper_players();
        $laterais        =   $this->escalacao_model->get_side_defense_players();
        $zagueiros       =   $this->escalacao_model->get_defense_players();
        $meias           =   $this->escalacao_model->get_midfield_offensive_players();
        $atacantes       =   $this->escalacao_model->get_forward_players();
        
        $TotalJogadores  =   $this->escalacao_model->count_checkout_internal($IdJogo);

        if($TotalJogadores >= 22)
        {            
            $body = '';
            
            $body  .=  '<table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Time 1</th>
                                <th scope="col">Time 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Goleiros</th>
                                <td>'.@$goleiros['Jogadores'][0].'</td>
                                <td>'.@$goleiros['Jogadores'][1].'</td>
                            </tr>
                            <tr>
                                <th scope="row">Laterais</th>
                                <td>'.@$laterais['Jogadores'][0].'</td>
                                <td>'.@$laterais['Jogadores'][2].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$laterais['Jogadores'][1].'</td>
                                <td>'.@$laterais['Jogadores'][3].'</td>
                            </tr>                        
                            <tr>
                                <th scope="row">Zagueiros</th>
                                <td>'.@$zagueiros['Jogadores'][0].'</td>
                                <td>'.@$zagueiros['Jogadores'][2].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$zagueiros['Jogadores'][1].'</td>
                                <td>'.@$zagueiros['Jogadores'][3].'</td>
                            </tr>
                            <tr>
                                <th scope="row">Meias</th>
                                <td>'.@$meias['Jogadores'][0].'</td>
                                <td>'.@$meias['Jogadores'][2].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$meias['Jogadores'][1].'</td>
                                <td>'.@$meias['Jogadores'][3].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$meias['Jogadores'][4].'</td>
                                <td>'.@$meias['Jogadores'][6].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$meias['Jogadores'][5].'</td>
                                <td>'.@$meias['Jogadores'][7].'</td>
                            </tr>
                            <tr>
                                <th scope="row">Atacantes</th>
                                <td>'.@$atacantes['Jogadores'][0].'</td>
                                <td>'.@$atacantes['Jogadores'][2].'</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>'.@$atacantes['Jogadores'][1].'</td>
                                <td>'.@$atacantes['Jogadores'][3].'</td>
                            </tr>                                                 
                        </tbody>
                        </table>';

            echo $body;

        }
        else
        {
            echo '<div class="alert alert-danger" role="alert">
                        Precisamos ter no mínimo <strong>22</strong> jogadores relacionados para a partida, e atualmente temos <strong>'.$TotalJogadores.'</strong>.
                  </div>';   
        }        
        
        /*
        
        // Defesa = 1,3,3,2 = 12
        // Meio = 4,3,2,4 = 12
        // Ataque = 3,3 = 6
        // 30 + Goleiro = 35 numero máximo

        $goleiros     =   $this->escalacao_model->get_goalkeaper_players();
        $laterais     =   $this->escalacao_model->get_side_defense_players();
        $zagueiros    =   $this->escalacao_model->get_defense_players();
        $volantes     =   $this->escalacao_model->get_midfield_players();
        $meias        =   $this->escalacao_model->get_midfield_offensive_players();
        $atacantes    =   $this->escalacao_model->get_forward_players();

        $Total = $goleiros['Quantidade'] + $laterais['Quantidade'] + $zagueiros['Quantidade'] + $volantes['Quantidade'] + $meias['Quantidade'] + $atacantes['Quantidade'];

        if($Total >= 11)
        {
            $body = '';
            
            $body = '<b>' . $goleiros['Posicao'] . '</b><br>';
                        
            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $goleiros['Jogadores'][0];
            }  

            $body .= '<br><br><b>' . $laterais['Posicao'] . '</b><br>';
            
            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $laterais['Jogadores'][0] . '<br>';
                $body .= $laterais['Jogadores'][1];
            }  

            $body .= '<br><br><b>' . $zagueiros['Posicao'] . '</b><br>';

            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $zagueiros['Jogadores'][0] . '<br>';
                $body .= $zagueiros['Jogadores'][1];
            } 
            
            $body .= '<br><br><b>' . $volantes['Posicao'] . '</b><br>';
            
            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $volantes['Jogadores'][0] . '<br>';
                $body .= $volantes['Jogadores'][1];
            } 
            
            $body .= '<br><br><b>' . $meias['Posicao'] . '</b><br>';
            
            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $meias['Jogadores'][0] . '<br>';
                $body .= $meias['Jogadores'][1];
            }         

            $body .= '<br><br><b>' . $atacantes['Posicao'] . '</b><br>';
            
            for ($x = 0; $x < 1; $x++) 
            {
                $body .= $atacantes['Jogadores'][0] . '<br>';
                $body .= $atacantes['Jogadores'][1];
            }

            $pontos = $goleiros['Pontos'] + $laterais['Pontos'] + $zagueiros['Pontos'] + $volantes['Pontos'] + $meias['Pontos'] + $atacantes['Pontos'];

            echo $body .= '<br><br><b>' . $pontos . ' pontos - Numero máximo de 35</b>';
        }
        else
        {
            echo '<div class="alert alert-danger" role="alert">
                        Precisamos ter no mínimo <strong>22</strong> jogadores relacionados para a partida, e atualmente temos <strong>'.$Total.'</strong>.
                  </div>';   
        }

        */
    }     
}