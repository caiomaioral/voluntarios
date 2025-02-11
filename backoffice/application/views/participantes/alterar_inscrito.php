<?php 

echo $Themes;
echo $CssProjects; 
echo $Javascripts; 

echo $AddCss; 
echo $AddJavascripts; 

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Alterar Dependente</h3>
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
                            <a href="<?php echo base_url(); ?>eventos/alterar_inscrito/<?php echo $DataBody->id_Inscrito; ?>">Retornar ao Inscrito</a>
                        </li>                      
                        <li>
                            Alterar Dependente
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

                    echo form_open(base_url() . 'eventos/salvar_dependente', $attributes); 

                    ?>

                        <?php $Disabled = ($TipoUsuario == 1)? 'disabled' : ''; ?>
                    
                        <p id="validation_error"></p>
                        
                        <table border="0" cellspacing="2" cellpadding="6">
                        <tr>
                            <td height="20">Nome:</td>
                        </tr>
                        <tr>
                            <td><input id="str_Nome" name="str_Nome" type="text" size="40" value="<?php echo set_value('str_Nome', $DataBody->str_Nome); ?>" <?php echo $Disabled; ?> /></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td height="20">CPF:</td>
                        </tr>
                        <tr>
                            <td><input id="str_CPF" name="str_CPF" type="text" size="12" value="<?php echo set_value('str_CPF', $DataBody->str_CPF); ?>" <?php echo $Disabled; ?> /></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td><input name="bt_Salvar" type="submit" value="Salvar" <?php echo $Disabled; ?> /></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td class="msg_error"><?php echo validation_errors(); ?></td>
                        </tr>                         
                        </table>
                        
                        <input id="ID_Inscrito" name="ID_Inscrito" type="hidden" value="<?php echo $DataBody->id_Inscrito; ?>" />
                        <input id="ID_Dependente" name="ID_Dependente" type="hidden" value="<?php echo $DataBody->id_Dependente; ?>" />
                        
                    <?php echo form_close(); ?>
                    
                </div>
            </td>
        </tr>
        </table>
	</td>
</tr>
</table>