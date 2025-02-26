$(function() {

    //
    // Evento de click
    //
    $(document).on('change', 'select[name="Status[]"]', function()
    {
        var params  =  new Array();
        
        var myId = $(this).attr('id');
        myId = myId.replace('Status_', '');

        params.push({ 
            name: "Id_Pagamento", 
            value: myId
        },
        {
            name: "Status", 
            value: $(this).val()                 
        });                     

        //
        // Efetua ou retira a baixa
        //
        $.ajax({
            url      :  '/pyngaiada/pagamento/efetar_baixa',
            type     :  'POST',
            data     :   params,
            success  :   function(data)   
            {
                $("#Success").fadeIn("slow", function() {
                    $('#Success').html('<div class="alert alert-success" role="alert">Operação efetuada com sucesso! :)</div>');       
                });

                setInterval(function() { 
                    $("#Success").fadeOut("slow");
                }, 3000);                
            }
        });
    });

});