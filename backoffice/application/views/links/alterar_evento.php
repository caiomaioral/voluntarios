<?php 

echo $AddCss; 
echo $AddJavascripts; 

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Alterar link para Evento</h3>
    	</div>    
     </td>
</tr>
<tr>
    <td style="padding-top: 2px; padding-bottom: 10px">

        <div class="td_Corpo">
            <div class="breadCrumbHolder module">
                <div id="breadCrumb3" class="breadCrumb module">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>home">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>links">Pesquisar Eventos</a>
                        </li>
                        <li>
                            Alterar Evento
                        </li>
                    </ul>
                </div>
            </div>
        </div>
            
    </td>
</tr>
<tr>
     <td style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">

                <div class="td_Corpo">

                    <?php 
                    
                        $attributes = array('id' => 'FormW', 'name' => 'FormW');

                        echo form_open_multipart(base_url() . 'links/salvar_evento', $attributes); 
                    
                    ?>
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="5"></td>
                    </tr>                    
                    <tr>
                        <td height="20">Selecione o Evento para vincular ao link:</td>
                    </tr>                    
                    <tr>
                    	<td>
                            <?php
                            
                                $js = 'id="Eventos" placeholder="Busque por um evento..." style="width:600px;"';
                                
                                echo form_dropdown('Eventos', $AddEventos, set_value('Eventos', $DataBody->id_evento), $js);                            
                            
                            ?>                          
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>                     
                    <tr>
                        <td height="20">Título para aparecer na hora do pagamento:</td>
                    </tr>
                    <tr>
                        <td><input id="str_Titulo" name="str_Titulo" type="text" size="70" value="<?php echo set_value('str_Titulo', $DataBody->titulo); ?>" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">URL Amigável (Exemplo: <em><strong>barca-mais ou mulheres-do-bola</strong></em>):</td>
                    </tr>
                    <tr>
                        <td><input id="str_Url" name="str_Url" type="text" size="50" value="<?php echo set_value('str_Url', $DataBody->url); ?>" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Definir quantidade de parcelas:</td>
                    </tr>                    
                    <tr>
                        <td>
                            <?php

                                $js = 'id="int_Gateway"';

                                echo form_dropdown('int_Gateway', $AddGateway, set_value('int_Gateway', $DataBody->parcelas), $js);                            
                            ?>  
                                                       
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>  
                    <tr>
                        <td height="20">Data do Evento:</td>
                    </tr>
                    <tr>
                    	<td><input id="Data_Evento" name="Data_Evento" type="text" size="11" value="<?php echo set_value('Data_Evento', data_us_to_br($DataBody->data_evento)); ?>" readonly="true" class="date" /></td>
                    </tr> 
                    <tr>
                        <td height="20"></td>
                    </tr>  
                    <tr>
                        <td height="20">Fim do Evento:</td>
                    </tr>
                    <tr>
                    	<td><input id="Fim_Evento" name="Fim_Evento" type="text" size="11" value="<?php echo set_value('Fim_Evento', data_us_to_br($DataBody->fim_evento)); ?>" readonly="true" class="date" /></td>
                    </tr>                                          
                    <tr>
                        <td height="20"></td>
                    </tr>  
                    <tr>
                        <td height="20">Data Limite para inscrição do site:</td>
                    </tr>
                    <tr>
                    	<td><input id="Data_Limite" name="Data_Limite" type="text" size="11" value="<?php echo set_value('Data_Limite', data_us_to_br($DataBody->validade)); ?>" readonly="true" class="date" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Descrição do evento:</td>
                    </tr>
                    <tr>
                        <td>

                            <textarea id="editor" name="editor" rows="4" cols="50"><?php echo set_value('editor', $DataBody->texto); ?></textarea>   

                        </td>
                    </tr>                                        
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Valor do evento:</td>
                    </tr>
                    <tr>
                        <td><input id="str_Valor" name="str_Valor" type="text" size="10" value="<?php echo set_value('str_Valor', num_to_user($DataBody->valor)); ?>" class="money" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Valor do transporte? Deixar 0,00 se não tiver.</td>
                    </tr>
                    <tr>
                        <td><input id="str_Transporte" name="str_Transporte" type="text" size="10" value="<?php echo set_value('str_Transporte', num_to_user($DataBody->transporte)); ?>" class="money" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Quantidade máxima de inscritos. Se colocar 200, somente 200 pessoas poderão se inscrever:</td>
                    </tr>                    
                    <tr>
                        <td><input id="int_Limite" name="int_Limite" type="text" size="10" value="<?php echo set_value('int_Limite', $DataBody->limite); ?>" /></td>                           
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Quantidade máxima de dependentes por pagantes. Se colocar 2, o inscrito só poderá pagar para ele e mais 1 pessoa:</td>
                    </tr>                   
                    <tr>
                        <td>
                            <?php

                                $js = 'id="int_Adicionais"';

                                echo form_dropdown('int_Adicionais', $AddAdicionais, set_value('int_Adicionais', $DataBody->adicionais), $js);                            
                            ?>                             
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>  
                    <tr>
                        <td height="20">Imagem do Cabeçalho (Recomendável usar no tamanho de 1110 × 332 px):</td>
                    </tr> 
                    <tr>
                        <td>
                            <div class="file-wrapper">
                                <input id="str_Arquivo" name="str_Arquivo" type="file" value="<?php echo set_value('str_Arquivo'); ?>" />
                                <span class="button">Selecione um arquivo</span>
                            </div>
                            
                            <input id="str_Arquivo_Pronto" name="str_Arquivo_Pronto" type="hidden" value="<?php echo $DataBody->header; ?>" />                    
                        </td>
                    </tr>                    
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td><input name="bt_Salvar" type="submit" value="Salvar Evento" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td class="msg_error"><?php echo validation_errors(); ?></td>
                    </tr> 
                    </table>
                    
                    <input id="ID" name="ID" type="hidden" value="<?php echo $DataBody->id_evento_link; ?>" />
                    
                    <?php echo form_close(); ?>
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>