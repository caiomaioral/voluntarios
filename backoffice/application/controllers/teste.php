<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teste extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    //
    // Página principal
    //
    public function index()
    {
        $this->data['error']  =  "";
        
        $this->usable_teste('teste');
    }
}