<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller {

    private $Participantes  =  'enr_Participantes';

    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('perfil_model');
        $this->load->model('elenco_model');
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['AddCss']  		     =   load_css(array('home/home'));
        $this->data['AddJavascripts']    =   load_js(array('colaboradores/colaboradores'));

        $this->usable_system('upload');
    }

    //
    // Lista os colaboradores
    //	    
    public function listar_ajax()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('Id', 'Nome', 'CPF', 'Email', 'Telefone', 'Termos', 'Data');
        
        // DB table to use
        $sTable = $this->Participantes;
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = 1; //$this->input->get_post('sEcho', true);
    
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
        $iTotal = $this->db->count_all($this->Participantes);
    
        // Output
        $output = array(
            'draw' => intval($sEcho),
            'recordsTotal' => $iTotal,
            'recordsFiltered' => intval($iFilteredTotal),
            'data' => array()
        );

        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $Termos = ($aRow['Termos'] == 1)? '<font color="red"><strong>Inativo</strong></font>' : '<font color="blue"><strong>Ativo</strong></font>';
                
                $row = array($aRow['Id'], mb_strtoupper($aRow['Nome']), $aRow['CPF'], mb_strtolower($aRow['Email']), $aRow['Telefone'], $Termos, $aRow['Data']);

                $row[] = $aRow[$col];
            }
        
            $output['data'][] = $row;
        }
    
        echo json_encode($output);
    }     
}