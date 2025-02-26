function count_check(id_jogo)
{
    //
    // Atualiza o contador
    //
    $.ajax({
        url      :  '/pyngaiada/jogo/count_checkin/' + id_jogo,
        type     :  'GET',
        success  :   function(data)   
        {
            $('#count_players').html(data + ' de no mínimo 22 atletas escalados');       
        }
    });
}

$(document).ready(function(){

    //
    // Evento de click
    //
    $(document).on('click', '#CheckIn', function()
    {
        var id = $(this).data('id');

        ids = id.split('|');

        $.ajax({
            url      :  '/pyngaiada/jogo/checkin/' + ids[0] + '/' + ids[1],
            type     :  'GET',
            success  :   function(data)   
            {
                $("#Atleta_" + ids[0]).fadeIn("slow", function() {
                    $('#Atleta_' + ids[0]).html(data);       
                });

                //
                // Atualiza o contador
                //
                count_check(ids[1])                
            }
        });


    });

    //
    // Evento de click
    //
    $(document).on('click', '#CheckOut', function()
    {
        var id = $(this).data('id');

        ids = id.split('|');

        $.ajax({
            url      :  '/pyngaiada/jogo/checkout/' + ids[0] + '/' + ids[1],
            type     :  'GET',
            success  :   function(data)   
            {
                $("#Atleta_" + ids[0]).fadeIn("slow", function() {
                    $('#Atleta_' + ids[0]).html(data);       
                });

                //
                // Atualiza o contador
                //
                count_check(ids[1])                
            }
        });
    });    

    $(document).on('click', '#UpdateList', function()
    {
        $.ajax({
            url      :  '/pyngaiada/jogo/list_team/' + $('#IdJogo').val(),
            type     :  'GET',
            success  :   function(data)   
            {
                $("#Team").fadeIn("slow", function() {
                    $('#Team').html(data);       
                });
            }
        });
    });    
});