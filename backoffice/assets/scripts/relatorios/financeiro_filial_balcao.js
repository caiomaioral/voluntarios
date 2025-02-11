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

    $("#FormX").attr('action', location.href + '../../../excel/eventos_balcao');
    $("#FormX").submit();
}