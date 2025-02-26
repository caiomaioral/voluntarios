<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class MY_Upload extends CI_Upload
{
    /**
     * Force filename to lowercase
     *
     * @var string
     */
    public $file_ext_tolower = TRUE;
    public $file_name_tolower = TRUE;


    function _prep_filename($filename)
    {
        if ($this->allowed_types === '*' OR ($ext_pos = strrpos($filename, '.')) === FALSE)
        {
            return $filename;
        }

        $ext = substr($filename, $ext_pos);
        $filename = substr($filename, 0, $ext_pos);

        //change ext tolower
        $filename = ($this->file_name_tolower)?strtolower($filename):$filename;
        //change ext tolower
        $ext = ($this->file_ext_tolower)?strtolower($ext):$ext;

        return str_replace('.', '_', $filename).$ext;
    }
}