<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<?php

echo $AddCss;
echo $AddJavascripts;

?>

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
     <td colspan="2">
        <div class="td_Titulo">
	        <h3 class="Play">Controle de Presença por CPF - Bola de Neve Eventos</h3>
    	</div>    
     </td>
</tr>
<tr>
     <td colspan="2" style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
            	<div class="td_Corpo">

                    <?php

                        $attributes = array('id' => 'FormX', 'name' => 'FormX');

                        echo form_open('', $attributes); 

                    ?>

                    <table width="820" border="2" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="40"width="180"></td>
                    	<td>
                            <?php
                            
                                $js = 'id="Eventos" placeholder="Busque por um evento..." style="width:600px;"';
                                
                                echo form_dropdown('Eventos', $AddEventos, set_value('Eventos', ''), $js);                            
                            
                            ?>                          
                        </td>
                    </tr>
                    <tr><td height="15"></td></tr>                    
                    <tr>
                        <td>CPF (Sem pontos e traços):</td>
                        <td>
							<input id="CPF" name="CPF" type="text" size="11" maxlength="11" value="<?php echo set_value('CPF'); ?>" />
                        </td>
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>                        
                        <td width="50"></td>
                    	<td>
                            <input id="checkin" name="bt_Relatorio" type="button" value="Clique para finalizar o check-in" />
                        </td>                        
                    </tr> 
                    </table>

                    <?php echo form_close(); ?>

                </div>
            </td>
        </tr>
        </table>
	</td>
</tr>
<tr>
    <td colspan="2" height="50"></td>
</tr>
<tr>
    <td colspan="2" align="center">
        
		<div id="show"><?php if(isset($GetCodigo)) echo $GetCodigo; ?></div>
		
	</td>
</tr>
<tr>
    <td height="20"></td>
</tr>
</table>