function SendMail(id_inscrito)
{
    $.ajax({
            type	 :  'GET',
            cache	 :  false,
            url		 :  'http://' + location.host + '/eventos/admin/eventos/status_money/' + id_inscrito,
            success  :  function(data) 
            {
                alert('E-mail disparado com sucesso!');
            },
            error    :  function(data)
            {
                alert('Inscrito ainda não pagou. Aguarde o mesmo efetuar o pagamento.');
            }
    });
}

function IncluirParticipante()
{
    location.href = ('eventos/incluir_participante/' + $('#id_evento').val());
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
        "sAjaxSource"       : "financeiro/listar_cartao_ajax_server/" + $("#id_evento").val(),
        "aoColumns": [
                        /* Ações */
                        {  
                            "bSearchable": false, 
                            "bSortable": false,
                            "fnRender": function (oObj)
                            {
                                if($('#idUsuario').val() == 1 || $('#idUsuario').val() == 3 || $('#idUsuario').val() == 8)
                                {
                                    var excluir = '<a id="excluir" href=\"financeiro/excluir_pedido/' + oObj.aData[0] + '\" title="Excluir"><img src=\"assets/images/ico-lixeira.gif\" width="17" height="17"></a>';
                                }
                                else
                                {
                                    excluir = '';
                                }
                                
                                if($('#Perfil').val() == 0)
                                {
                                    return '<div class="acao">' + excluir + '<a id="alterar" href=\"financeiro/alterar_pedido/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';
                                }
                                else
                                {
                                    return '<div class="acao"> <a id="alterar" href=\"financeiro/alterar_pedido/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';	
                                }                            
                            }
                        },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": true },
                        { "bVisible": true, "bSearchable": true, "bSortable": false },
                        { "bVisible": true, "bSearchable": true, "bSortable": false },
                        { "bVisible": true, "bSearchable": false, "bSortable": true }
                    ], 
    }).fnSetFilteringDelay(700);

    //
    // Javascript de click no botão de pesquisar
    //
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
            "sAjaxSource"       : "financeiro/listar_cartao_ajax_server/" + $("#id_evento").val(),
            "aoColumns": [
                            /* Ações */
                            {  
                               "bSearchable": false, 
                               "bSortable": false,
                               "fnRender": function (oObj)
                               {
                                    if($('#Perfil').val() == 0)
                                    {
                                        return '<div class="acao"> <a id="alterar" href=\"financeiro/alterar_pedido/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';
                                    }
                                    else
                                    {
                                        return '<div class="acao"> <a id="alterar" href=\"financeiro/alterar_pedido/' + oObj.aData[0] + '\" title="Alterar"><img src=\"assets/images/pencil.gif\" width="17" height="17"></a> </div>';	
                                    }                                      
                               }
                            },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": true },
                            { "bVisible": true, "bSearchable": true, "bSortable": false },
                            { "bVisible": true, "bSearchable": true, "bSortable": false },
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