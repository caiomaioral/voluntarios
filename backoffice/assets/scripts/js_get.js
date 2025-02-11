/*
* ARQUIVO: JS_GET
*
* AUTOR: FABIO LIMA CE
*
* DESCRIPTION LANGUAGE: PORTUGESE
*
* DESCRICAO:
* TRATA-SE DE UM SCRIPT QUE INTERPRETA PARAMETROS PASSADOS ATRAVES DA URL
* E OS TRANSFORMA EM VARIAVEIS JAVASCRIPT. PODE SER USADO EM SITUACOES
* SIMPLES QUE NAO EXIGEM O USO DE PHP OU CGI.
* PODE SUBSTITUIR O USO DE $GET E $POST NO PHP EM CASOS SIMPLES.
*
* COMO USAR:
* BASTA INCLUIR ESTE CODIGO NA PAGINA QUE IRA PROCESSAR OS PARAMETROS.
* PARA ISSO, INCLUA ESTE ARQUIVO USANDO A TAG:
* <script language="javascript" type="text/javascript" src="js_get.js"></script>.
*
* PARA OBTER OS PARAMETROS, BASTA CHAMAR A FUNCAO "GET" ASSIM:
* js_get.get("nome_parametro").
*
* DICAS, SUGESTOES E CRITICAS: fabiolimapessoal@yahoo.com.br
*
*/

/******************************************************/
/******************************************************/

// FUNCAO ADD:
// ADICIONA UM ITEM AO DICIONARIO
// UM ITEM EH COMPOSTO POR UMA CHAVE E UM VALOR
function add(key, val)
{
    var tup = new Array(key, val);
    this.tab.push(tup);
}

// FUNCAO GETBYKEY:
// RECEBE A CHAVE DE PESQUISA
// RETORNA UM VALOR ASSOCIADO A ESSA CHAVE
function getByKey(key)
{
    var i=0;
    for(i; i<this.tab.length; i++)
    {
        if(this.tab[i][0]==key)
        {
            return this.tab[i][1];
        }
    }
    return "";
}

// FUNCAO GETBYINDEX:
// RECEBE UM NUMERO (INDEX)
// RETORNA UM VALOR ASSOCIADO A ESSE NUMERO
function getByIndex(idx)
{
    if(idx<this.tab.length){ return this.tab[idx][1]; }
}

// FUNCAO GETARRAY:
// RETORNA UM ARRAY CONTENDO TODOS (E APENAS) OS VALORES DO DICIONARIO
function getArray()
{
    var ret = new Array();
    var i=0;
    for(i; i<this.tab.length; i++)
    {
        ret.push(this.tab[i][1]);
    }
    return ret;
}

// CLASSE DIC (DE DICIONARIO)
// POSSUI APENAS UMA COLECAO CHAMADA TAB (TABELA) DE ITENS
// UM ITEM EH COMPOSTO POR UMA CHAVE E UM VALOR
function dic()
{
    this.tab = new Array();
}

// ASSOCIANDO AS FUNCOES ADD, GETBYKEY,
// GETBYINDEX E GETARRAY A CLASSE DIC
// POR MEIO DO RECURSO PROTOTYPE
dic.prototype.add=add;
dic.prototype.getByKey=getByKey;
dic.prototype.getByIndex=getByIndex;
dic.prototype.getArray=getArray;

/******************************************************/
/******************************************************/

// FUNCAO GET:
// RECEBE UM NOME DE PARAMETRO
// RETORNA O VALOR ASSOCIADO A ESSE PARAMETRO
function get(key)
{
    return this.parms.getByKey(key);
}

// FUNCAO INDEX:
// RECEBE UM NUMERO DE SEQUENCIA (INDICE)
// RETORNA O VALOR ASSOCIADO A ESSE NUMERO
function index(idx)
{
    return this.parms.getByIndex(idx);
}

// FUNCAO VALUES:
// RETORNA UM ARRAY CONTENDO TODOS OS VALORES
function values()
{
    return this.parms.getArray();
}

// FUNCAO PARSE:
// INTERPRETA A URL PROCURANDO PARAMETROS E VALORES
// TAMBEM GERA PREENCHE A COLECAO DE PARAMETROS DO OBJETO JSGET
function parse(){

	var url = location.href
	var ini = false;
	var inipar = false;
	var inival = false;
	var par = "";
	var val = "";

	var i=0;
	for(i; i<url.length; i++)
	{
		if(!ini){
			if(url.charAt(i)=='?')
			{
				inipar = true;
				ini = true;
			}
		}else{
			if(url.charAt(i)=='&')
			{
				inipar = true;
				inival = false;
				this.parms.add(par, val);
				par = "";
			}
			else if(url.charAt(i)=='=')
			{
				inival = true;
				inipar = false;
				val = "";
			}
			else if(inipar)
			{
				par += url.charAt(i)
			}
			else if(inival)
			{
				val += url.charAt(i);
				if(i==url.length-1)
				{
					this.parms.add(par, val);
				}
			}
		}
	}
}

// CLASSE JSGET:
// POSSUI UMA URL E UMA COLECAO DE PARAMETROS COMO PROPRIEDADES
// POSSUI AS FUNCOES GET E PARSE COMO METODOS
function jsGet()
{
    this.url=location.href;
    this.parms=new dic();
    this.parse();
}

// ASSOCIANDO AS FUNCOES PARSE E GET A CLASSE JSGET
// POR MEIO DO RECURSO PROTOTYPE
jsGet.prototype.parse=parse;
jsGet.prototype.get=get;
jsGet.prototype.index=index;
jsGet.prototype.values=values;


// INSTANCIANDO O OBJETO JS_GET, O QUAL SERA USADO PARA
// OBTER OS PARAMETROS PASSADOS ATRAVES DA URL
js_get = new jsGet();