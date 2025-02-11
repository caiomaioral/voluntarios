$(function() {

    $('#breadCrumb1').jBreadCrumb();

    //
    // Ação de click do redirect
    //
    $(document).on('click', '#bt_Enviar', function()
    {
        location.href = ('bilheteria/incluir_bilheteria/' + $('#id_Evento_Link').val());
    });    

});