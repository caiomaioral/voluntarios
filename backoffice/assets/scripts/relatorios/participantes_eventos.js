function SetReport()
{
    if( $("#Eventos").val() == 0 )
    {
        alert("Favor escolher um evento.");	
        return false;
    }
    else if( $("#Situacao").val() == 0 )
    {
        alert("Favor escolher uma situação.");	
        return false;
    }
    else
    {
        $("#FormX").attr('action', location.href + '../../../excel/eventos');
        $("#FormX").submit();
    }   
}

$(document).ready(function(){
	
    $("#Inicio").datepicker();
    $("#Fim").datepicker();

    $('#Eventos').selectize({
        sortField: 'text'
    });
    
    $('#Lotes_Eventos').selectize({
        sortField: 'text'
    });
    
	//
	// Ajax que chama de acordo com o filtro selecionado
	//
	$(document).on('change', '#Eventos', function(){
		
		if($('#Eventos').val() != 0)
		{
            var params  =  $('#FormX').serialize();
			
			$.ajax({
				type: "POST",
				data: params,
				url: 'relatorios/filtro_lote/' + this.value,
				success: function (data)
				{
					$('#AjaxLotes').html(data);

                    $('#Lotes_Eventos').selectize({
                        sortField: 'text'
                    });                    
				}
			});
		}

		return false;		

	});    

});