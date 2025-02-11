function SetReport()
{
    if( $("#Eventos").val() == 0 )
    {
        alert("Favor escolher um evento.");	
        return false;
    }
    else if( $("#Forma").val() == 0 )
    {
        alert("Favor escolher um forma de pagamento.");	
        return false;
    }
    else if( $("#Situacao").val() == 0 )
    {
        alert("Favor escolher uma situação.");	
        return false;
    }
    else
    {
        $("#FormX").attr('action', location.href + '../../../excel/financeiro');
        $("#FormX").submit();
    }   
}

$(document).ready(function(){
	
    $("#Inicio").datepicker();
    $("#Fim").datepicker();

});