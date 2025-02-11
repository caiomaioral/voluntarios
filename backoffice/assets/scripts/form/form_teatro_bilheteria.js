
function Excluir(Id_Cadeira)
{
    //
    // Mostra as cadeiras do carrinho
    //            
    $.get('http://' + location.host + '/eventos/admin/bilheteria/excluir_chair/' + Id_Cadeira, function(data) {
        
        //
        // Mostra as cadeiras do carrinho
        //            
        $.get('http://' + location.host + '/eventos/admin/bilheteria/show_chairs', function(data) {
            
            $('#ResultAdd').html(data);

            //
            // Mostra as cadeiras do carrinho
            //             
            $.get('http://' + location.host + '/eventos/admin/bilheteria/calcular_cadeiras?valor=' + $('#valor').val(), function(data) {
                $('#ValorDisplay').html(data);
            }); 
        }); 
    });
}

$(function() {

    $('#breadCrumb3').jBreadCrumb();

    var locationSite = location.host;

    //
    // Incluir uma cadeira a mais
    //
    $(document).on('click', '#AddChair', function()
    {
        //
        // Incluindo as cadeiras
        //  
        $.get('http://' + location.host + '/eventos/admin/bilheteria/add_chairs/' + $('#int_Cadeiras').val(), function() {
            
            //
            // Mostra as cadeiras do carrinho
            //            
            $.get('http://' + location.host + '/eventos/admin/bilheteria/show_chairs', function(data) {
                
                $('#ResultAdd').html(data);

                //
                // Mostra as cadeiras do carrinho
                //             
                $.get('http://' + location.host + '/eventos/admin/bilheteria/calcular_cadeiras?valor=' + $('#valor').val(), function(data) {
                    $('#ValorDisplay').html(data);
                });                 

            });
        });
    });    
    
    //
    // Validate do Form
    // 
    $('#FormX').validate({
        
        ignore: [],

        submitHandler: function(form)
        {
            form.submit();
        },
        rules: 
        {
            'sel_Pagamento': {required: true},
            'valor': 
            {
                required: true,
                min: 1,
                number: true                
            }
        },
        messages: 
        {
            'sel_Pagamento': '<strong>DEFINIR A FORMA DE PAGAMENTO</strong> é obrigatório.',
            'valor': 'Você precisa incluir uma <strong>CADEIRA</strong> para continuar.'
        },
        submitHandler: function(form)
        {
            form.submit();
        }
    });
});