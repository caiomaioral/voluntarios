<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }
	 
    public function index()
    {
        redirect(base_url() . 'eventos');
    }
    
    public function qrcode()
    {
        $this->load->library('ciqrcode');
        $this->load->library('email');

        $config['cacheable']    =  false; //boolean, the default is true
        $params['data']         =  'Turma burra para caramba esse povo do Bola de Neve bicho';
        $params['level']        =  'H';
        $params['size']         =  7;
        $params['savename']     =  'assets/upload/qrcode/' . 'tes00129.png';
        
        $this->ciqrcode->generate($params);
        
        $config['protocol']         =  'smtp';
        $config['smtp_host']        =  'smtp.boladeneve.com';
        $config['smtp_port']        =  587;
        $config['starttls']         =  TRUE;
        $config['smtp_user']        =  'pagamentos@boladeneve.com';
        $config['smtp_pass']        =  'EveNtoS777bdnRina';
        $config['smtp_timeout']     =  5;
        $config['newline']          =  "\r\n";
        $config['charset']          =  'utf-8';
        $config['mailtype']         =  'html';				

        $this->email->initialize($config);

        $mail_conteudo   = 	  '<style type="text/css">
                                        .lin:link {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                        .lin:active {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                        .lin:visited {text-decoration: none; font-weight: bold; color: #000000; font-size: 11px}
                                        .lin:hover {text-decoration: underline; font-weight: bold; color: #000000; font-size: 11px}
                                   </style>';		

        $mail_conteudo   .=   '<table width="100%" border="0" cellpadding="10" cellspacing="10" style="font-family: Verdana, Tahoma, sans-serif, serif; font-size:13px;background-color: #fff; color:#000;">
                               <tr><td style="padding-left: 3px"><img src="'.base_url().'assets/images/wmb_logo.jpg" width="100" height="100" /></td>
                               <tr><td style="padding-left: 1px; padding-bottom: 30px">';

        $mail_conteudo   .=   '<p>Ol&aacute; <strong>Caio Murillo Maioral</strong>, voc&ecirc; acabou de efetuar a inscrição para o evento <strong>Cangaíba Eterno</strong>.</p>

                               Boa meu amigo.

                               <p>O status do seu pedido foi alterado para: <strong>Aprovado</strong></p>
                               <p>Segue abaixo os seus dados:</p>
                               <p><strong>Numero do Pedido:</strong>&nbsp;0000001</p>
                               <p><strong>Nome Completo:</strong>&nbsp;Caio Murillo Maioral</p>
                               <p><strong>CPF:</strong> 287.269.258-88</p>
                               <p><strong>E-mail:</strong>&nbsp;caiomaioral@gmail.com</p>
                               <p><strong>Valor Total:</strong>&nbsp;R$&nbsp;30,00</p>    
                               <p><strong>Data matricula:</strong> '.date("d/m/Y - H:i:s").'</p>
                               <p>&nbsp;</p>                               
                               <img src="'.base_url().'assets/upload/qrcode/tes00129.png" />
                               <p>&nbsp;</p>';

        $mail_conteudo   .=   '<br /><br /><b>E-mail enviado em ' . date("d/m/Y - H:i:s") . '.</b>';
        $mail_conteudo   .=   '</td></tr></table>';

        $this->email->from('<pagamentos@boladeneve.com>', 'Bola de Neve Eventos');
        $this->email->to('caiomaioral@gmail.com');

        $this->email->subject('Inscrição para o Bola de Neve Eventos - Aprovado');
        $this->email->message($mail_conteudo);

        $rc = $this->email->send();        
    }
    
    public function qrcodesimple()
    {
        $this->load->library('ciqrcode');

        header('Content-Type: image/png');
        
        $config['cacheable']    =  false; //boolean, the default is true
        $params['data']         =  'Turma burra para caramba esse povo do Bola de Neve bicho';
        $params['level']        =  'H';
        $params['size']         =  7;
        
        $this->ciqrcode->generate($params);
    }    
}