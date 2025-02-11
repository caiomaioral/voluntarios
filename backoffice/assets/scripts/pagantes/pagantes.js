function IncluirEvento()
{
	location.href = ('eventos/incluir');
}

function Disabled(id_evento)
{
	if(confirm("Tem certeza que deseja inativar o evento?"))
	{
		// Se rolou sucesso na inclusão
		$.ajax({
			type	 :  "POST",
			cache	 :  false,
			url		 :  "eventos/inativar/" + id_evento,
			success  :  function(data) 
			{
				parent.oTable.fnReloadAjax(null, null, true)
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
			url		 :  "eventos/ativar/" + id_evento,
			success  :  function(data) 
			{
				parent.oTable.fnReloadAjax(null, null, true)
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
		"bJQueryUI": true,
		"oColVis": 
		{
			"buttonText": "Ocultar Colunas"
		},
		"sPaginationType": "full_numbers",
		"aoColumnDefs": [ 
			{"sClass": "center", "aTargets": [ 0, 5, 6 ]}
		],
		"iDisplayLength" : 50,
		"sAjaxSource": "eventos/listar_ajax",
		"aoColumns": [
						/* Ações */
						{  "bSearchable": false, "bSortable": false,
						   "fnRender": function (oObj)
						   {
							   if($('#Perfil').val() == 0)
							   {
								    return '<div class="acao"><a id="excluir" href=\"eventos/excluir/' + oObj.aData[0] + '\" title="Excluir"><img src=\"assets/images/ico-lixeira.gif\" width="17" height="17"></a> <a id="alterar" href=\"eventos/alterar/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> <a id="visualizar" href=\"eventos/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a></div>';
							   }
							   else
							   {
									return '<a id="visualizar" href=\"eventos/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a></div>';	
							   }
						   }
						},
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
	
	// Ação para incluir conteudo
	//$(document).on('click', '#bt_Incluir', function(){ $('#onForm').trigger('click') });

	//
	// Ação do menu que chama ministério de Eventos
	//
	$(document).on('click', '#bt_Incluir', function(){ location.href = ('eventos/incluir_eventos') });

	//
	// Ação do menu que chama ministério de Teatro
	//
	//$(document).on('click', '#bt_Teatro', function(){ location.href = ('eventos/incluir_teatro') });	

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