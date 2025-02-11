<?php

echo $AddCss;
echo $AddJavascripts;

$attributes = array('id' => 'FormX', 'name' => 'FormX');

echo form_open('', $attributes); 

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Relatório Financeiro vendas em Bilheteria</h3>
    	</div>    
     </td>
</tr>
<tr>
     <td style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
                <div class="td_Corpo">

                    <table width="550" border="0" cellspacing="2" cellpadding="6">
                    <tr>                        
                    	<td>
                            <input name="bt_Relatorio" type="button" value="Clique para Gerar o Relatório" onclick="SetReport()" />
                        </td>                        
                    </tr>
                    </table>
					
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<?php echo form_close(); ?>
