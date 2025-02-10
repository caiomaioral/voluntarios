function callback() 
{
	const submitButton = document.getElementById('enviar_dados');
	submitButton.removeAttribute('disabled');
}

$(document).ready(function(){
	
	$('#CPF').mask('999.999.999-99', {reverse: true});
	$('#Telefone').mask('(99) 9999-99999').on('change', function (event) 
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
	// Handle dos campos incluidos para os adicionais
	//    
	$('#FormX').on('submit', function(event) 
	{
		//
		// prevent default submit action
		//        
		event.preventDefault(); 		
	});	

	//
	// Validate do Form
	// 
	$('#FormX').validate({

		submitHandler: function(form)
		{
			//
			// Desabilita o botão
			//        
			$('#enviar_dados').prop('disabled', true);			
			
			form.submit();
		},
		rules: 
		{
			'Nome': 
			{
				minlength: 5,
				maxlength: 40,
				required: true,
				lettersonly: true
			},
			'CPF': 
			{
				required: true,
				valid_cpf: true,
			},
			'Email': 
			{
				required: true,
				email: true
			},
			'Telefone': 
			{
				required: true
			},
			'Termos': 
			{
                required: true,
                maxlength: 2
            }		
		},
		messages: 
		{
			'Nome': 
			{
				required: '<strong>NOME</strong> é um campo obrigatório.',
				lettersonly: 'Favor inserir somente caracteres.',
				minlength: 'Favor incluir no mínimo 5 caracteres.',
				maxlength: 'Favor incluir no máximo 40 caracteres.',
			},
			'CPF': 
			{
				required: '<strong>CPF</strong> é um campo obrigatório.',
			},			
			'Email': 
			{
				required: '<strong>E-MAIL</strong> é um campo obrigatório.',
				email: 'Digite um <strong>E-MAIL</strong> válido.',
			},	
			'Telefone': '<strong>TELEFONE</strong> é um campo obrigatório.',
			'Termos': 
			{
                required: 'Você aceitar o <strong>TERMO</strong>.',
                maxlength: "Check no more than {0} boxes"
            }			
		},
		highlight: function(element) 
		{
			$(element).closest('.form-control').addClass('is-invalid');
			$(element).closest('.custom-select').addClass('is-invalid');
			$(element).closest('.form-check-input').addClass('is-invalid');
			$(element).closest('.custom-control custom-switch').addClass('is-invalid');
		},
		unhighlight: function(element) 
		{
			$(element).closest('.form-control').removeClass('is-invalid');
			$(element).closest('.custom-select').removeClass('is-invalid');
			$(element).closest('.form-check-input').removeClass('is-invalid');
			$(element).closest('.custom-control custom-switch').addClass('is-invalid');
		}
	});
	
	//
	// Valida para somente campo texto
	//	
	$.validator.addMethod("lettersonly", function(value, element) 
	{
		return this.optional(element) || /^[a-z áãâäàéêëèíîïìóõôöòúûüùçñ]+$/i.test(value);

	}, 'Favor inserir somente caracteres.');

	//
	// Valida o CPF
	//
	$.validator.addMethod('valid_cpf', function(value, element)
	{
		return this.optional(element) || (valida_cpf(value) == true);
		
	}, '<strong>CPF</strong> inválido, insira um correto.');

});