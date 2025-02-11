<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if(!class_exists('phplot'))
{
	require_once(BASEPATH . 'libraries/phplot' . EXT);
}

$obj =& get_instance();
$obj->phplot = new PHPlot(800, 400);
$obj->ci_is_loaded[] = 'phplot';

?>