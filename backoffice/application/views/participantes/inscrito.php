<?php 

echo $AddCss; 
echo $AddJavascripts; 

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Alterar Inscrito</h3>
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
                            <a href="<?php echo base_url(); ?>links/visualizar/<?php echo $DataBody->id_Evento_Link; ?>">Retornar a pesquisa</a>
                        </li>
                        <li>
                            Alterar Inscrito
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

                        echo form_open(base_url() . 'participantes/salvar_inscrito', $attributes); 

                        $Disabled = ($TipoUsuario == 1)? 'disabled' : '';

                    ?>

                    <table width="100%" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="20">Nome do Inscrito:</td>
                    </tr>
                    <tr>
                        <td><input id="str_Nome" name="str_Nome" type="text" size="50" value="<?php echo set_value('str_Nome', $DataBody->Nome); ?>" <?php echo $Disabled; ?> /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">CPF do Inscrito:</td>
                    </tr>
                    <tr>
                        <td><input id="str_CPF" name="str_CPF" type="text" size="12" value="<?php echo set_value('str_CPF', $DataBody->CPF); ?>" <?php echo $Disabled; ?> /></td>
                    </tr>
                    <tr>
                        <td height="30"></td>
                    </tr>                     
                    <tr>
                        <td>

                        <!-- Botão para a alteração dos dados -->

                        <input name="bt_Salvar" type="submit" value="Salvar Dados" <?php echo $Disabled; ?> /> 
                        
                        </td>
                    </tr> 
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td class="msg_error">
                            
                            <?php echo $Message = ($TipoUsuario == 1)? '<p><strong>Seu usuário não permite alterações nessa tela.</strong></p>' : ''; ?>
                        
                            <?php echo validation_errors(); ?>
                        
                        </td>
                    </tr>                                                                               
                    </table>
                    
                    <input id="ID_Evento" name="ID_Evento" type="hidden" value="<?php echo $DataBody->id_Evento; ?>" />
                    <input id="ID_Evento_Link" name="ID_Evento_Link" type="hidden" value="<?php echo $DataBody->id_Evento_Link; ?>" />
                    <input id="ID_Inscrito" name="ID_Inscrito" type="hidden" value="<?php echo $DataBody->id_Inscrito; ?>" />
                    <input id="Status" name="Status" type="hidden" value="<?php echo $DataOrder->Status_Pagamento; ?>" />
                    
                    <?php echo form_close(); ?>
                </div>
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>