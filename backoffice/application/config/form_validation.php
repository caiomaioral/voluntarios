<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(

    //
    // Validacao do formulário de login
    //
    'login/enviar' => array(

        array('field'=>'login',
              'rules'=>'trim|required|callback_autenticar',
              'label'=>'<strong>LOGIN</strong>'),

        array('field'=>'pwd',
              'rules'=>'trim|required|min_length[5]|max_length[20]',
	        'label'=>'<strong>SENHA</strong>')
    ),

    //
    // Validacao do formulário de esqueci minha senha
    //
    'esqueci/enviar' => array(

        array('field'=>'email',
              'rules'=>'trim|required|callback_autenticar',
	        'label'=>'<strong>E-MAIL</strong>')
    ),

    //
    // Validacao do formulário de perfil
    //
    'perfil/salvar' => array(

        array('field'=>'OldPassword',
              'rules'=>'trim|required|callback_confirm_senha',
              'label'=>'<strong>SENHA ATUAL</strong>'),
			  
        array('field'=>'NewPassword',
              'rules'=>'trim|required|min_length[6]|max_length[20]|callback_password_check[NewPassword]',
              'label'=>'<strong>NOVA SENHA</strong>'),

        array('field'=>'ConfirmPassword',
              'rules'=>'trim|required|min_length[6]|max_length[20]|matches[NewPassword]',
              'label'=>'<strong>Confirmação de NOVA SENHA</strong>')
    ),

    //
    // Validacao do formulário de envio de feedback
    //
    'feedbacks/enviar' => array(

      array('field'=>'Sugestao',
            'rules'=>'trim|required',
            'label'=>'<strong>SUGESTÃO</strong>')
  ),    
);
