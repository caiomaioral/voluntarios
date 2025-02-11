$(document).ready(function() {
	$('#palco').load($(".menu a:first").attr('href') + ' #ajax_content', selButton($(".menu a:first").attr('name')));
	
	$(".menu a").click(function(event) {
		loading(true);
		selButton($(this).attr('name'));
		$('#palco').load($(this).attr('href') + ' #ajax_content',function(){
			loading(false);
		});
		return false;
	});	

});

/* Exibe o "carregando" na div */
function loading(h){
	if(h){
		$('#palco').html("");
		$("#carregando").css("display", "inline");
	}else{
		$("#carregando").css("display", "none");
	}
}

function selButton(hb){
	/* altera todos os botões para o padrão */
	$('.menu a > img').each(function(index) {
		$(this).attr('src','assets/hotsites/nossos_astros/img/bt_'+$(this).parent().attr('name')+'.png')
	});
		
	/* Altera o botão selecionado */
	$('.menu a[name$="'+hb+'"] >img').attr('src','assets/hotsites/nossos_astros/img/bt_'+hb+'_over.png');
}

function reload_avatar(){
	$('#esquerda').load('./nossosastros/reload_avatar',function(){
			loading(false);
	});
}