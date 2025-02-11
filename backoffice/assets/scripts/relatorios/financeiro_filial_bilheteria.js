function SetReport()
{
    $("#FormX").attr('action', location.href + '../../pdf_financeiro_eventos_bilheteria');
    $("#FormX").attr('target', '_blank');
    $("#FormX").submit();
}