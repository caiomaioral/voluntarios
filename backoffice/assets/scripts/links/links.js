function Disabled(id_evento)
{
	if(confirm("Tem certeza que deseja inativar o evento?"))
	{
		// Se rolou sucesso na inclusão
		$.ajax({
			type	 :  "POST",
			cache	 :  false,
			url		 :  "links/inativar/" + id_evento,
			success  :  function(data) 
			{
				parent.oTable.fnReloadAjax(null, null, true);
			}
		});
	}
}

function Active(id_evento)
{
	if(confirm("Tem certeza que deseja ativar o evento?"))
	{
		// Se rolou sucesso na inclusão
		$.ajax({
			type	 :  "POST",
			cache	 :  false,
			url		 :  "links/ativar/" + id_evento,
			success  :  function(data) 
			{
				parent.oTable.fnReloadAjax(null, null, true);
			}
		});
	}
}

$(document).ready(function(){
	
	oTable = $('#examples').dataTable({
		
		"bProcessing": true,
		"bJQueryUI": true,
		"bDestroy": true,
		"sServerMethod": "POST",
		"sDom": '<"H"Cfr>t<"F"ip>',
		"oColVis": 
		{
			"buttonText": "Ocultar Colunas"
		},
		"sPaginationType": "full_numbers",
		"aoColumnDefs": [ 
			{"sClass": "center", "aTargets": [ 0, 6 ]}
		],
		"iDisplayLength" : 50,
		"sAjaxSource": "links/listar_ajax",
		"aoColumns": [
						{  "bSearchable": false, "bSortable": false,
						   "fnRender": function (oObj)
						   {
							   if($('#Perfil').val() == 0)
							   {
								    return '<div class="acao"> <a id="duplicar" href=\"links/duplicar/' + oObj.aData[0] + '\" title="Duplicar o Link"><img src=\"assets/images/3671751_copy_icon.png\" width="17" height="17"></a> <a id="alterar" href=\"links/alterar/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> <a id="excluir" href=\"links/excluir/' + oObj.aData[0] + '\" title="Excluir"><img src=\"assets/images/ico-lixeira.gif\" width="17" height="17"></a> <a id="visualizar" href=\"links/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a> </div>';
							   }
							   else
							   {
									return '<a id="visualizar" href=\"links/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a></div>';	
							   }
						   }
						},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true},
						{"bSearchable": true}
					  ], 
	});

	$('#onForm').fancybox({
		'width'				: 300,
		'height'			: 300,
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
	    'scrolling'			: 'no',
	    'titleShow'			: true
	});
	
	//
	// Ação do menu que chama ministério de Eventos
	//
	$(document).on('click', '#bt_Incluir', function(){ location.href = ('links/incluir_eventos') });

	//
	// Clique para excluir
	//	
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