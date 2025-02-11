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
	        <h3 class="Play">Lotes de Eventos Cadastrados</h3>
    	</div>    
     </td>
</tr>

<?php 

if($this->router->class != 'financeiro')
{
    if($this->session->userdata('intTipo') == 0){ 
    
?>

<tr>
     <td style="padding-top: 2px">
        
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
                <div class="td_Corpo">

                    <input id="bt_Incluir" name="bt_Incluir" type="button" value="Cadastrar novo lote para um Evento" />

                    <a id="incluir" href="<?php echo base_url(); ?>links/incluir_eventos"></a>
                    
                </div>
            </td>
        </tr>
        </table>
        
    </td>
</tr>
<tr>
    <td height="10"></td>
</tr>

<?php }else{ ?>
    
    <tr>
        <td height="4"></td>
    </tr>

<?php 
    
    }
} 

?>
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
                    <th width="580" height="25">Título</th>
                    <th width="80" height="25">Ministério</th>
                    <th width="80" height="25">Valor</th>
                    <th width="120" height="25">Limite</th>
                    <th width="120" height="25">Vendidos</th>
                    <th width="80" height="25">Validade</th>
                    <th width="90" height="25">Status</th>
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
    <br />
    <p>Deseja apagar o link de evento?</p>
    <br />
    <p><strong>Atenção, esta ação é irreversível!</strong></p>
</div>