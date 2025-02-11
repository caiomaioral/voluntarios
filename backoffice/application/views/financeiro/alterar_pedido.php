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
                            <a href="<?php echo base_url(); ?>financeiro/visualizar/<?php echo $DataOrder->id_Evento_Link; ?>">Retornar aos Pedidos</a>
                        </li>                      
                        <li>
                            Alterar Pedido
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

                        echo form_open(base_url() . 'financeiro/salvar_pedido', $attributes); 

                        if($DataOrder->Status_Pagamento == 2)
                        {
                            $Status = '<font color="green">Pago</font>';		
                        }
                
                        if($DataOrder->Status_Pagamento != 2)
                        {
                            $Status = '<font color="red">Não pago</font>';		
                        }
                    
                    ?>
                    
                    <table width="100%" border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <td height="20"><h3 class="Play"><?php echo $DataOrder->Nome; ?></h3></td>
                    </tr>                    
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td height="20"><h3 class="Play">CPF: <?php echo formatCPF_CNPJ($DataOrder->CPF); ?></h3></td>
                    </tr>                    
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td height="20"><h3 class="Play">Status: <?php echo $Status; ?></h3></td>
                    </tr>                     
                    <tr>
                        <td height="20"></td>
                    </tr>                                       
                    <tr>
                        <td height="20"><h3 class="Play">Valor:</h3></td>
                    </tr>                    
                    <tr>
                        <td>
                                                        
                            <input id="fl_Valor" name="fl_Valor" type="text" size="9" class="money" value="<?php echo set_value('fl_Valor', $DataOrder->Valor); ?>"  />

                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>                                       
                    <tr>
                        <td height="20"><h3 class="Play">Status de Pagamento:</h3></td>
                    </tr>                    
                    <tr>
                        <td>
                            
                            <?php 
                            
                                $Status_Pagamento = ($DataOrder->Status_Pagamento != 2)? 3 : 2;

                                echo form_dropdown('Status', $AddStatus, set_value('Status', $Status_Pagamento), 'style="width: 200px"'); 
                            
                            ?>                            

                        </td>
                    </tr>                    
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td><input id="bt_Salvar" name="bt_Salvar" type="submit" value="Alterar os dados" /></td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                    <tr>
                        <td class="msg_error"><?php echo validation_errors(); ?></td>
                    </tr>
                    </table>
                    
                    <input id="ID_Pedido" name="ID_Pedido" type="hidden" value="<?php echo $DataOrder->Pedido; ?>" />
                    <input id="ID_Evento" name="ID_Evento" type="hidden" value="<?php echo $DataOrder->id_Evento; ?>" />
                    <input id="CPF" name="CPF" type="hidden" value="<?php echo $DataOrder->CPF; ?>" />
                    <input id="Quantidade_Ingressos" name="Quantidade_Ingressos" type="hidden" value="<?php echo $DataOrder->Quantidade_Ingressos; ?>" />
                    <input id="ID_Evento_Link" name="ID_Evento_Link" type="hidden" value="<?php echo $DataOrder->id_Evento_Link; ?>" />
                    
                    <?php echo form_close(); ?>
                    
                </div>
            </td>
        </tr>
        </table>
	</td>
</tr>
</table>