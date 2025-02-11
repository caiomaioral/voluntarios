$(function() {
    
    $('#breadCrumb3').jBreadCrumb();

    var locationSite = location.host;

    //
    // Ação de click do redirect
    //
    $(document).on('click', '#bt_Enviar', function()
    {
        location.href = ('bilheteria/incluir_bilheteria/' + $('#id_Evento_Link').val());
    });

    //
    // Selecionar algum participante
    //
    $(document).on('change', '#sel_Adicionais', function()
    {
        $('#bt_Enviar').prop('disabled', true);

        $('#bt_Enviar').css('color', 'red');
        
        $.get('/admin/bilheteria/calcular?valor=' + $('#valor').val() + '&quantidade=' + $(this).val(), function(data) {
            
            $('#ValorDisplay').html(data);

            $('#bt_Enviar').prop('disabled', false);

            $('#bt_Enviar').css('color', '#666666');
        });
    });
    
    //
    // Validate do Form
    // 
    $('#FormX').validate({

        submitHandler: function(form)
        {
            form.submit();

            $('#bt_Enviar').prop('disabled', true);
        },
        rules: 
        {
            'sel_Pagamento': {required: true},            
        },
        messages: 
        {
            'sel_Pagamento': '<strong>DEFINIR A FORMA DE PAGAMENTO</strong> é obrigatório.',
        },
        submitHandler: function(form)
        {
            form.submit();

            $('#bt_Enviar').prop('disabled', true);
        }
    });
    
});