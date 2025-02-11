<?php

echo $AddCss;
echo $AddJavascripts;

?>
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
	        <h3 class="Play">Dados do Perfil</h3>
    	</div>    
     </td>
</tr>
<tr>
     <td style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
                <div class="td_Corpo">
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                    	<td valign="top">
                        
							<?php 
                            
                                $attributes = array('id' => 'FormX', 'name' => 'FormX');
                            
                                echo form_open(base_url() . 'perfil/salvar', $attributes); 
                            
                            ?>
                            <table width="100%" border="0">
                            <tr>
                                <td height="20">Nome:</td>
                            </tr>
                            <tr>
                                <td><input id="str_Nome" name="str_Nome" type="text" size="40" value="<?php echo $DataUser->strNome; ?>" readonly="readonly" /></td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td height="20">Login:</td>
                            </tr>
                            <tr>
                                <td><input id="str_Login" name="str_Login" type="text" size="30" value="<?php echo $DataUser->strLogin; ?>" readonly="readonly" /></td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td height="20">E-mail:</td>
                            </tr>
                            <tr>
                                <td><input id="str_Email" name="str_Email" type="text" size="40" value="<?php echo $DataUser->strEmail; ?>" /></td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td height="20">Senha Antiga:</td>
                            </tr>
                            <tr>
                                <td><input id="str_Senha" name="str_Senha" type="password" size="30" /></td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td height="20">Nova Senha:</td>
                            </tr>
                            <tr>
                                <td><input id="str_Senha_Nova" name="str_Senha_Nova" type="password" size="30" /></td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td><input name="bt_Salvar" type="submit" value="Salvar" /></td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td class="msg_error"><?php echo validation_errors(); ?></td>
                            </tr>
                            </table>
                            </form>
                            
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