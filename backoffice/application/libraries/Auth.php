<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Auth {
	
	function _Auth() 
	{
		$this->ci =& get_instance();
	}
 
	function token()  
	{
		$token = md5(uniqid(rand(), true));
		$this->ci->session->set_userdata('Synctoken', $token);
		
		return $token;
	}	
}

?>