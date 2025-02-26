<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Feedback_model extends CI_Model {
	
    private $feedbacks   =  'tb_feedbacks';
	
    public function __construct()
    {
        parent::__construct();
    } 

    //
    // Incluir o registro do feedback
    //
    public function insert_feedback($Data)
    {
        $this->db->insert($this->feedbacks, $Data);
    }      		
}

?>
