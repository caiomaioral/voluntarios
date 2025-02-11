<?php

echo $AddCss;
echo $AddJavascripts;

$attributes = array('id' => 'FormY', 'name' => 'FormY');

echo form_open(base_url(), $attributes); 

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
	        <h3 class="Play">Usuários Cadastrados</h3>
    	</div>    
     </td>
</tr>
<tr>
     <td style="padding-top: 2px">
		
        <?php if($this->session->userdata('intTipo') == 0){ ?>

            <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" style="margin:0;padding:0">
                    <div class="td_Corpo">

                        <input name="bt_Incluir" type="button" value="Cadastrar novo Usuário" onclick="IncluirUsuario()" />

                        <a id="incluir" href="<?php echo base_url(); ?>eventos/incluir"></a>
                        
                    </div>
                </td>
            </tr>
            </table>
            
        <?php } ?>
    </td>
</tr>
<tr>
    <td height="10"></td>
</tr>
<tr>
    <td>
        <div id="container" style="height: auto">
            <div class="demo_jui" style="height: auto">
            
            	<table id="examples" width="100%" cellpadding="0" cellspacing="0" border="0" class="display">
                <thead>
                <tr>
                    <th width="70" height="25">Ações</th>
                    <th width="270" height="25">Login</th>
                    <th width="380" height="25">Evento</th>
                    <th width="90" height="25">Data de Cadastro</th>
                </tr>
                </thead>
                <tbody>
                <tr class="gradeA">
                    
                </tr>
                </tbody>
            	</table>
                
            </div>
        </div>

        <!-- CARREGANDO -->
        <div id="carregando"></div>

        <!-- PALCO -->
        <div id="palco"></div>

    </td>
</tr>
</table>

<input id="Perfil" name="Perfil" type="hidden" value="<?php echo $this->session->userdata('intTipo'); ?>" />

<?php echo form_close(); ?>

<!-- delete confirmation dialog box -->
<div id="delConfDialog" title="Confirma&ccedil;&atilde;o de Exclus&atilde;o">
    <p>Tem certeza que deseja apagar o registro?</p>
</div>