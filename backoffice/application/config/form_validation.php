<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(

    /*
     * Validacao do formulário de login
     */
    'login/enviar' => array(

        array('field'=>'login',
              'rules'=>'trim|required|callback_autenticar',
	        'label'=>'<strong>LOGIN</strong>'),

        array('field'=>'pwd',
              'rules'=>'trim|required|min_length[5]|max_length[20]',
	        'label'=>'<strong>SENHA</strong>')
    ),

    /*
     * Validacao do formulário de perfil
     */
    'perfil/salvar' => array(

        array('field'=>'str_Senha',
              'rules'=>'trim|required|min_length[5]|max_length[20]|callback_confirm_senha',
	        'label'=>'<strong>SENHA ATUAL</strong>'),

        array('field'=>'str_Senha_Nova',
              'rules'=>'trim|required|min_length[5]|max_length[20]|callback_password_check[str_Senha]',
              'label'=>'<strong>NOVA SENHA</strong>')
    ),
	
    /*
     * Validacao do formulário de inclusão de eventos
     */
    'eventos/enviar_evento' => array(

        array('field'=>'Igreja',
              'rules'=>'required|greater_than[0]',
              'label'=>'<strong>IGREJA</strong>'),      

        array('field'=>'str_Titulo',
              'rules'=>'trim|required',
              'label'=>'<strong>TÍTULO</strong>')			  			  
    ),

    /*
     * Validacao do formulário de inclusão de eventos
     */
    'links/enviar_evento' => array(

      array('field'=>'Eventos',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>EVENTOS</strong>'),

      array('field'=>'str_Titulo',
            'rules'=>'trim|required',
            'label'=>'<strong>TÍTULO</strong>'),

      array('field'=>'str_Url',
            'rules'=>'trim|required|callback_uniqueURL',
            'label'=>'<strong>URL AMIGÁVEL</strong>'),

      array('field'=>'int_Gateway',
            'rules'=>'trim|required|greater_than[0]',
            'label'=>'<strong>PARCELAS</strong>'),               

      array('field'=>'editor',
            'rules'=>'trim|required',
            'label'=>'<strong>DESCRIÇÃO DO EVENTO</strong>'),             

      array('field'=>'Data_Evento',
            'rules'=>'trim|required',
            'label'=>'<strong>DATA DO EVENTO</strong>'), 
            
      array('field'=>'Data_Limite',
            'rules'=>'trim|required',
            'label'=>'<strong>DATA LIMITE PARA INSCRIÇÃO</strong>'),

      array('field'=>'str_Valor',
            'rules'=>'trim|required',
            'label'=>'<strong>VALOR DO EVENTO</strong>'),

      array('field'=>'int_Limite',
            'rules'=>'trim|required|numeric|greater_than[0]',
            'label'=>'<strong>LIMITE DE INSCRITOS</strong>'),

      array('field'=>'int_Adicionais',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>ADICIONAIS PARA PAGAMENTO</strong>'),        
      
      array('field'=>'str_Arquivo',
            'rules'=>'callback_handle_upload')			  			  
      ),    
      

    /*
     * Validacao do formulário de inclusão de eventos
     */
    'eventos/salvar_evento' => array(

      array('field'=>'Igreja',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>IGREJA</strong>'),

      array('field'=>'str_Titulo',
            'rules'=>'trim|required',
            'label'=>'<strong>TÍTULO</strong>')			  			  
      ),      

    /*
     * Validacao do formulário de alteração de eventos
     */
    'links/salvar_evento' => array(

      array('field'=>'Eventos',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>EVENTOS</strong>'),        
      
        array('field'=>'str_Titulo',
              'rules'=>'trim|required',
              'label'=>'<strong>TÍTULO</strong>'),

        array('field'=>'str_Url',
              'rules'=>'trim|required',
              'label'=>'<strong>URL AMIGÁVEL</strong>'),

        array('field'=>'int_Gateway',
              'rules'=>'trim|required|greater_than[0]',
              'label'=>'<strong>PARCELAS</strong>'),               

        array('field'=>'editor',
              'rules'=>'trim|required',
              'label'=>'<strong>DESCRIÇÃO DO EVENTO</strong>'),             
  
        array('field'=>'Data_Evento',
              'rules'=>'trim|required',
              'label'=>'<strong>DATA DO EVENTO</strong>'), 

        array('field'=>'Data_Limite',
              'rules'=>'trim|required',
              'label'=>'<strong>DATA LIMITE PARA PAGAMENTO</strong>'),

        array('field'=>'str_Valor',
              'rules'=>'trim|required',
              'label'=>'<strong>VALOR DO EVENTO</strong>'),

        array('field'=>'int_Limite',
              'rules'=>'trim|required|numeric|greater_than[0]',
              'label'=>'<strong>LIMITE DE INSCRITOS</strong>'),

        array('field'=>'int_Adicionais',
              'rules'=>'required|greater_than[0]',
              'label'=>'<strong>ADICIONAIS PARA PAGAMENTO</strong>')			  			  			  
    ),

    /*
     * Validacao do formulário de alteração de eventos
     */
    'links/salvar_duplicar_evento' => array(

      array('field'=>'Eventos',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>EVENTOS</strong>'),        
      
        array('field'=>'str_Titulo',
              'rules'=>'trim|required',
              'label'=>'<strong>TÍTULO</strong>'),

        array('field'=>'str_Url',
              'rules'=>'trim|required',
              'label'=>'<strong>URL AMIGÁVEL</strong>'),

        array('field'=>'int_Gateway',
              'rules'=>'trim|required|greater_than[0]',
              'label'=>'<strong>PARCELAS</strong>'),               

        array('field'=>'editor',
              'rules'=>'trim|required',
              'label'=>'<strong>DESCRIÇÃO DO EVENTO</strong>'),             
  
        array('field'=>'Data_Evento',
              'rules'=>'trim|required',
              'label'=>'<strong>DATA DO EVENTO</strong>'), 

        array('field'=>'Data_Limite',
              'rules'=>'trim|required',
              'label'=>'<strong>DATA LIMITE PARA PAGAMENTO</strong>'),

        array('field'=>'str_Valor',
              'rules'=>'trim|required',
              'label'=>'<strong>VALOR DO EVENTO</strong>'),

        array('field'=>'int_Limite',
              'rules'=>'trim|required|numeric|greater_than[0]',
              'label'=>'<strong>LIMITE DE INSCRITOS</strong>'),

        array('field'=>'int_Adicionais',
              'rules'=>'required|greater_than[0]',
              'label'=>'<strong>ADICIONAIS PARA PAGAMENTO</strong>')			  			  			  
    ),    

    /*
     * Validacao do formulário de inclusão de usuários
     */
    'usuarios/enviar' => array(

      array('field'=>'str_Nome',
            'rules'=>'trim|required',
            'label'=>'<strong>NOME DO USUÁRIO</strong>'),

      array('field'=>'str_Login',
            'rules'=>'trim|required',
            'label'=>'<strong>LOGIN DO USUÁRIO</strong>'),

      array('field'=>'str_Senha',
            'rules'=>'trim|required',
            'label'=>'<strong>SENHA DO USUÁRIO</strong>'),              

      array('field'=>'str_Evento',
            'rules'=>'required|numeric|greater_than[0]',
            'label'=>'<strong>SELECIONE O EVENTO PARA VINCULAR AO USUÁRIO</strong>')			  			  
  ),
    
  /*
   * Validacao do formulário de alteração de usuários
   */
  'usuarios/salvar' => array(

      array('field'=>'str_Nome',
            'rules'=>'trim|required',
            'label'=>'<strong>NOME DO USUÁRIO</strong>'),

      array('field'=>'str_Login',
            'rules'=>'trim|required',
            'label'=>'<strong>LOGIN DO USUÁRIO</strong>'),

      array('field'=>'str_Senha',
            'rules'=>'trim|required',
            'label'=>'<strong>SENHA DO USUÁRIO</strong>'),              

      array('field'=>'str_Evento',
            'rules'=>'required|numeric|greater_than[0]',
            'label'=>'<strong>SELECIONE O EVENTO PARA VINCULAR AO USUÁRIO</strong>')			  			  			  
   ),  
   
    /*
     * Validacao do formulário de alteração de inscritos
     */
    'participantes/salvar_inscrito' => array(

      array('field'=>'str_Nome',
            'rules'=>'trim|required',
            'label'=>'<strong>NOME</strong>'),

      array('field'=>'str_CPF',
            'rules'=>'trim|required|callback_valida_cpf|callback_duplicidade[id_Evento, str_CPF]',
            'label'=>'<strong>CPF</strong>')    
    ),

    /*
     * Validacao do formulário de alteração de dependentes
     */
    'eventos/salvar_dependente' => array(

      array('field'=>'str_Nome',
            'rules'=>'trim|required',
            'label'=>'<strong>NOME</strong>'),

      array('field'=>'str_CPF',
            'rules'=>'trim|required|callback_valida_cpf',
            'label'=>'<strong>CPF</strong>')
    ),          

    /*
     * Validacao do formulário de check-in
     */
    'presenca/checkin_cpf' => array(

      array('field'=>'Eventos',
            'rules'=>'required|greater_than[0]',
            'label'=>'<strong>EVENTO</strong>'),

      array('field'=>'CPF',
            'rules'=>'trim|required|numeric|callback_valida_cpf',
            'label'=>'<strong>CPF</strong>')
    ),
    
    /*
     * Validacao do formulário de alteração de valor de cartão de crédito
     */
    'financeiro/salvar_pedido' => array(
      
      array('field'=>'fl_Valor',
            'rules'=>'trim|required',
            'label'=>'<strong>VALOR DO CARTÃO</strong>'),
      ), 
      
    /*
     * Validacao do formulário da venda de balcão
     */
    'gateway/enviar_participante_evento' => array(
      
      array('field'=>'Nome_Adicional',
            'rules'=>'required',
            'label'=>'<strong>NOME DO PARTICIPANTE</strong>'),

      array('field'=>'CPF_Adicional',
            'rules'=>'required',
            'label'=>'<strong>CPF DO PARTICIPANTE</strong>')            
      ),
      
    /*
     * Validacao do formulário da venda de balcão
     */
    //'financeiro/salvar_pedido' => array(
    //  
    //  array('field'=>'CPF',
    //        'rules'=>'callback_valida_status[CPF]',
    //        'label'=>'<strong>STATUS DO PAGAMENTO</strong>')            
    //  ),      
);