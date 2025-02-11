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
	        <h3 class="Play">Partipantes do Evento - <?php echo trim($AddData->titulo); ?></h3>
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
                            <a href="<?php echo base_url(); ?>links">Lotes</a>
                        </li>
                        <li>
                            Pesquisar Participantes
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

                    <table width="600">
                    <tr>
                        <td height="30"><h3>Módulo de Balcão</h3></td>
                        <td height="30"><h3>Módulo de Bilheteria</h3></td>
                    </tr>
                    <tr>
                        <td>
                            <input name="bt_Incluir" type="button" value="Cadastrar novo Participante" onclick="IncluirParticipante()" />
                            <a id="incluir" href="<?php echo base_url(); ?>gateway/incluir_participante"></a>
                        </td>
                        <td>
                            <input name="bt_Incluir" type="button" value="Clique para acessar o Módulo Bilheteria" onclick="IncluirBilheteria()" />
                            <a id="incluir" href="<?php echo base_url(); ?>eventos/incluir_bilheteria"></a>
                        </td>
                    </tr>
                    </table>

                </div>                

            </td>
        </tr>
        </table>

    </td>
</tr>
<tr>
    <td height="10"></td>
</tr>
<tr>
     <td style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
                <div class="td_Corpo">

                    <table width="800" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <td>Status de Pagamento:</td>
                    	<td>
							<?php
                            
                                $js = 'id="Status"';
                                
                                echo form_dropdown('Status', $AddStatus, set_value('Status', 'SELECIONE O STATUS PARA BUSCAR'), $js);                            
                            
                            ?>                          
                        </td>
                    	<td>Numero Pedido:</td>
                        <td width="130"><input id="NN" name="NN" type="text" /></td>
                        <td width="30"></td>
                        <td><input type="button" value="Pesquisar" class="pesquisar" /></td>
                    </tr>
                    </table>

                </div>
            </td>
        </tr>
        </table>
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
                    <th width="90" height="25">Ações</th>
                    <th width="380" height="25">Inscrito</th>
                    <th width="100" height="25">CPF</th>
                    <th width="120" height="25">Pedido</th>
                    <th width="120" height="25">Presença</th>
                    <th width="120" height="25">Data e Hora Check-in</th>
                    <th width="150" height="25">Data Cadastro</th>
                    <th width="150" height="25">Status</th>
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

<input id="id_evento" name="id_evento" type="hidden" value="<?php echo $AddData->id_evento_link; ?>" />

<input id="Perfil" name="Perfil" type="hidden" value="<?php echo $this->session->userdata('intTipo'); ?>" />

<?php echo form_close(); ?>

<!-- delete confirmation dialog box -->
<div id="delConfDialog" title="Confirma&ccedil;&atilde;o de Exclus&atilde;o">
    <br />
    <p>Deseja apagar o inscrito?</p>
    <br />
    <p><strong>Atenção, esta ação é irreversível!</strong></p>
</div>