<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logoff extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('session');
        $this->session->sess_destroy();
        $this->session->unset_userdata($this->session->all_userdata());
        
        redirect(base_url() . 'login');
    }
}