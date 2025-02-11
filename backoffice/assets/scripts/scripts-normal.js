	// Função do Menu de Opções
	function Menu(dir, obj){
		var endereco = "";
		var ifrLoad = window.document.getElementById('ifrLoad');
		endereco = 'modulos/'+dir+'/'+obj;
		ifrLoad.height = "300px";
		ifrLoad.src = endereco;
	}

	function UpdateGuia(ID, Campo){
		callAjax('POST:FormX', 'ConteudoModulo', window.location+'&func=AlterarGuias&ID='+ID+'&Campo='+Campo);
	}

	function FCKeditor(){
		if(typeof(FCKeditorAPI) != 'undefined' && typeof(FCKeditorAPI) != 'unknown'){ 
			getElement('Texto').value=setEntidadeHTML(1, FCKeditorAPI.GetInstance('Texto').EditorDocument.body.innerHTML); 
		}
	}

	function DefineAtributos(I, A, E, P){
		var fI = window.document.getElementsByName('I');
		var fA = window.document.getElementsByName('A');
		var fE = window.document.getElementsByName('E');
		var fP = window.document.getElementsByName('P');
		
		if(I=='n'){ var IContador = fI.length; for(a=0; a<IContador; a++) fI[a].className = 'subNone' }
		if(A=='n'){ var AContador = fA.length; for(a=0; a<AContador; a++) fA[a].className = 'subNone' }
		if(E=='n'){ var EContador = fE.length; for(a=0; a<EContador; a++) fE[a].className = 'subNone' }
		if(P=='n'){ var PContador = fP.length; for(a=0; a<PContador; a++) fP[a].className = 'subNone' }
		
		var tbAction = window.document.getElementsByName('tbAction');
		var ACContador = tbAction.length;
		
		if(I=='n' && A=='n' && E=='n'){ 
			for(b=0; b<ACContador; b++){
				var newCol = window.document.createElement('td');
				newCol.style.backgroundColor = '#FFF';
				newCol.innerHTML = '<div>Sem Acesso</div>';
				tbAction[b].appendChild(newCol);
			}
		}
	}

	function mudaGuia(Obj, ID){
		var Aba1 = window.document.getElementById('Aba1');
		var Aba2 = window.document.getElementById('Aba2');
		var Aba3 = window.document.getElementById('Aba3');
		var Link1 = window.document.getElementById('Link1');
		var Link2 = window.document.getElementById('Link2');
		var Link3 = window.document.getElementById('Link3');

		if(Obj=="Aba1"){
			$("#Aba3").fadeOut(1);
			$("#Aba2").fadeOut(1);
			$("#Aba1").fadeIn(300);
			Link1.className = "selected";
			Link2.className = "";
			Link3.className = "";
			redefineIframe();
		}
		if(Obj=="Aba2"){
			$("#Aba1").fadeOut(1);
			$("#Aba3").fadeOut(1);
			$("#Aba2").fadeIn(300);
			Link1.className = "";
			Link3.className = "";
			Link2.className = "selected";
			callAjax('POST:FormX', 'Permissoes', window.location+'&func=GerarAtributos&ID='+ID);
		}
		if(Obj=="Aba3"){
			$("#Aba1").fadeOut(1);
			$("#Aba2").fadeOut(1);
			$("#Aba3").fadeIn(300);
			Link1.className = "";
			Link2.className = "";
			Link3.className = "selected";
			redefineIframe();
		}
	}

	function AtivaReg(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaUsuarios&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaPrograma(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaProgramas&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaEmpresa(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaEmpresas&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaEmpreendimento(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaEmpreendimentos&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaRanking(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaRankings&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaPremio(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaPremios&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaDisponibilidade(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaPremios&Pagina='+sel_Pagina.value+'&IDisponivel='+ID);
	}

	function AtivaNoticia(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaNoticias&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaEvento(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaAgenda&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function AtivaLink(ID){
		var sel_Pagina = window.document.getElementById('sel_Pagina');
		callAjax('POST:FormX', 'Result', window.location+'&func=PesquisaLinks&Pagina='+sel_Pagina.value+'&ID='+ID);
	}

	function Tira_Arroba(value){
		if(window.document.getElementById('sel_Empresa').value==6){
			var obj_value = value;
			pos = obj_value.indexOf('@');
			tira = obj_value.substring(pos, -30);
			window.document.getElementById('str_Email').value = tira+'@consultoria.com.br';
		}else{
			return true;	
		}
	}

	function ValidarUsuarioSite(){
		var Empresa = window.document.getElementById('sel_Empresa');
		var Perfil = window.document.getElementById('selTipo');
		var Corretor = window.document.getElementById('str_Corretor');
		var CEP = window.document.getElementById('str_CEP');
		var Endereco = window.document.getElementById('str_Endereco');
		var Email = window.document.getElementById('str_Email');
		var Nascimento = window.document.getElementById('dt_Nascimento');
		var CPF = window.document.getElementById('str_CPF');
		var Senha = window.document.getElementById('str_Senha');
		var str_Nome_Padrinho = window.document.getElementById('str_Nome_Padrinho');
		var CPFPad = window.document.getElementById('str_CPF_Padrinho');

		if(Empresa.value=="null"){
			alert('=> Favor selecione a EMPRESA do usuario.');
			Empresa.focus();
			return false;
		}else if(Perfil.value=="null"){
			alert('=> Favor selecione o PERFIL de usuario.');
			Perfil.focus();
			return false;
		}else if(Corretor.value==""){
			alert('=> Preencha o NOME DE CORRETOR do usuario.');
			Corretor.focus();
			return false;
		}else if(CEP.value==""){
			alert('=> Preencha o CEP do usuario.');
			CEP.focus();
			return false;
		}else if(Endereco.value==""){
			alert('=> Preencha o ENDEREÇO do usuario.');
			Endereco.focus();
			return false;
		}else if(Email.value==""){
			alert('=> Preencha o E-MAIL do usuario.');
			Email.focus();
			return false;
		}else if(Email.value!="" && !Tira_Arroba(Email.value)){
			alert('=> Seu e-mail deve ser SEU NOME @consultoria.com.br.\n');
			Email.focus();
			return false;
		}else if(Nascimento.value==""){
			alert('=> Preencha o NASCIMENTO do usuario.');
			Nascimento.focus();
			return false;
		}else if(CPF.value!="" && !valida_CPF(CPF.value)){
			alert('=> O CPF do CORRETOR esta errado. Utilize um CPF valido.\n');
			CPF.focus();
			return false;
		}else if(Senha.value==""){
			alert('=> Preencha a SENHA do usuario.');
			Senha.focus();
			return false;
		}else if(Empresa.value==5 && str_Nome_Padrinho.value==""){
			alert('=> Preencha o NOME do PADRINHO.');
			str_Nome_Padrinho.focus();
			return false;
		}else if(CPFPad.value!="" && !valida_CPF(CPFPad.value)){
			alert('=> O CPF do PADRINHO esta errado. Utilize um CPF valido.\n');
			CPFPad.focus();
			return false;
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarUsuarios&Acao=Salvar&ID='+ID_Conteudo.value);
		}
	}

	function ValidarUsuario(obj){
		var Tipo = window.document.getElementById('selTipo');
		var Nome = window.document.getElementById('str_Nome');
		var Email = window.document.getElementById('str_Email');
		var Senha = window.document.getElementById('str_Senha');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Tipo.value=="null"){
			alert('Favor selecione o perfil de usuário.');
			Tipo.focus();
			return false;
		}
		if(Nome.value==""){
			alert('Preencha o nome do usuário.');
			Nome.focus();
			return false;
		}
		if(Email.value==""){
			alert('Preencha o e-mail do usuário.');
			Email.focus();
			return false;
		}
		if(obj=="Alterar"){
			if(Senha.value==""){
				alert('Favor preencher a senha do usuário.');
				Senha.focus();
				return false;
			}
		}
		if(obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirUsuarios&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarUsuarios&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}

	function ValidaSenha(){
		var ID = window.document.getElementById('ID');	
		var user_velhopass = window.document.getElementById('user_velhopass');	
		var user_senha = window.document.getElementById('user_senha');

		if(user_velhopass.value==""){
			alert('Preencha a antiga senha.');
			user_velhopass.focus();
			return false;
		}		
		if(user_senha.value==""){
			alert('Preencha a nova senha.');
			user_senha.focus();
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarSenha&Acao=Salvar&ID='+ID.value);
	}

	function ValidarPrograma(obj){
		var Nome = window.document.getElementById('str_Nome');
		var Arquivo = window.document.getElementById('Arquivo');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Nome.value==""){
			alert('Preencha o título do programa.');
			Nome.focus();
			return false;
		}
		if(obj=="Incluir"){
			if(Arquivo.value==""){
				alert('Favor efetue o upload do logotipo do programa.');
				return false;
			}
		}
		if(obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirProgramas&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarProgramas&Acao=Salvar&ID='+ID_Conteudo.value);
		}
	}

	function ValidarFase(obj){
		var Nome = window.document.getElementById('str_Nome');
		var Arquivo = window.document.getElementById('Arquivo');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');
		var ID_Programa = window.document.getElementById('ID_Programa');

		if(Nome.value==""){
			alert('Preencha o título da fase.');
			Nome.focus();
			return false;
		}
		if(obj=="Incluir"){
			//if(Arquivo.value==""){
			//	alert('Favor efetue o upload do logotipo da fase.');
			//	return false;
			//}
		}
		if(obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirFases&Acao=Salvar&ID='+ID_Conteudo.value);
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarFases&Acao=Salvar&ID='+ID_Conteudo.value+'&IDPrograma='+ID_Programa.value);
		}
	}
	
	function ValidarEmpresa(obj){
		var Nome = window.document.getElementById('str_Nome');
		var Visibilidade = window.document.getElementById('sel_Visibilidade');
		var Ordem = window.document.getElementById('sel_Ordem');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Nome.value==""){
			alert('Preencha o nome da empresa.');
			Nome.focus();
			return false;
		}
		if(Visibilidade.value=="null"){
			alert('Selecione se a empresa ficará visível ou não.');
			Visibilidade.focus();
			return false;
		}
		if(Ordem.value=="null"){
			alert('Selecione se a ordem que as empresas irão aparecer./nSe não quiser ordem coloca 0.');
			Ordem.focus();
			return false;
		}
		if(obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirEmpresas&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarEmpresas&Acao=Salvar&ID='+ID_Conteudo.value);
		}
	}
	
	function ValidarEmpreendimentos(obj){
		var Nome = window.document.getElementById('str_Nome');
		var Peso = window.document.getElementById('sel_Peso');
		var Site = window.document.getElementById('str_Site');
		var Imagem = window.document.getElementById('Imagem');
		
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Nome.value==""){
			alert('Preencha o nome do empreendimento.');
			Nome.focus();
			return false;
		}
		if(Peso.value=="null"){
			alert('Selecione o peso.');
			Peso.focus();
			return false;
		}
		if(Site.value==""){
			alert('Preencha o endereço do site.');
			Site.focus();
			return false;
		}
		if(Imagem==null || Imagem.value=="undefined"){
			alert('Favor efetue o upload do logotipo do empreendimento.');
			return false;
		}
		if(obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirEmpreendimentos&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarEmpreendimentos&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}
	
	function ValidarFotosEmpreendimentos(){
		var Tipo = window.document.getElementById('sel_Tipo');
		var Legenda = window.document.getElementById('str_Legenda');
		var Imagem = window.document.getElementById('Imagem');
		
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Tipo.value=="null"){
			alert('Selecione o tipo da foto.');
			Tipo.focus();
			return false;
		}
		if(Imagem==null || Imagem.value=="undefined"){
			alert('Favor efetue o upload da foto ou planta do empreendimento.');
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirFotos&Acao=Salvar&ID='+ID_Conteudo.value);
		return true;
	}
	
	function ValidarFotosPremio(){
		var Legenda = window.document.getElementById('str_Legenda');
		var Imagem = window.document.getElementById('FotoPremio');
		
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Imagem==null || Imagem.value=="undefined"){
			alert('Favor efetue o upload da foto.');
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirFotos&Acao=Salvar&ID='+ID_Conteudo.value);
		return true;
	}	

	function ValidarFotosAlbum(){
		var Legenda = window.document.getElementById('str_Legenda');
		var Imagem = window.document.getElementById('Imagem');
		
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Imagem==null || Imagem.value=="undefined"){
			alert('Favor efetue o upload da foto.');
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirFotos&Acao=Salvar&ID='+ID_Conteudo.value);
		return true;
	}	
	
	function isNumeric(sText){
	   // caso queira utilizar a virgula como separador decimal coloque nesta variável
	   // para verificar se é positivo retire o hifen da variavel
	   var ValidChars = "0123456789.,-";
	   var IsNumber=true;
	   var Char;
	
	   for (i=0; i<sText.length && IsNumber == true; i++){ 
		  Char = sText.charAt(i); 
		  if (ValidChars.indexOf(Char) == -1){
			 IsNumber = false;
		  }
	   }
	   return IsNumber;
	}
	
	
	function is_numeric(mixed_var ){
	   if(mixed_var===''){
		  return false;
	   }
	   return !isNaN(mixed_var * 1);
	}
	
	function isInteger(sNum){
	   // EXPRESSAO REGULAR PARA ACEITAR APENAS NUMEROS INTEIROS
	   var reDigits = /^\d+$/;
	   //var reDigits = /^\s*\d+\s*$/;
	   //var reDigits = new RegExp("^[0-9]$");
	   return reDigits.test(sNum);
	}

	function Mais(){
		var Linha = window.document.getElementById('Linhas');
		var ni = window.document.getElementById('Ajax');
		var newdiv = document.createElement('div');
		var Numero = parseInt(Linha.value)+1;

		var divIdName = 'Linha_'+Numero;

		newdiv.setAttribute('id', divIdName);
		newdiv.style.border = '0px dashed #000';
		
		ni.appendChild(newdiv);
		callLine('POST:FormX', divIdName, window.location+'&func=AddLinhas&Linha='+Numero);

		Linha.value = Numero;
	}

	function Menos(){
		var Linha = window.document.getElementById('Linhas');
		var Item = window.document.getElementById('Item_dpv'+Linha.value);
		if(Linha.value==1){
			alert('=> Como este é o único item existente ele não pode ser excluído.');
			return false;
		}else{
			if(Item!=null && Item.value!="undefined") callMenu('POST:FormX', 'Linha_'+Linha.value, window.location+'&func=ExcluirItem&ID='+Item.value);
			$('Linha_'+Linha.value).fadeIn(500);
			removeElement('Linha_'+Linha.value);
			var Numero = parseInt(Linha.value)-1;
			Linha.value = Numero;		
		}
		redefineIframe();
	}

	function removeElement(divNum){
		var d = document.getElementById('Ajax');
		var olddiv = document.getElementById(divNum);
		d.removeChild(olddiv);
	}

	function Clear(Linha){
		var Predio = window.document.getElementById('Empreendimento_dpv'+Linha);
		var Unidade = window.document.getElementById('Unidade_dpv'+Linha);
		var Torre = window.document.getElementById('Torre_dpv'+Linha);
		var Nome = window.document.getElementById('Nome_dpv'+Linha);
		var CPF = window.document.getElementById('CPF_dpv'+Linha);
		var Venda = window.document.getElementById('Venda_dpv'+Linha);
		var VGV = window.document.getElementById('VGV_dpv'+Linha);
		
		if(Predio.value!="null" && Unidade.value!="" && Torre.value!="" && Nome.value!="" && CPF.value!="" && Venda.value!="" && VGV.value!=""){
			Predio.selectedIndex = 0;
			Unidade.value = "";	
			Torre.value = "";	
			Nome.value = "";	
			CPF.value = "";	
			Venda.value = "";		
			VGV.value = "";
		}else{
			if(confirm("=> Tem certeza que deseja excluir essa linha?")){
				Menos();
			}
		}
	}
	
	function EliminaEmpreendimentoDobrado(obj){
		for(x=0; x<=obj.length-1; x++){
			if(parseInt(obj[x])==parseInt(obj[x+1])){
				Nome = obj[x].split(';'); 
				alert('Validação dos Campos:\n\n=> A unidade '+Nome[1]+' esta repetida, por favor corrija isso no ranking.\n');
				return false;
			}
		}
		return true;
	}

	function EliminaDuplicidade(unidade){
		var erro = 0;
		for(x=0; x<=unidade.length-1; x++){
			if(unidade[x]==unidade[x+1]){
				erro += 1;
			}
		}
		if(erro>=1){
			return false;
		}else{
			return true;
		}
	}

	function ValidarRanking(Obj){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Empresa = window.document.getElementById('sel_Empresa');
		var Perfil = window.document.getElementById('sel_Perfil');		
		var Linhas = window.document.getElementById('Linhas');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');
		
		if(Programa.value=="null"){
			alert('=> Favor selecionar o PROGRAMA.\n');
		  	Programa.focus();
			return false;
		}
		if(Fase.value=="null"){
			alert('=> Favor selecionar a FASE do Programa.\n');
		  	Fase.focus();
		  	return false;
		}
		if(Empresa.value=="null"){
			alert('=> Favor selecionar a EMPRESA.\n');
		  	Empresa.focus();
		  	return false;
		}
		if(Perfil.value=="null"){
			alert('=> Favor selecionar o PERFIL do usuário.\n');
		  	Perfil.focus();
		  	return false;
		}

		var Contador = window.document.getElementById('Linhas');
		
		/* Array de teste */
		arrToSort = new Array();
		
		/* Parte eu defino os repetidos */
		y=1;
		for(x=0; x<parseInt(Contador.value); x++){
			var Predio = window.document.getElementById('Empreendimento_dpv'+y);
			var Unidade = window.document.getElementById('Unidade_dpv'+y);
			var Torre = window.document.getElementById('Torre_dpv'+y);
			var CPF = window.document.getElementById('CPF_dpv'+y);
			
			if(Unidade.value!=""){
				arrToSort[x] = Predio.value+';'+Unidade.value+';'+Torre.value+';'+CPF.value;
			}
			y++;
		}
		for(i=0; i<arrToSort.length; i++){
			for(j=i; j<arrToSort.length; j++){
				if(arrToSort[i] > arrToSort[j]){
					valor_1_antigo = arrToSort[i];
					valor_2_antigo = arrToSort[j];
					arrToSort[i] = valor_2_antigo;
					arrToSort[j] = valor_1_antigo;
				}
			}
		}
		if(!EliminaDuplicidade(arrToSort)){
			alert('Validação dos Campos:\n\n=> Existem UNIDADES repetidas para o mesmo CORRETOR.\n');
			return false;
		}else{
			for(w=1; w<=Contador.value; w++){
				var Empreendimento = window.document.getElementById('Empreendimento_dpv'+w);
				var Unidade = window.document.getElementById('Unidade_dpv'+w);
				var Torre = window.document.getElementById('Torre_dpv'+w);
				var Nome = window.document.getElementById('Nome_dpv'+w);
				var CPF = window.document.getElementById('CPF_dpv'+w);
				var Venda = window.document.getElementById('Venda_dpv'+w);
				var VGV = window.document.getElementById('VGV_dpv'+w);					
				
				if(Empreendimento.value=="null"){
					alert('=> Preencha o nome do EMPREENDIMENTO na linha '+w+'.\n');
					Empreendimento.focus();
					return false;
				}
				if(Unidade.value==""){
					alert('=> Preencha o numero da UNIDADE na linha '+w+'.\n');
					Unidade.focus();
					return false;
				}
				if(Torre.value==""){
					alert('=> Preencha a TORRE do empreendimento na linha '+w+'.\n');
					Torre.focus();
					return false;
				}
				if(Nome.value==""){
					alert('=> Preencha o NOME do corretor na linha '+w+'.\n');
					Nome.focus();
					return false;
				}
				if(CPF.value==""){
					alert('=> Preencha o CPF do corretor na linha '+w+'.\n');
					CPF.focus();
					return false;
				}
				if(CPF.value!="" && !valida_CPF(CPF.value)){
					alert('=> O CPF da linha '+w+' é inválido. Utilize um CPF válido.\n');
					CPF.focus();
					return false;
				}
				if(Venda.value==""){
					alert('=> Preencha a DATA DA VENDA do corretor na linha '+w+'.\n');
					Venda.focus();
					return false;
				}
				if(Venda.value!="" && !VerificaData(Venda.value)){
					alert('=> A DATA DA VENDA da linha '+w+' é inválida. Utilize uma data válida.\n');
					VGV.focus();
					return false;				
				}
				if(VGV.value==""){
					alert('=> Preencha o VGV do corretor na linha '+w+'.\n');
					VGV.focus();
					return false;
				}
			}
		}
		if(Obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirRanking&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarRanking&Acao=Salvar&ID='+ID_Conteudo.value);	
		}
	}
	
	function ValidarPremio(Obj){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Tipo = window.document.getElementById('sel_Tipo');
		var Participante = window.document.getElementById('sel_Participante');
		var Faixa = window.document.getElementById('sel_Faixa');
		var Titulo = window.document.getElementById('str_Titulo');
		var Valor = window.document.getElementById('str_Valor');	
		var Quantidade = window.document.getElementById('sel_Quantidade');
		var Valor_Original = window.document.getElementById('val_Valor_Original');		
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');					

		getElement('Texto').value = setEntidadeHTML(1, FCKeditorAPI.GetInstance('Texto').EditorDocument.body.innerHTML);

		if(Programa.value=="null"){
			alert('=> Selecione o PROGRAMA.\n');
			Programa.focus();
			return false;
		}
		if(Programa.value!="null"){
			if(Fase.value=="null"){
				alert('=> Selecione a FASE.\n');
				Fase.focus();
				return false;
			}
		}
		if(Tipo.value=="null"){
			alert('=> Selecione o TIPO do prêmio.\n');
			Tipo.focus();
			return false;
		}
		if(Participante.value=="null"){
			alert('=> Selecione o perfil de PARTICIPANTE do prêmio.\n');
			Participante.focus();
			return false;
		}
		if(Faixa.value=="null"){
			alert('=> Selecione a FAIXA de prêmio.\n');
			Faixa.focus();
			return false;
		}
		if(Titulo.value==""){
			alert('=> Preencha o TÍTULO do prêmio.\n');
			Titulo.focus();
			return false;
		}
		if(Valor.value==""){
			alert('=> Preencha o VALOR do prêmio.\n');
			Valor.focus();
			return false;
		}
		if(getElement('Texto').value=="<p><br></p>"){
			alert('=> Preencha o TEXTO descritivo do prêmio.');
			return false;
		}
		if(Quantidade.value=="null"){
			alert('=> Selecione a QUANTIDADE de pessoas.\n');
			Quantidade.focus();
			return false;
		}
		
		if(Obj=="Incluir"){
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirPremio&Acao=Salvar');
		}else{
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarPremio&Acao=Salvar&ID='+ID_Conteudo.value+'&Valor='+Valor_Original.value);
		}
	}
	
	function ValidarNoticias(Obj){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Empresa = window.document.getElementById('sel_Empresas');
		var Nome = window.document.getElementById('str_Nome');
		var Chamada = window.document.getElementById('str_Chamada');
		var Data = window.document.getElementById('dt_Conteudo');
		var Cat = window.document.getElementById('sel_Cat');
		var Imagem = window.document.getElementById('FotoNoticia');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		getElement('Texto').value = setEntidadeHTML(1, FCKeditorAPI.GetInstance('Texto').EditorDocument.body.innerHTML);
		
		if(Programa.value=="null"){
			alert('=> Selecione o PROGRAMA.\n');
			Programa.focus();
			return false;
		}
		if(Programa.value!="null"){
			if(Fase.value=="null"){
				alert('=> Selecione a FASE.\n');
				Fase.focus();
				return false;
			}
			if(Empresa.value==""){
				alert('=> Selecione ao menos uma EMPRESA.\n');
				Empresa.focus();
				return false;
			}
		}
		if(Nome.value==""){
			alert('=> Preencha o TÍTULO da notícia.');
			Nome.focus();
			return false;
		}
		if(Chamada.value==""){
			alert('=> Preencha a CHAMADA da notícia.');
			Chamada.focus();
			return false;
		}
		if(Data.value==""){
			alert('=> Preencha a DATA da notícia.');
			Data.focus();
			return false;
		}
		if(Cat.value=="null"){
			alert('=> Selecione a CATEGORIA da qual essa notícia fará parte.');
			Cat.focus();
			return false;
		}
		if(Imagem==null || Imagem.value=="undefined"){
			alert('=> Favor efetue o upload da IMAGEM da notícia.');
			return false;
		}
		if(getElement('Texto').value=="<p><br></p>"){
			alert('=> Preencha a DESCRIÇÃO da notícia.');
			return false;
		}
		if(Obj=="Incluir"){
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirNoticia&Acao=Salvar');
		}else{
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarNoticia&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}
	
	function ValidarEventos(Obj){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Empresa = window.document.getElementById('sel_Empresas');
		var Nome = window.document.getElementById('str_Nome');
		var Data = window.document.getElementById('dt_Evento');

		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		getElement('Texto').value = setEntidadeHTML(1, FCKeditorAPI.GetInstance('Texto').EditorDocument.body.innerHTML);
		
		if(Programa.value=="null"){
			alert('=> Selecione o PROGRAMA.\n');
			Programa.focus();
			return false;
		}
		if(Programa.value!="null"){
			if(Fase.value=="null"){
				alert('=> Selecione a FASE.\n');
				Fase.focus();
				return false;
			}
			if(Empresa.value==""){
				alert('=> Selecione ao menos uma EMPRESA.\n');
				Empresa.focus();
				return false;
			}
		}
		if(Nome.value==""){
			alert('=> Preencha o TÍTULO do evento.');
			Nome.focus();
			return false;
		}
		if(Data.value==""){
			alert('=> Preencha a DATA do evento.');
			Data.focus();
			return false;
		}
		if(getElement('Texto').value=="<p><br></p>"){
			alert('=> Preencha a DESCRIÇÃO do evento.');
			return false;
		}
		if(Obj=="Incluir"){
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirEvento&Acao=Salvar');
		}else{
			FCKeditor();
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarEvento&Acao=Salvar&ID='+ID_Conteudo.value);
		}
	}

	function ValidarLink(Obj){
		var Pasta = window.document.getElementById('str_Pasta');
		var Arquivo = window.document.getElementById('str_Arquivo');
		var Nome = window.document.getElementById('str_Nome');

		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Pasta.value==""){
			alert('=> Favor preencher o nome da PASTA de destino do arquivo.');
			Pasta.focus();
			return false;
		}
		if(Arquivo.value==""){
			alert('=> Favor preencher o nome FÍSICO do arquivo.');
			Arquivo.focus();
			return false;
		}
		if(Nome.value==""){
			alert('=> Favor preencher o nome de exibição do LINK.');
			Nome.focus();
			return false;
		}
		if(Obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirLinks&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarLinks&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}

	function ValidarFrase(Obj){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Frase = window.document.getElementById('str_Frase');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Programa.value=="null"){
			alert('=> Selecione o PROGRAMA.\n');
			Programa.focus();
			return false;
		}
		if(Programa.value!="null"){
			if(Fase.value=="null"){
				alert('=> Selecione a FASE.\n');
				Fase.focus();
				return false;
			}
		}
		if(Frase.value==""){
			alert('=> Preencha a FRASE da sorte.');
			Frase.focus();
			return false;
		}
		if(Obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirFrase&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarFrase&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}

	function ValidarPerfil(Obj){
		var Perfil = window.document.getElementById('str_Perfil');
		var ID_Conteudo = window.document.getElementById('ID_Conteudo');

		if(Perfil.value==""){
			alert('=> Favor preencha o PERFIL de usuário.');
			Perfil.focus();
			return false;
		}
		if(Obj=="Incluir"){
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirPerfil&Acao=Salvar');
		}else{
			callAjax('POST:FormX', 'ResultAjax', window.location+'&func=AlterarPerfil&Acao=Salvar&ID='+ID_Conteudo.value);
		}		
	}
	
	function ValidarContato(){
		var Programa = window.document.getElementById('sel_Programa');
		var Fase = window.document.getElementById('sel_Fase');
		var Empresa = window.document.getElementById('sel_Empresa');
		var Perfil = window.document.getElementById('sel_Perfil');
		var Nome = window.document.getElementById('str_Nome');
		var Email = window.document.getElementById('str_Email');
		var Telefone = window.document.getElementById('str_Telefone');
		var Tipo = window.document.getElementById('sel_Tipo');
		var Status = window.document.getElementById('sel_Status');
		var Entrada = window.document.getElementById('sel_Entrada');
		var Situacao = window.document.getElementById('sel_Situacao');
		var Mensagem = window.document.getElementById('Mensagem_Contato');	
		var ID = window.document.getElementById('ID');
		
		if(Programa.value=="null"){
			alert('=> Selecione o PROGRAMA.\n');
			Programa.focus();
			return false;
		}
		if(Programa.value!="null"){
			if(Fase.value=="null"){
				alert('=> Selecione a FASE.\n');
				Fase.focus();
				return false;
			}
		}
		if(Fase.value!="null"){
			if(Empresa.value=="null"){
				alert('=> Selecione ao menos uma EMPRESA.\n');
				Empresa.focus();
				return false;
			}
		}		
		if(Empresa.value!="null"){
			if(Perfil.value=="null"){
				alert('=> Selecione o PERFIL do usuário.\n');
				Perfil.focus();
				return false;
			}
		}
		if(Tipo.value=="null"){
			alert('=> Selecione o TIPO de contato.');
			Tipo.focus();
			return false;
		}
		if(Situacao.value=="null"){
			alert('=> Selecione a SITUAÇÃO da interação.');
			Situacao.focus();
			return false;
		}
		if(Nome.value==""){
			alert('=> Digite o NOME do contato.');
			Nome.focus();
			return false;
		}
		if(Email.value==""){
			alert('=> Digite o E-MAIL do contato.');
			Email.focus();
			return false;
		}
		if(Telefone.value==""){
			alert('=> Digite o TELEFONE do contato.');
			Telefone.focus();
			return false;
		}
		if(Mensagem.value=="Digite aqui sua Interação."){
			alert('=> Favor digitar a MENSAGEM da interação.');
			Mensagem.focus();
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=IncluirContato&Acao=Incluir');		
	}
	
	function IncluirInteracao(){
		var Tipo = window.document.getElementById('sel_Tipo');
		var Status = window.document.getElementById('sel_Status');
		var Situacao = window.document.getElementById('sel_Situacao');
		var Mensagem = window.document.getElementById('Mensagem_Contato');	
		var ID = window.document.getElementById('ID');
		
		if(Tipo.value=="null"){
			alert('=> Selecione o TIPO de contato.');
			Tipo.focus();
			return false;
		}
		if(Situacao.value=="null"){
			alert('=> Selecione a SITUAÇÃO da interação.');
			Situacao.focus();
			return false;
		}
		if(Status.value=="null"){
			alert('=> Selecione o STATUS do contato.');
			Status.focus();
			return false;
		}
		if(Mensagem.value=="Digite aqui sua Interação."){
			alert('=> Favor digitar a MENSAGEM da interação.');
			Mensagem.focus();
			return false;
		}
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func=VisualizarContatos&Acao=Incluir&ID='+ID.value);
	}
	
	function Pesquisar(Func){
		callAjax('POST:FormX', 'Result', window.location+'&func='+Func);
	}

	function Incluir(Func){
		callAjax('POST:FormX', 'ResultAjax', window.location+'&func='+Func);
	}

	function callMain(Module){
		callAjax('POST:FormX', 'Result', window.location+'&func=MenuConteudo');
	}

	function callModule(Module){
		callAjax('POST:FormX', 'Result', window.location+'&func=GerenciaSistema&Modulo='+Module);
	}

	function Logoff(){
		location.href=('modulos/logoff/pg_Logoff.php');
	}

	function Configuracoes(){
		callAjax('POST:FormX', 'ConteudoModulo', '&func=PesquisaGuias');
	}

	function Extremos(Posicao, Func, Retorno){
		var sel_Pagina = window.document.getElementById('sel_Pagina'); 
		if(sel_Pagina.value!=Posicao){
			sel_Pagina.value = Posicao;
			callAjax('POST:FormX', Retorno, window.location+'&func='+Func);
		}
	}
	
	function Retrocede(Func, Retorno){
		var sel_Pagina = window.document.getElementById('sel_Pagina'); 
		if(sel_Pagina.value!=1){
			sel_Pagina.value--;
			callAjax('POST:FormX', Retorno, window.location+'&func='+Func);
		}
	}

	function Avanca(Func, Retorno){
		var sel_Pagina = window.document.getElementById('sel_Pagina'); 
		Numero_Paginas = sel_Pagina.length;
		if(sel_Pagina.value!=Numero_Paginas){
			sel_Pagina.value++;
			callAjax('POST:FormX', Retorno, window.location+'&func='+Func);
		}
	}

	// Contador de caracteres de um input
	function textCounter(field, countfield, maxlimit){
		if(field.value.length > maxlimit){
			field.value = field.value.substring(0, maxlimit);
		}else{ 
			countfield.value = maxlimit - field.value.length;
		}
	}

	function valida_CPF(s){
		if(s.length<11){
			return false;
		}else{
			if(s=="11111111111" || s=="22222222222" || s=="33333333333" || s=="44444444444" || s=="55555555555" || s=="66666666666" || s=="77777777777" || s=="88888888888" || s=="99999999999"){
				return false;
			}else{
				var v = 0;
				var c = s.substr(0,9);
				var dv = s.substr(9,2);
				var d1 = 0;
				for(i=0; i<9; i++){
					var num_c = c.substr(i, 1);			
					d1 += num_c * (10-i);
				}
				if(d1==0){
					v=1;
					return false;
				}else{
					d1 = 11 - (d1%11);
					if (d1>9) d1 = 0;
					var num_dv = dv.substr(0, 1);
					if (num_dv != d1){
					  v=v+1;
					  return false;
					}else{	
						d1 *= 2;
						for(i=0; i<9; i++){
							var num_c = c.substr(i, 1);
							d1 += num_c * (11-i);
						}
						d1 = 11-(d1%11);
						if(d1>9) d1 = 0;
						var num_dv = dv.substr(1, 1);
						if(num_dv != d1){
							v=v+1;
							return false;
						}else{
							return true;
						}
					}
				}
			}
		}
	}
	
	function callAjax(form, obj, prm){
		acessaAjax(form, '', prm, obj, 2);
	}

	function callMenu(form, obj, prm){
		acessaAjax(form, '', prm, obj, 3);
	}
	
	function callLine(form, obj, prm){
		acessaAjax(form, '', prm, obj, 5);
	}
	
	function callMinimum(form, obj, prm){
		acessaAjax(form, '', prm, obj, 4);
	}

	function textCounter(field, countfield, maxlimit){
		if(field.value.length > maxlimit){
			field.value = field.value.substring(0, maxlimit);
		}else{ 
			countfield.value = maxlimit - field.value.length;
		}
	}

	// Funcao limita somente digitaçao de numeros
	function limita_carac(text){
		var obj_value = text;
		var new_value = text;
		for(var i=0; i<obj_value.length; i++){
			var value_letra = obj_value.charAt(i);
			var code_letra = obj_value.charCodeAt(i);		
			if(code_letra==39 || code_letra==47 || code_letra==180 || code_letra==92 || code_letra==32 || code_letra==124 || code_letra==91 || code_letra==93 || code_letra==43 || code_letra==61){
				new_value = new_value.replace(value_letra, '');
			}
		}
		return(new_value);	
	}

	// Funçõezinhas padrão pra facilitar
	function $m(quem){
		// Apelido só pra não ficar repetindo o document.getElementById
		return document.getElementById(quem)
	}
	function remove(quem){
		quem.parentNode.removeChild(quem);
	}
	function addEvent(obj, evType, fn){
		// O velho do elcio.com.br/crossbrowser
		if (obj.addEventListener)
			obj.addEventListener(evType, fn, true)
		if (obj.attachEvent)
			obj.attachEvent("on"+evType, fn)
	}
	function removeEvent( obj, type, fn ) {
		if(obj.detachEvent){
			obj.detachEvent('on'+type, fn);
		}else{
			obj.removeEventListener(type, fn, false); 
		}
	} 
	
	// Manipula o iFrame qndo o AJAX é chamado no PORTAL
	function redefineIframe(){
 		var structure = window.parent.document.getElementById('Result');
		var iframe = window.parent.document.getElementById('ifrLoad');
		var Interna = window.document.getElementById('Corpo');
		if(Interna == null){
			iframe.style.height = "300px";
		}else{
			structure.style.height = Interna.offsetHeight+10+"px";	
			iframe.style.height = Interna.offsetHeight+10+"px";	
		}
	}

	// Gera o aumento do iframe na pagina principal / INTRANET 
	function valIframe(iframe){
		var vIframe = window.parent.document.getElementById(iframe);
		var Corpo = window.document.getElementById('Corpo');
		var Result = window.parent.document.getElementById('Result');
		var celTD = vIframe;
		
		if(vIframe != 'null'){				
			Result.style.height = Corpo.offsetHeight+100+"px";
			vIframe.height = Corpo.offsetHeight+50;
			vIframe.width = 630;
		}
	}

	// A que faz o serviço pesado
	function ajaxUpload(form, url_action, id_elemento_retorno, html_exibe_carregando, html_erro_http){
	
		// Testando se passou o ID ou o objeto mesmo
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

	// Funcao que só permiti digitar numero em um campo txt
 	function txtlimita(event){
		if(navigator.appName == 'Netscape'){
			var codigo = event.which;
		}else{
			var codigo = event.keyCode;
		}
		if((codigo >= 48 && codigo <= 57) || codigo == 8){
			event.returnValue = true;
			event.cancelBubble = false;
		}else{
			return false;
			event.returnValue = false;
			event.cancelBubble = true;
		}
	}

	// Gera masscara de valor
	function mask(str, textbox, loc, delim, loc2, delim2){
		var locs = loc.split(',');
			for(var i=0; i <= locs.length; i++){
				for(var k=0; k <= str.length; k++){
					if(k == locs[i]){
						if(str.substring(k,k+1) != delim){
							str = str.substring(0,k)+delim+str.substring(k,str.length);
						}
					}else if(k == loc2){
						if(str.substring(k,k+1) != delim2){
							str=str.substring(0,k)+delim2+str.substring(k,str.length);
						}
					}
				}
			}
		textbox.value = str;
	}

	// Funcao limita somente digitaçao de numeros
	function limita_number(text){
		var obj_value = text;
		var new_value = text;
	
		for(var i=0; i<obj_value.length; i++){
			var value_letra = obj_value.charAt(i);
			var code_letra = obj_value.charCodeAt(i);		
			if(code_letra<48 || code_letra>57){
				new_value = new_value.replace(value_letra, '');
			}
		}
		return(new_value);	
	}

 	// Conta o total de numeros ou letras de um caracter ********************************
 	function verifica_letras(valor, valreturn){
 		var cont_valor = valor.length;
 		var list_letras = "ABCÇDEFGHIJKLMNOPQRSTUVWXYZ";
 		var tot_letras = 0;
 		var list_number = "1234567890";
 		var tot_number = 0;
 		for(i=0; i <= cont_valor; i++){
 			var valchar = valor.charAt(i - 1);
 			if(list_letras.indexOf(valchar.toUpperCase()) != -1){
 				tot_letras++;
 			}else if(list_number.indexOf(valchar) != -1){				
 				tot_number++;
 			}
 		}
 		if(valreturn == 'text'){
 			return(tot_letras);
 		}else if(valreturn == 'number'){
 			return(tot_number);
 		}
 	}

	// Reseta objetos de uma tabela de um formulario --------------------
 	function limpar_campos_tabela(obj){
 		var cont_a = obj.childNodes.length;		
 		for(var i=0; i < cont_a; i++){
 			var obj_b = obj.childNodes[i];
 			var obj_bTag = obj_b.nodeName.toLowerCase();
 			if(obj_bTag == 'td'){
 				var cont_c = obj_b.childNodes.length;
 
 				for(var j=0; j < cont_c; j++){
 					var obj_c = obj_b.childNodes[j];
 					var obj_cTag = obj_c.nodeName.toLowerCase();
 					
 					if(obj_cTag == 'table'){
 						var cont_d = obj_c.childNodes.length;
 
 						for(var k=0; k < cont_d; k++){
 							var obj_d = obj_c.childNodes[k];
 							var obj_dTag = obj_d.nodeName.toLowerCase();
 							
 							if(obj_dTag == 'tbody'){
 								var cont_e = obj_d.childNodes.length;
 						
 								for(var l=0; l < cont_e; l++){
 									var obj_e = obj_d.childNodes[l];
 									var obj_eTag = obj_e.nodeName.toLowerCase();
 						
 									if(obj_eTag == 'tr'){
 										limpar_campos_linha(obj_e);
 										limpar_campos_tabela(obj_e);
 									}
 								}
 							}
 						}
 					}
 				}
 			}
 		}
 	}

	// Reseta objetos de formulario de uma linha de uma tabela --------------
 	function limpar_campos_linha(obj){
 		var cont_a = obj.childNodes.length;
 		for(m=0; m < cont_a; m++){
 			var obj_a = obj.childNodes[m];
 			var obj_aTag = obj_a.nodeName.toLowerCase();
			if(obj_aTag == 'td'){				
 				var cont_b = obj_a.childNodes.length;
 				for(n=0; n < cont_b; n++){
 					var obj_b = obj_a.childNodes[n];
 					var obj_bTag = obj_b.nodeName.toLowerCase();
 					var obj_bTyp = obj_b.type;
 					var obj_form = false;
					switch (obj_bTyp){
						case 'text':						
 							obj_b.disabled = false;
							obj_b.value = (obj_b.id.indexOf('txt_semana') == -1)?'':'-';
 							obj_form = true;
 						break;
 						case 'textarea':
 							obj_b.value = '';
 							obj_form = true;
 						break;
 						case 'hidden':
 							obj_b.value = '';
 							obj_form = true;
 						break;
 						case 'checkbox':
 							obj_b.checked = false;
 							obj_form = true;
 						break;
 						case 'radio':
 							obj_b.checked = false;
 							obj_form = true;
 						break;
 						case 'select-one':
							obj_b.options[0].selected = true;
 							obj_form = true;
 						break;
 						case 'select-multiple':
 							obj_b.options[0].selected = false;
 							obj_form = true;
 						break;
 						case 'file':
 							obj_b.nodeValue = '';
 							obj_form = true;
 						break;
 				
 					}
 					// Adiciona um novo ID no objeto do formulario
 					var obj_id = new String(obj_b.id);
 					if(obj_id != 'undefined' && obj_id != 'null' && obj_id != '' && obj_id.indexOf('_dpv') != -1){
 						var obj_name = obj_id.substr(0, obj_id.indexOf('_dpv') + 4);
 						var obj_num = new Number(obj_id.substr(obj_id.indexOf('_dpv') + 4, obj_id.length));
 						obj_b.id = obj_name + (obj_num+1);
 						//obj_b.style.backgroundColor='beige';
 					}
 				}
 			}
 		}	
 	}

	// Reseta objetos de formulario de uma linha de uma tabela --------------
 	function limpar_campos(obj){
 		var cont_a = obj.childNodes.length;
 		for(m=0; m < cont_a; m++){
 			var obj_a = obj.childNodes[m];
 			var obj_aTag = obj_a.nodeName.toLowerCase();
 			if(obj_aTag == 'td'){				
 				var cont_b = obj_a.childNodes.length;
 				for(n=0; n < cont_b; n++){
 					var obj_b = obj_a.childNodes[n];
 					var obj_bTag = obj_b.nodeName.toLowerCase();
 					var obj_bTyp = obj_b.type;
 					var obj_form = false;
 
 					switch (obj_bTyp){
 						case 'text':						
 							obj_b.value = (obj_b.id.indexOf('txt_semana') == -1)?'':'-';
 							obj_form = true;
 						break;
 						case 'textarea':
 							obj_b.value = '';
 							obj_form = true;
 						break;
 						case 'hidden':
 							obj_b.value = '';
 							obj_form = true;
 						break;
 						case 'checkbox':
 							obj_b.checked = false;
 							obj_form = true;
 						break;
 						case 'radio':
 							obj_b.checked = false;
 							obj_form = true;
 						break;
 						case 'select-one':
 							obj_b.options[0].selected = true;
 							obj_form = true;
 						break;
 						case 'select-multiple':
 							obj_b.options[0].selected = false;
 							obj_form = true;
 						break;
 						case 'file':
 							obj_b.nodeValue = '';
 							obj_form = true;
 						break;
 				
 					}
 				}
 			}
 		}	
 	}

	// Deixa em foco um objeto
 	function obj_focus(obj){
 		window.document.getElementById(obj).focus();
 	}

	// Define uma classe para um objeto
 	function obj_class(obj, cls_name){
 			window.document.getElementById(obj).className = cls_name;
 	}

	// Define novos atributos a uma linha da e no seu filhos de uma tabela
 	function def_td_style(obj, obj_this, class_on, class_off, obj_disable){
 		var obj_Thisval = obj_this.value;
 		var obj_Thisckd = obj_this.checked;		
 		
 		if(obj_Thisckd == true){
 			classe = class_on;
 		}else{
 			classe = class_off;
 		}
 		
 		if(obj_Thisval.indexOf(':') != -1){
 			var obj_Thisarr = obj_Thisval.split(':');
 			obj_this.value = obj_Thisarr[0];
 			obj_Thisval = obj_this.value;
 		}
 		obj_a = obj;
 		cont_a = obj_a.childNodes.length;
 		
 		for(b = 0; b < cont_a; b++){
 			obj_b = obj_a.childNodes[b];
 			tag_b = obj_b.nodeName.toLowerCase();
 			
 			if(tag_b == 'td'){
 				obj_b.className = classe;
 				cont_c = obj_b.childNodes.length;
 				for(c = 0; c < cont_c; c++){
 					obj_c = obj_b.childNodes[c];
 					tag_c = obj_c.nodeName.toLowerCase();
 										
 					if(tag_c == 'table'){
 						cont_d = obj_c.childNodes.length;						
 						for(d = 0; d < cont_d; d++){
 							obj_d = obj_c.childNodes[d];
 							tag_d = obj_d.nodeName.toLowerCase();
 							
 							if(tag_d == 'tbody'){
 								cont_e = obj_d.childNodes.length;
 								
 								for(e = 0; e < cont_e; e++){
 									obj_e = obj_d.childNodes[e];
 									tag_e = obj_e.nodeName.toLowerCase();
 									
 									if(tag_e == 'tr'){
 										cont_f = obj_e.childNodes.length;
 										for(f=0; f < cont_f; f++){
 											obj_f = obj_e.childNodes[f];
 											tag_f = obj_f.nodeName.toLowerCase();
 											
 											if(tag_f == 'td'){
 												obj_f.className = classe;
 												
 												if(obj_disable == false){
 													cont_g = obj_f.childNodes.length;
 												
 													for(g=0; g < cont_g; g++){
 														obj_g = obj_f.childNodes[g];																											
 														var obj_gTyp = obj_g.type;			
 														var obj_gVal = (obj_Thisckd == true)? false : true;
 														switch (obj_gTyp){
 															case 'text':
 																obj_g.value = '';
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'textarea':
 																obj_b.value = '';
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'hidden':
 																obj_g.value = '';
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'checkbox':
 																obj_g.checked = false;
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'radio':
 																obj_g.checked = false;
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'select-one':
 																obj_g.options[0].selected = true;
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'select-multiple':
 																obj_g.options[0].selected = true;
 																obj_g.disabled = obj_gVal;
 															break;
 															case 'file':
 																obj_g.value = '';
 																obj_g.disabled = obj_gVal; 
 															break;
 													
 														}
 													}
 												}
 											}
 										}
 									}
 								}
 							}
 						}
 					}
 				}
 			}
 		}
 	}
 	
	// Checa a validade de uma data DD/MM/AAAA em seus digitos
 	function valida_data(val_event, obj, separador, ano){
 		
		var obj = window.document.getElementById(obj);
 		var data = obj.value;
 		var val_event = val_event.type;
 		if(data.indexOf(separador) != -1){
 			ar_data = data.split(separador);			
 			
 			for(i=0; i < ar_data.length; i++){

				valor = new Number(ar_data[i]);
 				count = new String(ar_data[i]).length;
 				
				if(val_event.toLowerCase() == 'keyup'){
					
					if(i==0 && valor < ano && count == 4){
 						alert('O ANO especificado nesta data é inválido.\nEspecificar um ano que seja maior ou igual ao ano de '+ano+'.');
 						obj.value = '';
 						obj.focus();						
 						return false;
 					}else if(i==1 && (valor > 12 || valor == 00) && count == 2){
 						alert('O MÊS especificado nesta data é inválido.\nDigite umo MÊS válido para esta data entre 01 e 12.');
 						obj.value = ar_data[0]+'/';
 						obj.focus();
 						return false;
 					}else if(i==2 && count == 2){
 						// Define os dias de acordo com o mes selecionado
 						bi = (ar_data[0] % 4 == 0) ? 29 : 28; // Calcula o ano bissexto
 						diames = new Array (31,bi,31,30,31,30,31,31,30,31,30,31);
 						diames = diames[ar_data[1] - 1];
 						
 						if(valor <= 0 || valor > diames){
 							alert('O DIA especificado nesta data é inválido.\nO mês especificado vai do dia 01 ao dia '+diames);
 							obj.value = ar_data[0]+'/'+ar_data[1]+'/';					
 							obj.focus();
 							return false;
 						}
 					}
 				}
 			}
 		}		
 	}

	// Define o dia da semana
 	function dia_semana(ano, mes, dia, text_default, obj_text, obj_hid){
 		if(ano.length == 4 && mes.length == 2 && dia.length == 2){
 			// Descobre o dia da semana
 			semana = new Date(mes+'/'+dia+'/'+ano);
 			semana = semana.getDay();	
 			
 			var diasemana = new Array("Domingo", "Segunda-Feira", "Terça-Feira");
 			var diasemana = diasemana.concat("Quarta-Feira","Quinta-Feira", "Sexta-Feira");
 			var diasemana = diasemana.concat("Sábado");
 
 			window.document.getElementById(obj_hid).value = diasemana[semana];
 			if(obj_text != ''){
 				window.document.getElementById(obj_text).innerHTML = diasemana[semana];
 			}
 		}else{
 			window.document.getElementById(obj_hid).value = text_default;
 			if(obj_text != ''){
 				window.document.getElementById(obj_text).innerHTML = text_default;
 			}
 		}
 	}
 		
 	// Adiciona Eventos
 	function addEvent(obj, evType, fn){
 		if (obj.addEventListener)
 			obj.addEventListener(evType, fn, true)
 		if (obj.attachEvent)
 			obj.attachEvent("on"+evType, fn)
   	}

	// Captura objejos
 	function getObj(obj){
 		objReturn = window.document.getElementById(obj);
 		if(objReturn)
 			return objReturn;
 		else{
 			objReturn = window.parent.document.getElementById(obj);
 			if(objReturn)
 				return objReturn;
 			else
 				alert("Objeto (" + obj + ") Não pode ser encontrado.");
 		}
 	}

	// Captura o objeto pai da onde esta sendo executado o evento com onClick de um obj
 	function getObjEvent(evento){
 		if(typeof(evento)=='undefined')
 			evento=window.event		
 		obj=evento.target?evento.target:evento.srcElement
 		obj=(obj.nodeType == 3)?obj.parentNode:obj;
 		
 		if(obj == "undefined" || obj == "null")
 			alert("O evento para encontrar o objeto não foi encontrado.");
 		else
 			return obj;
 	}

 	function movediv(e){
 		if(typeof(e)=='undefined')var e=window.event		
 		thisObj = getObjEvent(e);
 		if(e.type.indexOf('click') != -1){
 			getObj('ferramenta').className = 'display_some';
 			getObj('ferramenta').style.top = e.clientY + 20;
 			getObj('ferramenta').style.left = (getObj('ifrLoad').clientWidth / 2) - (getObj('ferramenta').offsetWidth / 2);			
 			getObj('seta').className = 'display_some';
 			getObj('seta').style.left = e.clientX - (getObj('seta').offsetWidth) - 270;
 		}			
 		
		if(e.type.indexOf('mouseover') != -1)
 			thisObj.className = 'cursor_on';
 			
 	}

	// =========================================================================================
 	function enviaForm(method, obj, prm, acao){
		if(typeof(getElement(obj))  == "undefined"){
			alert("Desculpe mas o objeto de envio não existe.\nTente novamente, caso o erro persista contate o administrador do sistema.");
			return false;
		}else{			
			if(typeof(acao) != "undefined" && acao.length > 1){
				if(acao.indexOf(":") != -1){
					acao = acao.split(":");
					if(acao[1] == 'atualizar' && !confirm('Você deseja atualizar as informações deste evento?')){
						return false;
					}else{
						getElement(acao[0]).value = acao[1];
					}
				}else{
					alert('Atenção o parametro ACAO não foi definido corretamente na função.\nÉ necessário infomar os seguintes atributos: OBJETO + : + TIPO_ACAO.');
					return false;
				};
			};
			acessaAjax(method, '', window.location.href + prm, obj, 1);
			//redefineIframe();
		};
	};
	
	function setEntidadeHTML(tipconversao, texto){
		var eHTML1 = new Array("&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Agrave;","&Egrave;","&Igrave;","&Ograve;","&Ugrave;","&Atilde;","&Otilde;","&Acirc;","&Ecirc;","&Icirc;","&Ocirc;","&Ucirc;","&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&agrave;","&egrave;","&igrave;","&ograve;","&ugrave;","&atilde;","&otilde;","&acirc;","&ecirc;","&icirc;","&ocirc;","&ucirc;","&ccedil;","&Ccedil;","&amp;","&nbsp;","&lt;","&gt;","&deg;","&ordf;");
		var eHTML2 = new Array("Á","É","Í","Ó","Ú","À","È","Ì","Ò","Ù","Ã","Õ","Â","Ê","Î","Ô","Û","á","é","í","ó","ú","à","è","ì","ò","ù","ã","õ","â","ê","î","ô","û","ç","Ç","&"," ","<",">","º","ª");
		
		for(i=0; i< eHTML1.length; i++){
			switch(tipconversao){
				case 1:
					charFind = eHTML1[i];
					charReplace = eHTML2[i];
					break;
				case 2:
					charFind = eHTML2[i];
					charReplace = eHTML1[i];
					break;
				default:
					alert('Você não definiu o tipo da conversão na função setEntidadeHTML.');
					return false;
					break;
			}
			texto = texto.replace(eval('/'+charFind+'/g'), charReplace);
		}
		return texto;
	}
	
	// VALIDAÇÃO DE UMA DATA 
	function VerificaData(cData){
		var data = cData; 
		var tam = data.length;
		if(tam != 10){
			return false;
		}
		var dia = data.substr(0,2)
		var mes = data.substr (3,2)
		var ano = data.substr (6,4)     
		if(ano < 1900){
			return false;
		}
		if(ano > 2010){
			return false;
		}
	
		switch(mes){
			case '01':
		 	if(dia <= 31) 
			return (true);
		 	break;

			case '02':
		 	if(dia <= 29) 
			return (true);
		 	break;

			case '03':
			if(dia <= 31) 
			return (true);
			break;
			
			case '04':
			if(dia <= 30) 
			return (true);
			break;
			
			case '05':
			if(dia <= 31) 
			return (true);
			break;
			
			case '06':
			if(dia <= 30) 
			return (true);
			break;
			
			case '07':
			if(dia <= 31) 
			return (true);
			break;
			
			case '08':
			if(dia <= 31) 
			return (true);
			break;

			case '09':
			if(dia <= 30) 
			return (true);
			break;
			
			case '10':
			if(dia <= 31) 
			return (true);
			break;
			
			case '11':
			if(dia <= 30) 
			return (true);
			break;
			
			case '12':
			if(dia <= 31) 
			return (true);
			break;
		}{
		return false;
		}return true; 
	}