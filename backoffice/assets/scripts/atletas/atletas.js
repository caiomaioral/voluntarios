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
            {
               "sClass": "center", "aTargets": [ 0 ]
            }
        ],
        "iDisplayLength" : 50,
        "aaSorting": [[ 1, "asc" ]],
        "fnServerParams": function (aoData) 
        {
            aoData.push(
                { "name" : "ci_csrf_token_py", "value" : $('input[name=ci_csrf_token_py]').val() }
            );
        },
        "sAjaxSource": "/pyngaiada/atletas/listar_atletas_ajax",
        "aoColumns": [
                        {  "bSearchable": false,
                           "bSortable": false,
                           "fnRender": function (oObj)
                           {
                                return '<div class="acao"> <a id="pagamento" href="pagamento/quitar/' + oObj.aData[0] + '" title="Efetuar Pagamento"><img src="assets/images/tools.png" width="17" height="17"></a> <a title="Excluir Atleta" href="' + oObj.aData[0] + '" data-id="' + oObj.aData[0] + '" data-toggle="modal" data-target="#confirm-delete"><img src="assets/images/ico-lixeira.gif" width="17" height="17"></a> <a id="alterar" href="atletas/alterar/' + oObj.aData[0] + '" title="Alterar Dados"><img src="assets/images/pencil.gif" width="17" height="17"></a></div>';
                           } 
                        },
                        {"bSearchable": true},
                        {"bSearchable": true},
                        {"bSearchable": true},
                        {"bSearchable": true},
                        {"bSearchable": true}
                  ], 
    });

    $('#confirm-delete').on('show.bs.modal', function(e) {
        
        var id = $(e.relatedTarget).data('id');
        
        $('#IdAtleta').attr('value', id);   
    });

    $('#DeleteForever').on('click', function() {
        
        $.ajax({
            url      :  '/pyngaiada/atletas/excluir/' + $('#IdAtleta').val(),
            type     :  'GET',
            success  :   function()            
            {
                $('#confirm-delete').modal('hide');

                oTable.fnReloadAjax();
            }
        });	  
    });     
});