function Rollback(id_inscrito)
{
	if($('#Perfil').val() == 1)
	{
		alert('Seu usuário não permite alterações nessa funcionalidade, chame o administrador do Evento.')
	}
	else
	{	
        if(confirm("Tem certeza que deseja reverter o Check-in?"))
        {
            //
            // Se rolou sucesso na inclusão
            //
            $.ajax({
                type	 :  "POST",
                cache	 :  false,
                url		 :  "presenca/rollback/" + id_inscrito,
                success  :  function(data) 
                {
                    parent.oTable.fnReloadAjax(null, null, true);
                }
            });
        }
    }
}

function IncluirParticipante()
{
    location.href = ('gateway/incluir_participante/' + $('#id_evento').val());
}

function IncluirBilheteria()
{
    location.href = ('bilheteria/incluir_bilheteria/' + $('#id_evento').val());
}

$(document).ready(function(){
    
    //
    // Inclusão do Bread Crumb
    //    
    $('#breadCrumb3').jBreadCrumb();

    oTable = $('#examples').dataTable({
        
        "bProcessing"       : true,
        "bServerSide"       : true,
        "bJQueryUI"         : true,
        "bDestroy"          : true,
        "sServerMethod"     : "POST",
        "sPaginationType"   : "full_numbers",
        "aoColumnDefs"      : 
        [ 
            {"sClass": "center", "aTargets": [ 0 ]}
        ],
        "iDisplayLength"    : 10,
        "fnServerParams": function (aoData) 
        {
            aoData.push(
                { "name" : "Status", "value" : $("#Status").val() },
                { "name" : "NN", "value" : $("#NN").val() }
            );
        },        
        "sAjaxSource"       : "participantes/listar_participantes_ajax_server/" + $("#id_evento").val(),
        "aoColumns": [
                        /* Ações */
                        {  
                            "bSearchable": false, 
                            "bSortable": false,
                            "fnRender": function (oObj)
                            {
                                if($('#Perfil').val() == 0)
                                {
                                    return '<div class="acao"> <a id="bill" href=\"relatorios/gerar_pdf/' + oObj.aData[3] + '\" target="_blank" title=\"Gerar PDF de Comprovante\"><img src=\"assets/images/new_file_2.png\" width="16" height="16"></a>  <a id="alterar" href=\"participantes/alterar_inscrito/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';
                                }
                                else
                                {
                                    return '<div class="acao"> <a id="alterar" href=\"participantes/alterar_inscrito/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';	
                                }                            
                            }
                        },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": false, "bSortable": true }
                    ], 
    }).fnSetFilteringDelay(700);

    // Javascript de click no botão de pesquisar
    $(document).on("click", ".pesquisar", function(){

        oTable = $('#examples').dataTable({

            "bProcessing"       : true,
            "bServerSide"       : true,
            "bJQueryUI"         : true,
            "bDestroy"          : true,
            "sServerMethod"     : "POST",
            "sPaginationType"   : "full_numbers",
            "aoColumnDefs"      : 
            [ 
                {"sClass": "center", "aTargets": [ 0 ]}
            ],
            "iDisplayLength"    : 10,
            "fnServerParams": function (aoData) 
            {
                aoData.push(
                    { "name" : "Status", "value" : $("#Status").val() },
                    { "name" : "NN", "value" : $("#NN").val() }
                );
            },
            "sAjaxSource"       : "participantes/listar_participantes_ajax_server/" + $("#id_evento").val(),
            "aoColumns": [
                            /* Ações */
                            {  
                               "bSearchable": false, 
                               "bSortable": false,
                               "fnRender": function (oObj)
                               {
                                    if($('#Perfil').val() == 0)
                                    {
                                        return '<div class="acao"> <a id="bill" href=\"relatorios/gerar_pdf/' + oObj.aData[3] + '\" target="_blank" title=\"Gerar PDF de Comprovante\"><img src=\"assets/images/new_file_2.png\" width="16" height="16"></a>  <a id="alterar" href=\"participantes/alterar_inscrito/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';
                                    }
                                    else
                                    {
                                        return '<div class="acao"> <a id="alterar" href=\"participantes/alterar_inscrito/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';	
                                    }                                      
                               }
                            },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": false, "bSortable": true },
                            { "bVisible": true, "bSearchable": false, "bSortable": true }
                      ], 
        }).fnSetFilteringDelay(700);
    });

    $('#mail').fancybox({
        'width'             : 400,
        'height'		    : 270,
        'autoScale'     	: false,
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'type'			    : 'iframe'
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
        buttons: 
        {
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