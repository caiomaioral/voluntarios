$(function() {

    //const oTable = $('#example').DataTable({
    //    oColVis: {
    //        buttonText: "Ocultar Colunas"
    //    },
    //    sPaginationType: "full_numbers",
    //    iDisplayLength: 50,
    //    aaSorting: [[1, "asc"]],
    //});

    $('#example').DataTable({
        ajax: 'http://localhost:81/voluntarios/backoffice/colaboradores/listar_ajax',
        processing: true,
        serverSide: true
    });    

    /*
    const oTable = $('#example').dataTable({

        "bProcessing": true,
        "bJQueryUI": true,
        "bDestroy": true,
        "sServerMethod": "POST",
        "oColVis": 
        {
            "buttonText": "Ocultar Colunas"
        },
        "sPaginationType": "full_numbers",
        "iDisplayLength" : 50,
        "aaSorting": [[ 1, "asc" ]],
        "fnServerParams": function (aoData) 
        {
            aoData.push(
                { "name" : "ci_csrf_token_py", "value" : $('input[name=ci_csrf_token_py]').val() }
            );
        },
        "sAjaxSource": "http://localhost:81/voluntarios/backoffice/colaboradores/listar_colaboradores_ajax",
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
                        {"bSearchable": true},
                        {"bSearchable": true}
                  ], 
    });    
    */
});