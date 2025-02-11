<?php

echo $AddJavascripts;

$attributes = array('id' => 'FormC', 'name' => 'FormC', 'target' => '_blank');

echo form_open(base_url() . 'gateway/pdf_recibo', $attributes); 

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
                <div id="breadCrumb1" class="breadCrumb module">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>links">Eventos</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>links/visualizar/<?php echo $AddBody->id_evento_link; ?>"><?php echo ucwords(mb_strtolower($AddBody->titulo)); ?></a>
                        </li>
                        <li>
                            Participante incluido e pago com sucesso!
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
                        <img src="assets/upload/<?php echo $AddBody->header; ?>">
                    </div>

                    <div id="conteudoSite">
                        <div id="ResultAjax">
                            <div id="BodyHome">

                                <table border="0" align="center" width="600" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                <tr bgcolor="#d9d9d9">
                                    <td height="45" style="padding-left:90px; padding-right:30px; padding-top:30px; padding-bottom:15px">
                                        
                                        <table align="left">
                                        <tr>
                                            <td><h3 style="font-size: 27px">Dados enviados com sucesso.</h3></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="left" style="padding-left:90px">
                                                <p style="font-size:14px"><strong>Os dados foram enviados com sucesso.</strong></p> 
                                                <p style="font-size:14px">Clique para abaixo para gerar o comprovante de pagamento.</p>
                                            </td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                </table><br /><br /><br />

                                <table border="0" align="center" width="100%" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                <tr bgcolor="#d9d9d9">
                                    <td height="80" align="center" style="padding-right:5px">
                                        <table border="0">
                                        <tr>
                                            <td><input name="bt_Enviar" type="submit" value="Imprimir Comprovante" class="Button"></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                </table><br />                                
                                
                                <input id="Pedido" name="Pedido" type="hidden" value="<?php echo $AddUser->Pedido; ?>" />

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

<?php echo form_close(); ?>