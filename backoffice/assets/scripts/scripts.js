function verificaNumero(e)
{
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}

function validarCNPJ(cnpj)
{
    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj == '') return false;

    if (cnpj.length != 14)
            return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
            cnpj == "11111111111111" || 
            cnpj == "22222222222222" || 
            cnpj == "33333333333333" || 
            cnpj == "44444444444444" || 
            cnpj == "55555555555555" || 
            cnpj == "66666666666666" || 
            cnpj == "77777777777777" || 
            cnpj == "88888888888888" || 
            cnpj == "99999999999999")
            return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
                    pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
            return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
                    pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
              return false;

    return true;
}

function efetuaUpload(form,url,retorno,html_carregando,html_erro)
{
    ajaxUpload(form, url, retorno, html_carregando,html_erro);
}

function $m(quem){ return document.getElementById(quem) }

function remove(quem){ quem.parentNode.removeChild(quem) }

function addEvent(obj, evType, fn)
{
    if (obj.addEventListener)
            obj.addEventListener(evType, fn, true)
    if (obj.attachEvent)
            obj.attachEvent("on"+evType, fn)
}

function removeEvent( obj, type, fn )
{
    if(obj.detachEvent){
            obj.detachEvent('on'+type, fn);
    }else{
            obj.removeEventListener(type, fn, false); 
    }
} 

$(function(){

    $('.date').mask('99/99/9999');
    $('.time').mask('99:99:99');
    $('.date_time').mask('99/99/9999 99:99:99');
    $('.cep').mask('99999-999');
    $('.phone').mask('9999-9999');
    $('.phone_with_ddd').mask('(99) 9999-9999');
    $('.phone_us').mask('(999) 999-9999');
    $('.mixed').mask('AAA 000-S0S');

    $('.cep_with_callback').mask('00000-000', {onComplete: function(cep) {
        console.log('Mask is done!:', cep);
      },
       onKeyPress: function(cep, event, currentField, options){
        console.log('An key was pressed!:', cep, ' event: ', event, 'currentField: ', currentField.attr('class'), ' options: ', options);
      }
    });

    $('.crazy_cep').mask('00000-000', {onKeyPress: function(cep){
        var masks = ['00000-000', '0-00-00-00'];
        mask = (cep.length>7) ? masks[1] : masks[0];
      $('.crazy_cep').mask(mask, this);
    }});

    $('.cpf').mask('999.999.999-99', {reverse: true});
	
	$('.cnpj').mask('99.999.999/9999-99', {reverse: true});
	
	$('#str_CNPJ').mask('99.999.999/9999-99', {reverse: true});
    
	$('.money').mask('000.000.000.000.000,00', {reverse: true});

    $('.sp_celphones').mask('(00) 0000-0000', 
        {onKeyPress: function(phone, event, currentField, options){
          if(/(\(11\) 9?(9|8|7)).+/i.test(phone)){
            $(currentField).mask('(00) 00000-0000', options);
          } else {
            $(currentField).mask('(00) 0000-0000', options);         
          }
        }}
    );

    $.fn.serializeObject = function()
    {
       var o = {};
       var a = this.serializeArray();
       $.each(a, function() {
               if (o[this.name]) {
                       if (!o[this.name].push) {
                               o[this.name] = [o[this.name]];
                       }
                       o[this.name].push(this.value || '');
               } else {
                       o[this.name] = this.value || '';
               }
       });
       return o;
    };
});
