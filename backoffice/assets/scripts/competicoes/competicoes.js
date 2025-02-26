$(document).ready(function(){

    //
    // Evento de click
    //
    $(document).on('change', '#Jogos', function()
    {
        $.ajax({
            url      :  '/pyngaiada/home/jogos_home/' + $(this).val(),
            type     :  'GET',
            success  :   function(data)   
            {
                $("#ListaJogos").fadeIn("slow", function() {
                    $('#ListaJogos').html(data);       
                });
            }
        });
    });

    $(document).on('change', '#Temporada', function()
    {
        $.ajax({
            url      :  '/pyngaiada/home/artilheiros_home/' + $(this).val(),
            type     :  'GET',
            success  :   function(data)   
            {
                $("#ListaArtilheiros").fadeIn("slow", function() {
                    $('#ListaArtilheiros').html(data);       
                });
            }
        });
    });    
    
    $('#confirm-delete').on('show.bs.modal', function(e) {
        
        var id = $(e.relatedTarget).data('id');
        
        $('#IdJogo').attr('value', id);   
    });

    $('#DeleteForever').on('click', function() {
        
        $.ajax({
            url      :  '/pyngaiada/competicoes/excluir/' + $('#IdJogo').val(),
            type     :  'GET',
            success  :   function()            
            {
                $('#confirm-delete').modal('hide');

                $.ajax({
                    url      :  '/pyngaiada/home/jogos_home/' + $('#Jogos').val(),
                    type     :  'GET',
                    success  :   function(data)   
                    {
                        $("#ListaJogos").fadeIn("slow", function() {
                            $('#ListaJogos').html(data);       
                        });
                    }
                });
            }
        });	  
    });
    
    //
    // Validação da exclusão dos artilheiros
    //
    $('#confirm-delete-atilheiro').on('show.bs.modal', function(e) {
        
        var id = $(e.relatedTarget).data('id');
        
        $('#IdArtilheiro').attr('value', id);   
    });

    $('#DeleteForeverArtilheiro').on('click', function() {
        
        $.ajax({
            url      :  '/pyngaiada/competicoes/excluir_artilheiro/' + $('#IdArtilheiro').val(),
            type     :  'GET',
            success  :   function()            
            {
                $('#confirm-delete-atilheiro').modal('hide');

                $.ajax({
                    url      :  '/pyngaiada/home/artilheiros_home/' + $('#Temporada').val(),
                    type     :  'GET',
                    success  :   function(data)   
                    {
                        $("#ListaArtilheiros").fadeIn("slow", function() {
                            $('#ListaArtilheiros').html(data);       
                        });
                    }
                });
            }
        });	  
    });
});