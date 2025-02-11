function verificaNumero(e)
{
	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

function callback() 
{
	const submitButton = document.getElementById('wp-submit');
	submitButton.removeAttribute('disabled');
}

$(document).ready(function(){

});