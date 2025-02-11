function IncluirUsuario()
{
	location.href = ('usuarios/incluir');
}

$(document).ready(function(){
	
	oTable = $('#examples').dataTable({
		
		"bProcessing": true,
		"bJQueryUI": true,
		"bDestroy": true,
		"sServerMethod": "POST",
		"sDom": '<"H"Cfr>t<"F"ip>',
		"bJQueryUI": true,
		"oColVis": 
		{
			"buttonText": "Ocultar Colunas"
		},
		"sPaginationType": "full_numbers",
		"aoColumnDefs": [ 
			{"sClass": "center", "aTargets": [ 0, 3 ]}
		],
		"iDisplayLength" : 50,
		"sAjaxSource": "usuarios/listar_ajax",
		"aoColumns": [
						/* Ações */
						{  "bSearchable": false, "bSortable": false,
						   "fnRender": function (oObj)
						   {
							    return '<div class="acao"><a id="excluir" href=\"usuarios/excluir/' + oObj.aData[0] + '\" title="Excluir"><img src=\"assets/images/ico-lixeira.gif\" width="17" height="17"></a> <a id="alterar" href=\"usuarios/alterar/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';
						   }
						},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true}
					  ], 
	});

	$(document).on('click', '#excluir', function(){ 
		link = $(this).attr('href');
		cod = $(this).attr('href').match(/[\d]+$/);
		$('#delConfDialog').dialog('open');
		return false;
	});
	
	$('#delConfDialog').dialog({
		autoOpen: false,
		modal: true,
		hide: 'slide',
		show: 'slide',
		buttons: {
			'Não': function() 
			{
				$(this).dialog('close');
			},            
			'Sim': function() 
			{
				$(this).dialog('close');
				 $.ajax({
					type: "GET",
					url: link,
					data: "cod="+cod,
					success: function() 
					{
						oTable.fnReloadAjax(null, null, true);
					}
				});
			}
		}
	});
});