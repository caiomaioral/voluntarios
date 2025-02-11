<?php 

echo $AddCss; 
echo $AddJavascripts; 

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
            <h3 class="Play">Incluir Usuário</h3>
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
                            <a href="<?php echo base_url(); ?>usuarios">Pesquisar Usuários</a>
                        </li>
                        <li>
                            Incluir Usuário
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

                        echo form_open(base_url() . 'usuarios/enviar', $attributes); 
                    
                    ?>
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="20">Nome do usuário:</td>
                    </tr>
                    <tr>
                        <td><input id="str_Nome" name="str_Nome" type="text" size="50" value="<?php echo set_value('str_Nome'); ?>" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Login (Deve ser um e-mail, pode ser um e-mail fictício Ex: usuario1@boladeneve.com):</td>
                    </tr>
                    <tr>
                        <td><input id="str_Login" name="str_Login" type="text" size="30" value="<?php echo set_value('str_Login'); ?>" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>  
                    <tr>
                        <td height="20">Senha (Definido como padrão 123mudar, e ao logar o usuário pode alterar):</td>
                    </tr>
                    <tr>
                    	<td><input id="str_Senha" name="str_Senha" type="text" size="20" value="123mudar" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td height="20">Evento onde o usuário poderá trabalhar:</td>
                    </tr>
                    <tr>
                        <td>
                        <?php
                            
                            $js = 'id="str_Evento" style="width:350px"';
                            
                            echo form_dropdown('str_Evento', $AddEventos, set_value('str_Evento', 'SELECIONE O EVENTO'), $js);                            
                        
                        ?>                          
                        </td>
                    </tr>
                    <tr>
                        <td height="40"></td>
                    </tr>
                    <tr>
                        <td><input name="bt_Salvar" type="submit" value="Criar Usuário" /></td>
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