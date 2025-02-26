$(document).ready(function(){

    $('#confirm-delete').on('show.bs.modal', function(e) {
        
        var id = $(e.relatedTarget).data('id');
        
        $('#IdPagamento').attr('value', id);   
    });

    $('#DeleteForever').on('click', function() {
        
        $.ajax({
            url      :  '/pyngaiada/financeiro/excluir/' + $('#IdPagamento').val(),
            type     :  'GET',
            success  :   function()            
            {
                $('#confirm-delete').modal('hide');

                $('#FormS').submit();
            }
        });	  
    });     
});