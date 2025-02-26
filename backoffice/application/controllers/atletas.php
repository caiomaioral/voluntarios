<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atletas extends MY_Controller {

    private $atletas  =  'tb_atletas';

    public function __construct()
    {
        parent::__construct();

		//
		// Validando se pode ou não acessar a pagina
		//
		if($this->session->userdata('Admin') == 0)
        {
            redirect(base_url() . 'home');
        }        
		
        $this->load->model('atleta_model');
        $this->load->model('pagamento_model');
    }
	
	//
	// Metodo que chama view de atletas 
	//
    public function index()
    {
        $this->data['AddCss']   	    =   load_css(array('demo_page', 'demo_table', 'demo_table_jui'));
        $this->data['AddJavascripts']   =   load_js(array('jquery.dataTables', 'dataTables.fnReloadAjax', 'atletas/atletas'));

        $this->usable('atletas');
    }

    //
    // Lista os atletas
    //	    
    public function listar_atletas_ajax()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('Id', 'Nome', 'DataNascimento', 'Email', 'Posicao', 'Inativo');
        
        // DB table to use
        $sTable = $this->atletas;
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
                
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
            }
            
            $this->db->where("(". $aColumns[1] ." LIKE '%". $sSearch ."%' || ". $aColumns[2] ." LIKE '%". $sSearch ."%' || ". $aColumns[3] ." LIKE '%". $this->db->escape_like_str(str_replace('-', '', str_replace('.', '', $sSearch))) ."%' || ". $aColumns[4] ." LIKE '%". $sSearch ."%')");
        }        
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        
        $rResult = $this->db
                        ->order_by('Nome', 'ASC')                        
                        ->get($sTable);

        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($this->atletas);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => intval($iFilteredTotal),
            'aaData' => array()
        );
        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $Inativo = ($aRow['Inativo'] == 1)? '<font color="red"><strong>Inativo</strong></font>' : '<font color="blue"><strong>Ativo</strong></font>';
                
                $row = array($aRow['Id'], mb_strtoupper($aRow['Nome']), data_us_to_br($aRow['DataNascimento']), mb_strtolower($aRow['Email']), mb_strtoupper($aRow['Posicao']), $Inativo);

                $row[] = $aRow[$col];
            }
        
            $output['aaData'][] = $row;
        }
    
        echo json_encode($output);
    }
    
	//
	// Metodo que chama a inclusão de atletas 
	//
    public function incluir()
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->usable('atletas/incluir');
    } 
    
    //
    // Metodo que salva a inclusão de atleta
    //
    public function enviar()
    {
        $this->form_validation->set_rules('Nome',                   '<strong>NOME</strong>', 				             'trim|required');
        $this->form_validation->set_rules('Nascimento',             '<strong>DATA DE NASCIMENTO</strong>',               'required|callback_isDate');
        $this->form_validation->set_rules('Email',                  '<strong>E-MAIL</strong>', 				             'trim|required|valid_email|xss_clean|callback_duplicateEmail[Email]');
        $this->form_validation->set_rules('Posicao',                '<strong>POSIÇÃO</strong>', 				         'required');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->incluir();
        }
        else
        {
            //
            // Tabela de Atleta
            //
            $Data['Nome']        	     =    mb_strtoupper($this->input->post('Nome', TRUE));
            $Data['DataNascimento']      =    data_br_to_us($this->input->post('Nascimento'));
            $Data['Apelido']        	 =    mb_strtoupper($this->input->post('Apelido', TRUE));
            $Data['Email'] 		         =    mb_strtolower($this->input->post('Email', TRUE));
            $Data['Posicao']        	 =    $this->input->post('Posicao');
            $Data['Senha']        	     =    '123456';
            $Data['Inativo']        	 =    0;
            $Data['Admin']        	     =    0;
            $Data['DataEnvioCadastro'] 	 =    date('Y-m-d H:i:s');	

            //
            // Include
            //
            $this->atleta_model->insert_atleta($Data);

            $IdAtleta = $this->db->insert_id();

            //
            // Manda o pagamento 
            //
            $this->gerar_pagamento_atleta($IdAtleta);

            //
            // Redireciona
            //
            redirect(base_url() . 'atletas');
        }
    }

    //
	// Metodo que chama a inclusão de atletas 
	//
    public function alterar($IdAtleta)
    {
        $this->data['AddCss']   	    =   load_css(array());
        $this->data['AddJavascripts']   =   load_js(array());

        $this->data['AddAtleta']	    =   $this->atleta_model->get_atleta($IdAtleta);

        $this->usable('atletas/alterar');
    } 
    
    //
    // Metodo que salva a alteração de atleta
    //
    public function salvar()
    {
        $this->form_validation->set_rules('Nome',                   '<strong>NOME</strong>', 				             'trim|required');
        $this->form_validation->set_rules('Nascimento',             '<strong>DATA DE NASCIMENTO</strong>',               'required|callback_isDate');
        $this->form_validation->set_rules('Email',                  '<strong>E-MAIL</strong>', 				             'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('Posicao',                '<strong>POSIÇÃO</strong>', 				         'required');
        
        // 
        // Chamada do submit
        //        
        if($this->form_validation->run() == FALSE)
        {
            $this->alterar($this->input->post('IdAtleta'));
        }
        else
        {
            //
            // Tabela de Atleta
            //
            $Data['Id']        	         =    $this->input->post('IdAtleta', TRUE);
            $Data['Nome']        	     =    mb_strtoupper($this->input->post('Nome', TRUE));
            $Data['DataNascimento']      =    data_br_to_us($this->input->post('Nascimento'));
            $Data['Apelido']        	 =    mb_strtoupper($this->input->post('Apelido', TRUE));
            $Data['Email'] 		         =    mb_strtolower($this->input->post('Email', TRUE));
            $Data['Posicao']        	 =    $this->input->post('Posicao');
            $Data['Inativo']        	 =    $this->input->post('Inativo', TRUE);
            $Data['DataEnvioCadastro'] 	 =    date('Y-m-d H:i:s');	

            //
            // Alterar
            //
            $this->atleta_model->update_atleta($Data);

            redirect(base_url() . 'atletas');
        }
    } 
    
    //
	// Metodo que chama a exclusão de atletas 
	//
    public function excluir($IdAtleta)
    {
        $this->atleta_model->excluir_atleta($IdAtleta);
    } 
    
    //
    // Metodo que gera o pagamento do ano atual
    //
    public function gerar_pagamento_atleta($IdAtleta)
    {
        foreach($this->pagamento_model->get_all_tipos() as $sAddTipos):

            $Data['IdAtleta']     =     $IdAtleta;
            $Data['Exercicio']    =     $sAddTipos->Id;
            $Data['Valor']        =     $sAddTipos->Valor;
            $Data['Pago']         =     0;
            $Data['Ano']          =     date('Y');
            
            $this->pagamento_model->insert_pagamento($Data);

        endforeach; 
    }     
    
    //
    // Metodo que gera o pagamento do ano atual
    //
    public function gerar_pagamento_elenco()
    {
        foreach($this->pagamento_model->get_all_players() as $sAddPayment):

            foreach($this->pagamento_model->get_all_tipos() as $sAddTipos):

                $Data['IdAtleta']     =     $sAddPayment->Id;
                $Data['Exercicio']    =     $sAddTipos->Id;
                $Data['Valor']        =     $sAddTipos->Valor;
                $Data['Pago']         =     0;
                $Data['Ano']          =     date('Y');
                
                $this->pagamento_model->insert_pagamento($Data);
    
            endforeach;

        endforeach;
 
    }

    //
    //  Metodo que verifica se ja tem um email no sistema
    //
    public function duplicateEmail($Email)
    { 
        if($this->atleta_model->check_duplicidade_email($Email) == true)
        { 
            $this->form_validation->set_message(__FUNCTION__, 'Esse <strong>E-MAIL</strong> já consta em nosso sistema.');
            
            return false; 
        } 
        else
        { 
            return true;
        } 
    }    
}