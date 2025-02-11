$(document).ready(function(){
    
    var locationSite = location.host;

    //
    // Insere estilo ao botão
    //     
    $('input:button, input:submit').button();
    
    //
    // Inclusão do Bread Crumb
    //    
    $('#breadCrumb3').jBreadCrumb();

    //
    // Incluir as máscaras gerais
    //
    $('#str_CPF').mask('999.999.999-99');    

});