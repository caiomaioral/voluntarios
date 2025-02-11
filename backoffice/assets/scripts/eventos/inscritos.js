function LoadPage(Id)
{
    $.get('eventos/exibir_dependentes/' + Id, function(data) {
        
        $('#Dependentes').html(data);
        
    });   
}

function Delete(Id)
{
	$.ajax({
	    url      :  'eventos/excluir_dependentes/' + Id + '/' + $('#Valor').val(),
	    type     :  'GET',
	    success  :   function()   
	    {
			LoadPage($('#ID_Inscrito').val());
	    }
	});   
}

$(document).ready(function(){
    
    //
    // Inclusão do Bread Crumb
    //    
    $('#breadCrumb3').jBreadCrumb();

    //
    // Incluir as máscaras gerais
    //
    $('#str_CPF').mask('999.999.999-99');   

});