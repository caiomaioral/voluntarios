function Excluir(Id_Cadeira)
{
    //
    // Mostra as cadeiras do carrinho
    //            
    $.get('http://' + location.host + '/eventos/admin/eventos/excluir_chair/' + Id_Cadeira, function(data) {
        
        //
        // Mostra as cadeiras do carrinho
        //            
        $.get('http://' + location.host + '/eventos/admin/eventos/show_chairs', function(data) {
            
            $('#ResultAdd').html(data);

            //
            // Mostra as cadeiras do carrinho
            //             
            $.get('http://' + location.host + '/eventos/admin/eventos/calcular_cadeiras?valor=' + $('#valor').val(), function(data) {
                $('#ValorDisplay').html(data);
            }); 
        }); 
    });
}

$(function() {

    $('#breadCrumb3').jBreadCrumb();

    var locationSite = location.host;

    //
    // Incluir as máscaras gerais
    //
    $('#str_CPF').mask('999.999.999-99');
    $('#str_Telefone').mask('(99) 9999-9999');
    $('#str_Celular').mask('(99) 9999-9999?9').on('change', function (event) 
    {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();

        if(phone.length == 0) 
        {
            element.mask('(99) 9999-9999?9');
        }
        if(phone.length == 11) 
        {
            element.mask('(99) 99999-9999');
        }
        if(phone.length == 10) 
        {
            element.mask('(99) 9999-9999');
        }
    });
   
    //
    // Incluir uma cadeira a mais
    //
    $(document).on('click', '#AddChair', function()
    {
        //
        // Incluindo as cadeiras
        //  
        $.get('http://' + location.host + '/eventos/admin/eventos/add_chairs/' + $('#int_Cadeiras').val(), function() {
            
            //
            // Mostra as cadeiras do carrinho
            //            
            $.get('http://' + location.host + '/eventos/admin/eventos/show_chairs', function(data) {
                
                $('#ResultAdd').html(data);

                //
                // Mostra as cadeiras do carrinho
                //             
                $.get('http://' + location.host + '/eventos/admin/eventos/calcular_cadeiras?valor=' + $('#valor').val(), function(data) {
                    $('#ValorDisplay').html(data);
                });                 

            });
        });
    });    
    
    //
    // Set an rule that will valid the CPF
    //
    $.validator.addMethod('validarCPF', function(value, element)
    {
        return this.optional(element) || (validar_CPF(value) == true);
        
    }, '<strong>CPF</strong> inválido. Favor inserir um correto.'); 
    
    //
    // Set an rule that will check if this customer filled the form
    //
    var response;
    
    $.validator.addMethod('duplicidade', function(value, element)
    {
        var value = value.replace('.', '').replace('.', '').replace('-', '');
        
        $('#str_CPF').css({'background-color' : '#cecece'});
        $('#str_CPF').prop('disabled', true);
        
        //
        // Função em Ajax para buscar uma duplicidade de cadastro finalizado para permitir uma nova redigitação
        //
        $.ajax({
            type: 'GET',
            async: false,
            cache: false,
            url: 'http://' + location.host + '/eventos/admin/eventos/duplicidade_retype?evento=' + $('#id_eventos').val() + '&cpf=' + value,
            success: function(msg)
            {
                $('#str_CPF').css({'background-color' : '#fff'});
                $('#str_CPF').prop('disabled', false);                
                
                //If problems exists, set response to true
                response = ( msg == 'false' ) ? false : true;
            }            
        });
        
        return response;
        
    }, '<strong>CPF</strong> já efetuou pagamento para esse evento, utilize outro.');    
    
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
            'str_Nome': {required: true},
            'str_CPF': 
            {
                required: true,
                validarCPF: true,
                duplicidade: true
            },
            'str_Email': {required: true},
            'str_Celular': {required: true},
            'id_UF': {required: true},
            'str_Cidade': {required: true},
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
            'str_Nome': '<strong>NOME</strong> é um campo obrigatório.',	
            'str_CPF': 
            {
                required: '<strong>CPF</strong> é um campo obrigatório.'
            },	
            'str_Email': '<strong>E-MAIL</strong> é um campo obrigatório.',	
            'str_Celular': '<strong>CELULAR</strong> é um campo obrigatório.',
            'id_UF': '<strong>UF</strong> é um campo obrigatório.',	
            'str_Cidade': '<strong>CIDADE</strong> é um campo obrigatório.',
            'sel_Pagamento': '<strong>DEFINIR A FORMA DE PAGAMENTO</strong> é obrigatório.',
            'valor': 'Você precisa incluir uma <strong>CADEIRA</strong> para continuar.'
        },
        submitHandler: function(form)
        {
            form.submit();
        }
    });
});