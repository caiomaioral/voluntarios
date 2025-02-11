<?php

echo $AddJavascripts;

?>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
     <td>
        <div class="td_Titulo">
	        <h3 class="Play">Pagamentos para o Evento</h3>
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
                            <a href="<?php echo base_url(); ?>eventos">Eventos</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>links/visualizar/<?php echo $AddBody->id_evento_link; ?>"><?php echo ucwords(mb_strtolower($AddBody->titulo)); ?></a>
                        </li>
                        <li>
                            Incluir participante com pagamento no balcão
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
                        <img src="/admin/assets/upload/<?php echo $AddBody->header; ?>" width="1110" height="332">
                    </div>

                    <div id="conteudoSite">
                        <div id="ResultAjax">
                            <div id="BodyHome">
                                
                                <?php 
                                
                                    $attributes = array('id' => 'FormX', 'name' => 'FormX');
                                
                                    echo form_open(base_url() . 'gateway/enviar_participante_evento', $attributes); 
                                
                                ?>
                                
                                <div id="ResultRequire" class="containerSite">
                                    
                                    <br />
                                    
                                    <div id="ResultValidation" class="field-validation-valid validation"></div>

                                    <?php
                                    
                                    $_return = '';
                                    
                                    if($AddBody->transporte != 0.00)
                                    {
                                        $_return .= '<table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                                        <tr bgcolor="#d9d9d9">

                                                        <td height="30" align="left" style="padding-left:10px;padding-top:5px;padding-bottom:5px">
                                                            <table align="left" border="0">
                                                            <tr>
                                                                <td style="padding-left:6px;padding-bottom:10px">

                                                                    <table border="0" cellpadding="0" cellspacing="0">	
                                                                    <tr>
                                                                        <td colspan="2" height="40"><font style="font-size:14px"><strong>Transporte para o Evento</strong></font><br />Selecione se deseja ou não adquirir o transporte.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select id="int_Transporte" name="int_Transporte">
                                                                            <option value="0">Não</option>
                                                                            <option value="1">Sim</option>
                                                                            </select>				
                                                                        </td>
                                                                        <td style="padding-left: 10px"></td>
                                                                    </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                    
                                                    <input id="valor_transporte" name="valor_transporte" type="hidden" value="'.$AddBody->transporte.'">';
                                    }
                                    else
                                    {
                                        $_return .= '<input id="int_Transporte" name="int_Transporte" type="hidden" value="0">';   
                                    }     
                                    
                                    echo $_return;
                                    
                                    ?>                                     
                                            
                                    <table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr>
                                        <td bgcolor="#FFFFFF" style="padding-left:10px; padding-right:10px; padding-top:8px; padding-bottom:14px">

                                            <table width="100%" border="0" cellpadding="3" cellspacing="2">
                                            <tr>
                                                <td height="40"><font style="font-size:20px"><strong>Dados de quem vai pagar</strong></font><br /></td>
                                            </tr>
                                            <tr><td height="10"></td></tr>                                            
                                            <tr>
                                                <td style="padding-left:6px">Nome Completo</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">

                                                    <input id="str_Nome" name="str_Nome" size="45" type="text" value="<?php echo set_value('str_Nome'); ?>" />
                                                    
                                                    <?php echo form_error('str_Nome', '<span class="field-validation-valid validation" data-valmsg-for="str_Nome" data-valmsg-replace="true">', '</span>'); ?>
                                                    
                                                </td>
                                            </tr>
                                            <tr><td height="20"></td></tr>
                                            <tr>
                                                <td style="padding-left:6px">CPF</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">

                                                    <input id="str_CPF" name="str_CPF" size="12" type="text" value="<?php echo set_value('str_CPF'); ?>" unique="currency" />
                                                    
                                                    <?php echo form_error('str_CPF', '<span id="CPF" class="field-validation-valid validation" data-valmsg-for="str_CPF" data-valmsg-replace="true">', '</span>'); ?>
                                            
                                                </td>
                                            </tr>
                                            <tr><td height="20"></td></tr>                                                
                                            <tr>
                                                <td style="padding-left:6px">E-mail</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">
                                                
                                                    <input id="str_Email" maxlenght="44" name="str_Email" size="40" type="text" value="<?php echo set_value('str_Email'); ?>" />
                                                    
                                                    <?php echo form_error('str_Email', '<span class="field-validation-valid validation" data-valmsg-for="str_Email" data-valmsg-replace="true">', '</span>'); ?>
                                                    
                                                </td>
                                            </tr>
                                            <tr><td height="20"></td></tr>
                                            <tr>
                                                <td style="padding-left:6px">Celular</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">
                                                
                                                    <input id="str_Celular" name="str_Celular" size="12" type="text" value="<?php echo set_value('str_Celular'); ?>" />
                                                    
                                                    <?php echo form_error('str_Celular', '<span class="field-validation-valid validation" data-valmsg-for="str_Celular" data-valmsg-replace="true">', '</span>'); ?>
                                        
                                                </td>
                                            </tr>
                                            <tr><td height="20"></td></tr> 
                                            <tr>
                                                <td style="padding-left:6px">Cidade</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">
                                                
                                                    <input id="str_Cidade" maxlength="100" name="str_Cidade" size="60" type="text" value="<?php echo set_value('str_Cidade'); ?>" />
                                                    
                                                    <?php echo form_error('str_Cidade', '<span class="field-validation-valid validation" data-valmsg-for="str_Cidade" data-valmsg-replace="true">', '</span>'); ?>
                                                                                        
                                                </td>
                                            </tr>
                                            <tr><td height="20"></td></tr>                                                                                                                                    
                                            <tr>
                                                <td style="padding-left:6px">UF</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px; padding-top: 5px">
                                                    
                                                    <select id="id_UF" name="id_UF">
                                                    <option value="" <?php echo set_select('id_UF', ''); ?>>Selecione um Estado</option>
                                                    <option value="AC" <?php echo set_select('id_UF', 'AC'); ?>>ACRE (AC)</option>
                                                    <option value="AL" <?php echo set_select('id_UF', 'AL'); ?>>ALAGOAS (AL)</option>
                                                    <option value="AM" <?php echo set_select('id_UF', 'AM'); ?>>AMAZONAS (AM)</option>
                                                    <option value="AP" <?php echo set_select('id_UF', 'AP'); ?>>AMAPÁ (AP)</option>
                                                    <option value="BA" <?php echo set_select('id_UF', 'BA'); ?>>BAHIA (BA)</option>
                                                    <option value="CE" <?php echo set_select('id_UF', 'CE'); ?>>CEARÁ (CE)</option>
                                                    <option value="DF" <?php echo set_select('id_UF', 'DF'); ?>>DISTRITO FEDERAL (DF)</option>
                                                    <option value="ES" <?php echo set_select('id_UF', 'ES'); ?>>ESPÍRITO SANTO (ES)</option>
                                                    <option value="GO" <?php echo set_select('id_UF', 'GO'); ?>>GOIÁS (GO)</option>
                                                    <option value="MA" <?php echo set_select('id_UF', 'MA'); ?>>MARANHÃO (MA)</option>
                                                    <option value="MG" <?php echo set_select('id_UF', 'MG'); ?>>MINAS GERAIS (MG)</option>
                                                    <option value="MS" <?php echo set_select('id_UF', 'MS'); ?>>MATO GROSSO DO SUL (MS)</option>
                                                    <option value="MT" <?php echo set_select('id_UF', 'MT'); ?>>MATO GROSSO (MT)</option>
                                                    <option value="PA" <?php echo set_select('id_UF', 'PA'); ?>>PARÁ (PA)</option>
                                                    <option value="PB" <?php echo set_select('id_UF', 'PB'); ?>>PARAÍBA (PB)</option>
                                                    <option value="PE" <?php echo set_select('id_UF', 'PE'); ?>>PERNAMBUCO (PE)</option>
                                                    <option value="PI" <?php echo set_select('id_UF', 'PI'); ?>>PIAUÍ (PI)</option>
                                                    <option value="PR" <?php echo set_select('id_UF', 'PR'); ?>>PARANÁ (PR)</option>
                                                    <option value="RJ" <?php echo set_select('id_UF', 'RJ'); ?>>RIO DE JANEIRO (RJ)</option>
                                                    <option value="RN" <?php echo set_select('id_UF', 'RN'); ?>>RIO GRANDE DO NORTE (RN)</option>
                                                    <option value="RO" <?php echo set_select('id_UF', 'RO'); ?>>RONDÔNIA (RO)</option>
                                                    <option value="RR" <?php echo set_select('id_UF', 'RR'); ?>>RORAIMA (RR)</option>
                                                    <option value="RS" <?php echo set_select('id_UF', 'RS'); ?>>RIO GRANDE DO SUL (RS)</option>
                                                    <option value="SC" <?php echo set_select('id_UF', 'SC'); ?>>SANTA CATARINA (SC)</option>
                                                    <option value="SE" <?php echo set_select('id_UF', 'SE'); ?>>SERGIPE (SE)</option>
                                                    <option value="SP" <?php echo set_select('id_UF', 'SP'); ?>>SÃO PAULO (SP)</option>
                                                    <option value="TO" <?php echo set_select('id_UF', 'TO'); ?>>TOCANTINS (TO)</option>
                                                    </select>
                                                    
                                                    <?php echo form_error('id_UF', '<span class="field-validation-valid validation" data-valmsg-for="id_UF" data-valmsg-replace="true">', '</span>'); ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="20"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:6px">Pagamento</td>
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
                                            <tr>
                                                <td id="ResultTransport"></td>
                                            </tr>                                                                                         
                                            </table>
                                        </td>
                                    </tr>
                                    </table><br />
                                    
                                    <table border="0" align="center" width="595" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                    <tr bgcolor="#d9d9d9">

                                        <td height="30" align="left" style="padding-left:10px;padding-top:10px;padding-bottom:5px">
                                            <table align="left" border="0">
                                            <tr>
                                                <td style="padding-left:6px;padding-bottom:10px">

                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td height="40"><font style="font-size:20px"><strong>Inclusão de quem vai no Evento</strong></font></td>
                                                    </tr>
                                                    <tr><td height="10"></td></tr>
                                                    <tr>
                                                        <td>
                                                            <select id="sel_Adicionais" name="sel_Adicionais">
                                                            <option value="0">Quantos ingressos deseja adquirir?</option>
                                                            
                                                            <?php for($x = 1; $x <= $AddBody->adicionais; $x++){ ?>
                                                            
                                                                    <option value="<?php echo $x; ?>"><?php echo $x; ?> ingresso (s)</option>
                                                            
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
                                                <td><input id="bt_Enviar" name="bt_Enviar" type="submit" value="Finalizar compra" class="Button"></td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    </table><br />
                                    
                                    <input id="id_evento" name="id_evento" type="hidden" value='<?php echo $AddBody->id_evento; ?>'>
                                    <input id="id_evento_link" name="id_evento_link" type="hidden" value='<?php echo $AddBody->id_evento_link; ?>'>
                                    <input id="url" name="url" type="hidden" value='<?php echo $AddBody->url; ?>'>
                                    <input id="valor" name="valor" type="hidden" value='<?php echo $AddBody->valor; ?>'>
                                    
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