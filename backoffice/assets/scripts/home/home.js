$(function() {

    const oTable = $('#example').DataTable({
        bProcessing: true,
        bJQueryUI: true,
        bDestroy: true,
        sServerMethod: "POST",
        sDom: '<"H"Cfr>t<"F"ip>',
        oColVis: {
            buttonText: "Ocultar Colunas"
        },
        sPaginationType: "full_numbers",
        aoColumnDefs: [
            {
                sClass: "center", aTargets: [0]
            }
        ],
        iDisplayLength: 50,
        aaSorting: [[1, "asc"]],
    });

});