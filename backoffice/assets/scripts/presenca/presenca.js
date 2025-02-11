$(document).ready(function(){
	
	$('#Codigo').focus();
	
	$(document).click(function() {
		$('#Codigo').focus();
	});	

	$('form input'). keydown(function (e) 
	{
		if (e. keyCode == 13)
		{
			var params  =  $('#FormX').serialize();

			$.ajax({
				type: "POST",
				data: params,
				url: 'presenca/getParticipantConfirm',
				success: function (data)
				{
					$('#show').html(data);
					$('#Codigo').val("");
					$('#Codigo').focus();
				}
			});			
			
			return false;
		}
	});

	$(document).on('change', '#Codigo', function(){
		
		var params  =  $('#FormX').serialize();

		$.ajax({
			type: "POST",
			data: params,
			url: 'presenca/getParticipantConfirm',
			success: function (data)
			{
				$('#show').html(data);
				$('#Codigo').val("");
				$('#Codigo').focus();
			}
		});

		return false;		
	});

	$('#Codigo').focus();
	
});