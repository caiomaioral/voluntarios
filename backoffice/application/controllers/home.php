<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('perfil_model');
        $this->load->model('pagamento_model');
        $this->load->model('elenco_model');
        $this->load->model('competicoes_model');
        $this->load->model('escalacao_model');

        $this->data['AddCss']  		       =   load_css(array('home/home'));
        $this->data['AddJavascripts']      =   load_js(array('home/home'));
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['error']          =   "";
        $this->data['Admin']          =   ($this->session->userdata('Admin') == 1)? 'MEMBRO DA COMISSÃO' : 'ATLETA PAGANTE';
        $this->data['Temporada']      =   $this->input->post('Temporada');
        $this->data['Assistencias']   =   $this->input->post('Assistencias');
        
        $this->usable('home');
    }

    //
    // Teste de Escalação
    //    
    public function escalacao()
    {
        // Defesa = 1,3,3,2 = 12
        // Meio = 4,3,2,4 = 12
        // Ataque = 3,3 = 6
        // 30 + Goleiro = 35 numero máximo

        $goleiros = $this->escalacao_model->get_goalkeaper_players();
        $laterais = $this->escalacao_model->get_side_defense_players();
        $zagueiros = $this->escalacao_model->get_defense_players();
        $volantes = $this->escalacao_model->get_midfield_players();
        $meias = $this->escalacao_model->get_midfield_offensive_players();
        $atacantes = $this->escalacao_model->get_forward_players();

        echo '<b>' . $goleiros['Posicao'] . ' - '. $goleiros['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $goleiros['Jogadores'][0];
        }  

        echo '<br><br><b>' . $laterais['Posicao'] . ' - '. $laterais['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $laterais['Jogadores'][0] . '<br>';
            echo $laterais['Jogadores'][1];
        }  

        echo '<br><br><b>' . $zagueiros['Posicao'] . ' - '. $zagueiros['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $zagueiros['Jogadores'][0] . '<br>';
            echo $zagueiros['Jogadores'][1];
        } 
        
        echo '<br><br><b>' . $volantes['Posicao'] . ' - '. $volantes['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $volantes['Jogadores'][0] . '<br>';
            echo $volantes['Jogadores'][1];
        } 
        
        echo '<br><br><b>' . $meias['Posicao'] . ' - '. $meias['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $meias['Jogadores'][0] . '<br>';
            echo $meias['Jogadores'][1];
        }         

        echo '<br><br><b>' . $atacantes['Posicao'] . ' - '. $atacantes['Pontos'] .' pontos</b><br>';
        
        for ($x = 0; $x < 1; $x++) 
        {
            echo $atacantes['Jogadores'][0] . '<br>';
            echo $atacantes['Jogadores'][1];
        } 

        $pontos = $goleiros['Pontos'] + $laterais['Pontos'] + $zagueiros['Pontos'] + $volantes['Pontos'] + $meias['Pontos'] + $atacantes['Pontos'];

        echo '<br><br><b>' . $pontos . ' pontos - Numero máximo de 35</b>';
    }

    //
    // Upload de foto
    //
    public function upload()
    {
        $config['upload_path']        =   'assets/upload';
        $config['allowed_types']      =   'jpg|jpeg|JPEG|JPG';
        $config['max_size']           =   1000;
        $config['file_ext_tolower']   =   TRUE;
        $config['file_name']          =   $this->session->userdata('IdEstudante') . '777' . rand() . '.jpg';

        $this->load->library('upload', $config);

        //
        // Chama a rotina de Upload
        //
        if(!$this->upload->do_upload('FileAvatar'))
        {
            $error = array('error' => $this->upload->display_errors());

            $this->data['error'] = $error;

            $this->usable('home');
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            $this->session->set_userdata(array('Foto' => $config['file_name']));

            $this->data['error'] = "";

            //
            // Persiste a nova imagem no banco de dados
            //
            $Data['Id']      =   $this->session->userdata('Id');
            $Data['Foto']    =   $config['file_name'];

            $this->perfil_model->update_cracha($Data);

            $this->usable('home');
        }
    }
    
    //
    // Artilheiros da Home
    //    
    public function artilheiros_home($Temporada)
    {
        echo $this->competicoes_model->Widget_Artilheiros($Temporada, 'Home');
    }
  
    //
    // Jogos da Home
    //    
    public function jogos_home($Temporada)
    {
        echo $this->competicoes_model->Widget_Matches($Temporada);
    }
}