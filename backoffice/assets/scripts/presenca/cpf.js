$(document).ready(function(){

    $('#Eventos').selectize({
        sortField: 'text'
    });
    
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
		event.preventDefault();
		return false;
		}
	});

	$('#CPF').keydown(function(event){ 
		var keyCode = (event.keyCode ? event.keyCode : event.which);   
		if (keyCode == 13) 
		{
			$('#checkin').trigger('click');
		}
	});  	
	
	$(document).on('click', '#checkin', function(){
		
		var params  =  $('#FormX').serialize();

		if ($("#Eventos").val() == 0) 
        {
            alert('Favor selecionar o Evento.');
            $("#Eventos").focus();
            return false;
		}
		else if ($("#CPF").val().length < 1) 
        {
            alert('Favor preencher o CPF.');
            $("#CPF").focus();
            return false;
		}
		else
		{
			$.ajax({
				type: "POST",
				data: params,
				url: 'presenca/getParticipantConfirmCPF',
				success: function (data)
				{
					$('#show').html(data);
				}
			});
	
			return false;	
		}
	});
});