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
                <div id="breadCrumb1" class="breadCrumb module">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>links">Eventos</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>links/visualizar/<?php echo $AddBody->id_evento_link; ?>"><?php echo ucwords(mb_strtolower($AddBody->titulo)); ?></a>
                        </li>
                        <li>
                            Capacidade máxima de inscritos atingida!
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
                        <img src="https://eventos.boladeneve.com/admin/assets/upload/<?php echo $AddBody->header; ?>">
                    </div>

                    <div id="conteudoSite">
                        <div id="ResultAjax">
                            <div id="BodyHome">

                                <table border="0" align="center" width="550" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
                                <tr bgcolor="#d9d9d9">
                                    <td height="45" style="padding-left:30px; padding-right:8px; padding-top:30px; padding-bottom:15px">
                                        
                                        <table align="left">
                                        <tr>
                                            <td><h3 style="font-size: 22px">Capacidade máxima de inscritos atingida!</h3></td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="left" style="padding-left:32px">
                                                <p style="font-size:14px"><strong>Se quiser incluir mais, será necessário alterar em configurações.</strong></p> 
                                            </td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                                </table><br /><br /><br />

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