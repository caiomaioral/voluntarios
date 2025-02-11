function Disabled(id_evento)
{

}

function Active(id_evento)
{

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
			{"sClass": "center", "aTargets": [ 0, 5, 6 ]}
		],
		"iDisplayLength" : 50,
		"sAjaxSource": "links/listar_ajax",
		"aoColumns": [
						{  "bSearchable": false, "bSortable": false,
						   "fnRender": function (oObj)
						   {
							   if($('#Perfil').val() == 0)
							   {
								    return '<div class="acao"> <a id="visualizar" href=\"financeiro/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a> </div>';
							   }
							   else
							   {
									return '<div class="acao"> <a id="visualizar" href=\"financeiro/visualizar/' + oObj.aData[0] + '\" title="Visualizar"><img src=\"assets/images/circle.png\" width="17" height="17"></a></div>';	
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