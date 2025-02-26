$(document).ready(function() {

    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
   
    $(".file-upload").on('change', function(){
        readURL(this);
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });

    $('#FileAvatar').on('change', function() { 

        $("#FormX").submit();  	

    });
    
    //
    // Evento de click
    //
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

});