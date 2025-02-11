/// <summary>
/// Updates the textarea elements of all CKEditor instances.
/// This method is intended to be used onsubmit
/// </summary>
function UpdateCKEditors() 
{
    for (var i in CKEDITOR.instances) 
	{
        CKEDITOR.instances[i].updateElement();
    }    
}

function verificaNumero(e)
{
	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

function base_url()
{
    url_site     =  document.location.href
    url_pos      =  url_site.indexOf('//');
    url_limpar   =  url_site.substr(url_pos + 2);
    url_prot     =  url_site.substr(0, url_pos + 2);
    url_split 	 =  url_limpar.split('/');  
	
	url_base 	 =  url_prot + url_split[0] + '/' + url_split[1] + '/' + url_split[2] + '/';
	//url_base 	 =  url_prot + url_split[0] + '/' + url_split[1] + '/';
	
	return url_base.replace('undefined\/', '');		
}

function dominio_base()
{
    url_site     =  document.location.href
    url_pos      =  url_site.indexOf('//');
    url_limpar   =  url_site.substr(url_pos + 2);
    url_prot     =  url_site.substr(0, url_pos + 2);
    url_split 	 =  url_limpar.split('/');  
	
	url_base 	 =  url_prot + url_split[0] + '/' + url_split[1] + '/' + url_split[2] + '/' + url_split[3] + '/' + url_split[4] + '/';
	
	return url_base.replace('undefined\/', '');		
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

function validarCPF(s)
{
	s = s.replace(/[^\d]+/g, '');
	
	if(s.length < 11)
	{
		return false;
	}
	else
	{
		if(s=="11111111111" || s=="22222222222" || s=="33333333333" || s=="44444444444" || s=="55555555555" || s=="66666666666" || s=="77777777777" || s=="88888888888" || s=="99999999999")
		{
			return false;
		}
		else
		{
			var v = 0;
			var c = s.substr(0,9);
			var dv = s.substr(9,2);
			var d1 = 0;

			for(i=0; i<9; i++)
			{
				var num_c = c.substr(i, 1);			
				d1 += num_c * (10-i);
			}
			if(d1==0)
			{
				v=1;
				return false;
			}
			else
			{
				d1 = 11 - (d1%11);
				if (d1>9) d1 = 0;
				var num_dv = dv.substr(0, 1);
				if (num_dv != d1)
				{
				  v=v+1;
				  return false;
				}
				else
				{	
					d1 *= 2;
					for(i=0; i<9; i++)
					{
						var num_c = c.substr(i, 1);
						d1 += num_c * (11-i);
					}
					d1 = 11-(d1%11);
					if(d1>9) d1 = 0;
					var num_dv = dv.substr(1, 1);
					if(num_dv != d1)
					{
						v=v+1;
						return false;
					}
					else
					{
						return true;
					}
				}
			}
		}
	}
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

function ajaxUpload(form, url_action, id_elemento_retorno, html_exibe_carregando, html_erro_http)
{
	form = typeof(form)=="string"?$m(form):form;
	var erro="";

	if(form==null || typeof(form)=="undefined"){ 
		erro += "O form passado no 1o parâmetro não existe na página.\n";
	}else if(form.nodeName!="FORM"){ 
		erro += "O form passado no 1o parâmetro da função não é um form.\n";
	}

	if($m(id_elemento_retorno)==null){ 
		erro += "O elemento passado no 3o parâmetro não existe na página.\n";
	}

	if(erro.length>0){
		alert("Erro ao chamar a função:\n" + erro);
		return;
	}

	// Criando o iframe
	var iframe = document.createElement("iframe");
	iframe.setAttribute("id","fpf-temp");
	iframe.setAttribute("name","fpf-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");

	/* Não usei display:none pra esconder o iframe
	   pois tem uma lenda que diz que o NS6 ignora
	   iframes que tenham o display:none */

	// Adicionando ao documento
	form.parentNode.appendChild(iframe);
	window.frames['fpf-temp'].name="fpf-temp"; // IE sucks

	// Adicionando o evento ao carregar
	var carregou = function() { 

		removeEvent( $m('fpf-temp'),"load", carregou);
		var cross = "javascript: ";
		cross += "window.parent.$m('" + id_elemento_retorno + "').innerHTML = document.body.innerHTML; void(0); ";
		$m(id_elemento_retorno).innerHTML = html_erro_http;
		$m('fpf-temp').src = cross;
		
		// Deleta o iframe
		setTimeout(function(){ remove($m('fpf-temp'))}, 250);
	}

	addEvent( $m('fpf-temp'),"load", carregou)

	// Setando propriedades do form
	form.setAttribute("target","fpf-temp");
	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");

	// Submetendo
	form.submit();

	// Se for pra exibir alguma imagem ou texto enquanto carrega
	if(html_exibe_carregando.length > 0){
		msg = "<div id=\"AguardeMinimo\" style='height: 33px'></div>";
		$m(id_elemento_retorno ).innerHTML = msg;
	}
}

function isDate(txtDate)
{
  var currVal = txtDate;
  if(currVal == '')
    return false;
  
  //Declare Regex  
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray == null)
     return false;
 
  //Checks for dd/mm/yyyy format.
  dtDay= dtArray[1];
  dtMonth = dtArray[3];
  dtYear = dtArray[5];

  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)
  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

$(function(){

	$('input:button, input:submit').button();
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
