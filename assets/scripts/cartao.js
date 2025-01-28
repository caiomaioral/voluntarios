//
// Retorno do Nonce
//
function retornaNonce(data) 
{
    var obj = JSON.parse(data);
    
    var ctoken             =   $('input[name=ci_csrf_token_diz]').val();

    var order              =   $('#order_number').val();
    var amount             =   $('#Valor').val();
    var church_identity    =   $('#Igreja').val();
    var customer_email     =   $('#Email').val();
    var customer_name      =   $('#customer_name').val();
    var customer_identity  =   $('#customer_identity').val();

    //
    // Se não for nulo é porque tem dados no formulario
    //   
    if(data != 'null')
    {
        //
        // Abre o Ajax
        //
        $('.overlay').show();

        console.log('Click');
        
        $('#clique_pagamento').prop('disabled', true);

        $.ajax({
            type: 'POST',
            data: { nonce: obj.nonce, ci_csrf_token_diz: ctoken, order: order, amount: amount, customer_name: customer_name, customer_email: customer_email, customer_identity: customer_identity, church_identity: church_identity },
            url: '/dizimos/doacoes/endpoint',
            async: true,
            complete: function(data) 
            {
                if(data.responseText.length > 0) 
                {
                    window.location.href = data.responseText;
                }
                else
                {
                    //
                    // Faz a mensagem de retorno
                    //
                    location.href = ('/dizimos/doacoes/sucesso');
                }
            }, 
        }) 

        //
        // Fecha o modal
        //
        $('#exampleModalCenter').modal('toggle');         
    }
}

$(function() {

    //
    // Evento de click
    //
    $(document).on('click', '#clique_granito', function()
    {
        loadIFrame('prd', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiI2MTgyZDBkM2Q4ZDgwIiwiaWF0IjoxNjM1OTYzMDkxLCJpc3MiOiIwMDAxMTNjNC0wMDAwLTAwMDAtN2NkNS03NDlhZGY5ZWQ5MDgiLCJwZy52bHQiOiJmYWxzZSIsInBnLmN0eSI6ImNyZWRpdCJ9.9bjQ5WBKBu8-WSsVdUw-8Bh9sPvPlk8F-8KGa9WzzGQ'); 
    });

    //
    // Evento de click
    //
    $(document).on('click', '#clique_pagamento', function()
    {
        configurePaymentMethod(retornaNonce);
        
        sendPaymentMethodNonce();
    });
});