<?php

echo $AddJavascripts;

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
	        <h3 class="Play">Venda de Bilheteria</h3>
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
                            <a href="<?php echo base_url(); ?>links">Eventos</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>links/visualizar/<?php echo $AddBody->id_evento_link; ?>"><?php echo ucwords(mb_strtolower($AddBody->titulo)); ?></a>
                        </li>
                        <li>
                            Incluir venda de entradas
                        </li>
                    </ul>
                </div>
            </div>    
		</div>
            
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

                    <div id="headerSite">
                        <img src="https://eventos.boladeneve.com/admin/assets/upload/<?php echo $AddBody->header; ?>" width="1110" height="332">
                    </div>

                    <div id="conteudoSite">
                        <div id="ResultAjax">
                            <div id="BodyHome">
                                
                                <?php 
                                
                                    $attributes = array('id' => 'FormX', 'name' => 'FormX');
                                
                                    echo form_open(base_url() . 'bilheteria/enviar_bilheteria_evento', $attributes); 
                                
                                ?>
                                
                                <div id="ResultRequire" class="containerSite">
                                    
                                    <br />
                                    
                                    <div id="ResultValidation" class="field-validation-valid validation"></div>
                                            
                                    <table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr>
                                        <td bgcolor="#FFFFFF" style="padding-left:10px; padding-right:10px; padding-top:8px; padding-bottom:14px">

                                            <table width="100%" border="0" cellpadding="3" cellspacing="2">
                                            <tr>
                                                <td style="padding-left:6px">Forma de Pagamento</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">

                                                    <select id="sel_Pagamento" name="sel_Pagamento">
                                                    <option value="" <?php echo set_select('sel_Pagamento', ''); ?>>Selecione por favor</option>
                                                    <option value="3" <?php echo set_select('sel_Pagamento', '3'); ?>>Dinheiro</option>
                                                    <option value="1" <?php echo set_select('sel_Pagamento', '1'); ?>>Cartão de Crédito</option>
                                                    <option value="4" <?php echo set_select('sel_Pagamento', '4'); ?>>Cartão de Débito</option>
                                                    </select>
                                                    
                                                    <?php echo form_error('sel_Pagamento', '<span class="field-validation-valid validation" data-valmsg-for="sel_Pagamento" data-valmsg-replace="true">', '</span>'); ?>

                                                </td>
                                            </tr>                                            
                                            </table>
                                        </td>
                                    </tr>
                                    </table><br />
                                    
                                    <table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr bgcolor="#d9d9d9">

                                        <td height="30" align="left" style="padding-left:10px;padding-top:5px;padding-bottom:5px">
                                            <table align="left" border="0">
                                            <tr>
                                                <td style="padding-left:6px;padding-bottom:10px">

                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td height="40"><font style="font-size:14px"><strong>Quantidade de Bilhetes</strong></font><br />Selecione a quantidade de bilhetes para compra.</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select id="sel_Adicionais" name="sel_Adicionais">
                                                            <?php for($x = 1; $x <= 10; $x++){ ?>

                                                                    <?php $legenda = ($x == 1)? 'bilhete' : 'bilhetes'; ?>

                                                                    <option value="<?php echo $x; ?>" <?php if($x == 1) 'selected' ?>><?php echo $x . ' ' . $legenda; ?></option>
                                                            
                                                            <?php } ?>
                                                            
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="ResultAdd" bgcolor="#FFFFFF" style="padding-left:6px; padding-right:8px; padding-top:12px; padding-bottom:4px">


                                        </td>
                                    </tr>
                                    </table><br />

                                    <table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr bgcolor="#d9d9d9">

                                        <td height="30" align="left" style="padding-left:6px;padding-bottom:5px">
                                            <table align="left" border="0">
                                            <tr>
                                                <td style="padding-left:10px">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td height="55">
                                                            <div id="ValorDisplay">
                                                                <font style="font-size:20px"><strong>R$ <?php echo num_to_user($AddBody->valor); ?></strong></font>
                                                                <input id="ValorTotal" name="ValorTotal" type="hidden" value="<?php echo $AddBody->valor; ?>"><br />Valor total a ser pago.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    </table><br /><br />                    
                                    
                                    <table border="0" align="center" width="100%" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr bgcolor="#d9d9d9">
                                        <td height="80" align="center" style="padding-right:5px">
                                            <table border="0">
                                            <tr>
                                                <td><input id="bt_Enviar" name="bt_Enviar" type="submit" value="Fechar a Venda" class="Button"></td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    </table><br />
                                    
                                    <input id="id_evento" name="id_evento" type="hidden" value='<?php echo $AddBody->id_evento; ?>'>
                                    <input id="id_evento_link" name="id_evento_link" type="hidden" value='<?php echo $AddBody->id_evento_link; ?>'>
                                    <input id="url" name="url" type="hidden" value='<?php echo $AddBody->url; ?>'>
                                    <input id="valor" name="valor" type="hidden" value='<?php echo $AddBody->valor; ?>'>
                                    <input id="tipo_evento" name="tipo_evento" type="hidden" value='1'>
                                    
                                    <?php echo form_close(); ?>
                                    
                                </td>
                            </tr>
                            </table>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>