<?php

echo $AddCss;
echo $AddJavascripts;

?>

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
     <td colspan="2">
        <div class="td_Titulo">
	        <h3 class="Play">Controle de Presença - Bola de Neve Eventos</h3>
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
                        <td>Código:</td>
                        <td>
							<input id="Codigo" name="Codigo" type="password" size="76" />
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
        
		<div id="show"></div>
		
	</td>
</tr>
<tr>
    <td height="20"></td>
</tr>
</table>