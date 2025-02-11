<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

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
            <h3 class="Play">Relatório de Participantes por Evento</h3>
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
                        <td height="40"width="180"></td>
                    	<td>
                            <?php
                            
                                $js = 'id="Eventos" placeholder="Busque por um evento..." style="width:600px;"';
                                
                                echo form_dropdown('Eventos', $AddEventos, set_value('Eventos', ''), $js);                            
                            
                            ?>                          
                        </td>
                    </tr>
                    <tr><td height="10"></td></tr>
                    <tr>
                        <td height="40"width="180"></td>
                    	<td id="AjaxLotes">
                            <?php
                            
                                $js = 'id="Lotes_Eventos" placeholder="Busque por um lote de evento..." style="width:600px;"';
                                
                                echo form_dropdown('Lotes_Eventos', array(0 => 'SELECIONE O LOTE DE EVENTO PARA FILTRAR'), set_value('Lotes_Eventos', ''), $js);                            
                            
                            ?>                          
                        </td>
                    </tr>
                    <tr><td height="15"></td></tr>                    
                    <tr>                        
                        <td>Situação dos Inscritos</td>
                    	<td>
                            <select id="Situacao" name="Situacao">
                            <option value="0">SELECIONE A SITUAÇÃO</option>
                            <option value="1">PENDENTES</option>
                            <option value="2">QUITADOS</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td height="15"></td></tr>
                    <tr>                        
                        <td>Data de Inicio</td>
                        <td><input id="Inicio" name="Inicio" type="text" /></td>
                    </tr>
                    <tr><td height="15"></td></tr>
                    <tr>
                        <td>Data de Fim:</td>
                        <td><input id="Fim" name="Fim" type="text" /></td>                    
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>                        
                        <td width="50"></td>
                    	<td>
                            <input name="bt_Relatorio" type="button" value="Clique para Gerar o Relatório" onclick="SetReport()">
                        </td>                        
                    </tr>
                    </table>

                    <input id="Formato" name="Formato" type="hidden" value="2">
					
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<?php echo form_close(); ?>
