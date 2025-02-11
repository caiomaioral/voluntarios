<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thumbs extends MY_Controller {
 
    public function __construct()
    {
        parent::__construct();
		
        $this->load->library('image_lib');
    }
 
    public function avatar($width, $height, $img)
    {
		$img = $img . '.jpg';
		
		// Checa se a imagem existe se não existir, usa uma imagem padrão
        $img = is_file('assets/upload/' . $img) ? $img : 'avatar.jpg';
		
        // Se a miniatura já existir, ela é que será usada
        // (não há necessidade de usar a GD library de novo)
        if (!is_file('assets/upload/' . $width . 'x' . $height . '_' . $img))
        {
            $config['source_image']    =   'assets/upload/' . $img;
            $config['new_image']       =   'assets/upload/' . $width . 'x' . $height . '_' . $img;
            $config['width']           =   $width;
            $config['height']          =   $height;

			$this->image_lib->initialize($config);
            $this->image_lib->resize();
        }
        
        header('Content-Type: image/jpg');
		readfile('assets/upload/' . $width . 'x' . $height . '_' . $img);
    }
}