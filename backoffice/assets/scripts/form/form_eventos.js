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
    // Selecionar algum participante novo
    //
    $(document).on('change', '#sel_Adicionais', function()
    {
        $.get('/admin/gateway/calcular?valor=' + $('#valor').val() + '&quantidade=' + $(this).val() + '&transporte=' + $('#int_Transporte').val() + '&vtrans=' + $('#valor_transporte').val() + '&participante=' + $('#sel_Adicionais').val(), function(data) {
            
            $('#ValorDisplay').html(data);
            
        });
        
        $.get('/admin/gateway/show/' + $(this).val() + '/' + $('#int_Transporte').val(), function(data) {
            
            $('#ResultAdd').html(data);
            $('.Data_Nascimento_Adicional').mask('99/99/9999');

            $(document).on('change', '.CPF_Adicional', function()
            {
                $('.CPF_Adicional').mask('999.999.999-99');
            });
        });
    });

    //
    // Selecionar transporte ou não
    //
    $(document).on('change', '#int_Transporte', function()
    {
        $.get('/admin/gateway/calcular?valor=' + $('#valor').val() + '&quantidade=' + $('#sel_Adicionais').val() + '&transporte=' + $(this).val() + '&vtrans=' + $('#valor_transporte').val() + '&participante=' + $('#sel_Adicionais').val(), function(data) {
            $('#ValorDisplay').html(data);
        });

        $.get('/admin/gateway/show_transport/0/' + $('#int_Transporte').val(), function(data) {
            $('#ResultAdd').html(data);
            $('.cpf_adicional').mask('999.999.999-99');
            $('.Data_Nascimento_Adicional').mask('99/99/9999');
            
            //
            // Retona o status normal para ter que incluir os novos adicionais
            //
            $('#sel_Adicionais').val('0');

            //
            // Retona o status normal para definir se o cara vai ou não participar
            //
            $('#TransportContent').remove();
        });        
    });    
    
    //
    // Handle dos campos incluidos para os adicionais
    //    
    $('#FormX').on('submit', function(event) 
    {
        event.preventDefault();
        
        //
        // adding rules for inputs with class 'nome'
        //
        $('input.Nome_Adicional').each(function() {
            
            $(this).rules('add', {
                required: true,
                messages: 
                {
                    required: '<strong>NOME</strong> é um campo obrigatório.'
                }                
            })
            
        }); 
        
		//
		// adding rules for inputs with class 'cpf_adicional'
		//        
        $('input.CPF_Adicional').each(function() {
            
            $(this).rules('add', {
				required: true,
				valid_cpf: true,
				unique_cpf: true,
				duplicidade: true,
                messages: 
                {
                    required: '<strong>CPF</strong> é um campo obrigatório.'
                }                
            })
            
        });
        
        //
        // adding rules for inputs with class 'rg'
        //
        $('input.RG_Transporte_Adicional').each(function() {
            
            $(this).rules('add', {
                required: true,
                messages: 
                {
                    required: '<strong>RG</strong> é um campo obrigatório.'
                }                
            })
        }); 
        
        //
        // adding rules for inputs with class 'nascimento'
        //        
        $('input.Data_Nascimento_Adicional').each(function() {
            
            $(this).rules('add', {
                required: true,
                messages: 
                {
                    required: '<strong>DATA DE NASCIMENTO</strong> é um campo obrigatório.'
                }                
            })
        }); 

		//
        // adding rules for inputs with class 'cidade'
        //
        $('input.Cidade_Adicional').each(function() {
            
            $(this).rules('add', {
                required: true,
                messages: 
                {
                    required: '<strong>CIDADE</strong> é um campo obrigatório.'
                }                
            })
        }); 
        
        //
        // adding rules for inputs with class 'UF'
        //        
        $('select.UF_Adicional').each(function() {
            
            $(this).rules('add', {
                required: true,
                messages: 
                {
                    required: '<strong>UF</strong> é um campo obrigatório.'
                }                
            })
        });	        

        // prevent default submit action         
        event.preventDefault(); 
    })    

    //
	// Set an rule that will valid the CPF
	//
	$.validator.addMethod('valid_cpf', function(value, element)
	{
		return this.optional(element) || (valida_cpf(value) == true);
		
	}, '<strong>CPF</strong> inválido, insira um correto.');
	
	//
	// Set an rule that will valid the CPF in duplicate
	//
	$.validator.addMethod('unique_cpf', function(value, element, params)
	{
		var parentForm = $(element).closest('#add');
		var timeRepeated = 0;

		if (value != '') 
		{
			$(parentForm.find(':text')).each(function () {
				
				if ($(this).val() === value) {
					timeRepeated++;
				}
			});
		}
		return timeRepeated === 1 || timeRepeated === 0;

	}, '<strong>CPF</strong> esta repetido. Favor utilizar outro.');
	
    //
    // Set an rule that will check if this customer filled the form
    //
    $.validator.addMethod('duplicidade', function(value, element)
    {
        var response;
        var value = value.replace('.', '').replace('.', '').replace('-', '');
        
        $.ajax({
            type: 'GET',
            async: false,
            cache: false,
            url: '/duplicidade?evento=' + $('#id_evento').val() + '&cpf=' + value,
            success: function(msg)
            {
				//
				// If problems exists, set response to true
				//
				response = (msg == 'true')? true : false;
            }            
		});
        
        return response;
        
    }, '<strong>CPF</strong> já esta confirmado para esse evento.');
    
    //
    // Validate do Form
    // 
    $('#FormX').validate({

        submitHandler: function(form)
        {
            $('#bt_Enviar').prop('disabled', true);
            
            form.submit();
        },
        rules: 
        {
            'str_Nome': {required: true},
            'str_CPF': 
            {
                required: true,
                valid_cpf: true,
            },
            'str_Email': {required: true},
            'str_Celular': {required: true},
            'id_UF': {required: true},
            'str_Cidade': {required: true},
            'sel_Pagamento': {required: true},
            'sel_Adicionais': 
            {
                required: true,
                min: 1
            },                        
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
            'sel_Adicionais': '<strong>DEFINIR OS PARTICIPANTES</strong> é obrigatório.',            
        },
        submitHandler: function(form)
        {
            $('#bt_Enviar').prop('disabled', true);
            
            form.submit();
        }
    });
    
});