<?php

echo $AddCss;
echo $AddJavascripts;


?>

<style>

.rota
{
	font-size: 85px; 
	color: #000;
	font-weight: bold;
}

.quantidade
{
	font-size: 25px; 
	color: #000;
	font-weight: bold;
	margin-bottom: 10px;
}

.alerta
{
	font-size: 85px; 
	color: red;
	font-weight: bold;
}

.bordinha .completo .rota
{
	font-size: 75px; 
	color: #000;
	font-weight: bold;
	padding-top: 30px;
	padding-bottom: 20px;
}

.bordinha .completo .quantidade
{
	font-size: 45px; 
	color: #fff;
	font-weight: bold;
	padding-bottom: 10px;
}

.bordinha .incompleto .rota
{
	font-size: 75px; 
	color: #000;
	font-weight: bold;
	padding-top: 30px;
	padding-bottom: 20px;
}

.bordinha .incompleto .quantidade
{
	font-size: 45px; 
	color: #fff;
	font-weight: bold;
	margin-bottom: 10px;
}

.bordinha .alerta
{
	font-size: 85px; 
	color: red;
	font-weight: bold;
}

.bordinha td
{
	width: 400px;
	height: 200px;
	border: 1px solid #000;
	text-align: center;
}

.bordinha .completo
{
	background-color: green;
	height: 100%;
	width: 100%;	
}

.bordinha .incompleto
{
	background-color: red;
	height: 100%;
	width: 100%;
}

</style>

<div class="boxWhite">

<div class="ajax">
	<div class="overlay"></div>
	<div class="loader">
		<img src="<?php echo base_url(); ?>assets/images/ajax-loader.gif" width="16" height="16" align="left" />
		<p>Aguarde o processamento dos pedidos no ORS.</p>
	</div>
</div>

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
     <td colspan="2">
        <div class="td_Titulo">
	        <h3 class="Play">Modulo de Monitoria da Bipagem</h3>
    	</div>    
     </td>
</tr>
<tr>
     <td colspan="2" style="padding-top: 2px">
		
        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="margin:0;padding:0">
            	<div id="Bipagem_Placar" class="td_Corpo">

					<h3 class="Play">Total de Itens: <?php echo $AddTotal; ?></h3>
					<br />
					<h3 class="Play">Itens Bipados: <?php echo $AddBipados; ?></h3>
					<br />
					<h3 class="Play">Itens não Bipados: <?php echo $AddNotBipados; ?></h3>
					
                </div>
            </td>
        </tr>
        </table>
	</td>
</tr>
<tr>
    <td colspan="2" height="10"></td>
</tr>
<tr>
    <td colspan="2" align="center">
        
		<div class="td_Corpo">
		
			<div id="show"></div>
			
			<br />
			
			<div id="rotas_box">
				<?php echo $AddBody; ?>
			</div>
		
		</div>
		
	</td>
</tr>
<tr>
    <td height="20"></td>
</tr>
</table>

<div id="Cupom" class="fancyWindow" style="display:none">
    <a href="#demonstrative" id="onCupom"></a>
    <div id="demonstrative"></div>
</div>

</div>