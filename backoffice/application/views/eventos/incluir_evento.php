<?php 

echo $AddCss; 
echo $AddJavascripts; 

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Incluir Evento (Bola de Neve Eventos)</h3>
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
                            <a href="<?php echo base_url(); ?>eventos">Pesquisar Eventos</a>
                        </li>
                        <li>
                            Incluir Evento
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

                        echo form_open_multipart(base_url() . 'eventos/enviar_evento', $attributes); 
                    
                    ?>
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="20">Qual igreja pertence esse evento?</td>
                    </tr>                    
                    <tr>
                    	<td>
                            <?php
                            
                                $js = 'id="Eventos" placeholder="Busque por uma igreja..." style="width:600px;"';
                                
                                echo form_dropdown('Igreja', $AddIgreja, set_value('Igreja', ''), $js);                            
                            
                            ?>                          
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>                      
                    <tr>
                        <td height="20">Título para identificação do evento:</td>
                    </tr>
                    <tr>
                        <td><input id="str_Titulo" name="str_Titulo" type="text" size="70" value="<?php echo set_value('str_Titulo'); ?>" /></td>
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
                    
                    <?php echo form_close(); ?>
                    
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>