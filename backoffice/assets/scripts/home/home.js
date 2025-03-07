$(function() {

    const oTable = $('#example').DataTable({
        oColVis: {
            buttonText: "Ocultar Colunas"
        },
        sPaginationType: "full_numbers",
        iDisplayLength: 50,
        aaSorting: [[1, "asc"]],
    });

});