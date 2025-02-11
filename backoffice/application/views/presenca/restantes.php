<?php 

echo $Themes;
echo $CssProjects; 
echo $Javascripts; 

echo $AddCss; 

?>

<table border="0" align="center" width="720" cellpadding="0" cellspacing="0">
<tr>
	<td style="padding-left: 18px; padding-top: 18px">

		<table class="table" border="0">
		<tr>
			<th width="280">Numero Etiqueta</th>
			<th width="180">Pedido</th>
			<th width="100">Rota</th>
			<th width="180">Ordem Carregamento</th>
		</tr>
		<?php foreach($AddLista as $sAddLista): ?>
		<tr>
			<td><?php echo $sAddLista->DFVO_NUM_ETIQUETA; ?></td>
			<td><?php echo $sAddLista->DOFI_PEDIDO_INTERNO; ?></td>
			<td><?php echo $sAddLista->Route; ?></td>
			<td align="center"><?php echo $sAddLista->Load; ?></td>
		</tr>
		<?php endforeach; ?>
		</table>  							

	</td>
</tr>    
</table>