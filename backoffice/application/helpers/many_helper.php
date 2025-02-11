<?php if ( ! defined( 'BASEPATH' ))  exit ( 'No direct script access allowed' );

/*
 * debug - usado para debugar a aplicação durante o desenvolvimento
 * $content: o valor a ser mostrado na tela
 * $die: para parar de rodar o script
 */
function debug($content, $die = TRUE)
{
    echo "<pre>";
    print_r($content);
    echo "</pre>";
    
	if($die === TRUE) 
	{
        die();
    }
}

//
// Função para chamar o reCAPTCHA
//
function reCAPTCHA($recaptchaResponse)
{
	$secret = '6LcNVwsqAAAAAOQhEUlyFFcHhCU0Rl3AVEcbuPYJ';
	$url = 'https://www.google.com/recaptcha/api/siteverify';

	$data1 = array('secret' => $secret, 'response' => $recaptchaResponse);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	$status = json_decode($response, true);

	if($status['success']) 
	{
		return true;
	}
	else
	{
		return false;
	}	
}

//
// Retorna o código de acordo com o esquema Cielo
//
function paymentStatus($payment_status, $Legenda)
{
	//
	// Validação
	//
	if($payment_status == 0)
	{
		$payment_status  	 =  1;
		$status_description  =  'Pendente';
	}
	if($payment_status == 2)
	{
		$payment_status  	 =  2;
		$status_description  =  'Aprovado';
	}
	if($payment_status == 80)
	{
		$payment_status  	 =  3;
		$status_description  =  'Negado';
	}
	if($payment_status == 70)
	{
		$payment_status  	 =  4;
		$status_description  =  'Negado';
	}            
	if($payment_status == 4)
	{
		$payment_status  	 =  5;
		$status_description  =  'Negado';
	}															
	if($payment_status == 3)
	{
		$payment_status  	 =  6;
		$status_description  =  'Negado';
	}
	if($payment_status == 7)
	{
		$payment_status	 	 =  7;
		$status_description  =  'Negado';
	}
	if($payment_status == 99)
	{
		$payment_status	 	 =  99;
		$status_description  =  'Falha';
	}	

	if($Legenda == 'Codigo')
	{
		return $payment_status;
	}
	
	if($Legenda == 'Status')
	{
		return $status_description;	
	}	
}

function dataready($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	
	return $data;
} 
        
function calcula_liquido($valor_venda, $tipo, $parcelas)
{
    //Debito = 1,60 %
    //Credito a Vista = 2,90 %
    //Credito parcelado = 3,40 %
    
    if($tipo == 1)
    {
        if($parcelas > 1)
        {
            $valor_cielo = (3.40 * $valor_venda) / 100;
            
            $valor_liquido = $valor_venda - $valor_cielo;
        }
        else
        {
            $valor_cielo = (2.90 * $valor_venda) / 100;

            $valor_liquido = $valor_venda - $valor_cielo;        
        }
    }
    elseif($tipo == 2) 
    {
        $valor_cielo = (1.60 * $valor_venda) / 100;

        $valor_liquido = $valor_venda - $valor_cielo;  
	}
	else
	{
		$valor_liquido = $valor_venda;
	}
    
    return $valor_liquido;
}

/*
 *  Formata o CEP
 */
function formataCEP($cep)
{
    if($cep == "") return '';

    $valor  = str_replace(array('.','-',','),'',trim($cep));
    $tam    = strlen($valor);
    if($tam == 8)
    {
        $p1 = substr($valor,0,5);
        $p2 = substr($valor,5,3);
        $resultado = $p1."-".$p2;
    }
    else
    {
        $new = '0'.$valor;
        $p1 = substr($new,0,5);
        $p2 = substr($new,5,3);
        $resultado = $p1."-".$p2;
    }
    return $resultado;
}

/*
 * Verifica se é data
 */
function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

/*
 * limpa campos inputs
 */
function limpa_campo($string)
{
    $str =  preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $string);
    $str =  trim($str); ## Limpa espaços vazio
    $str =  strip_tags($str); ## Tira tags html e php
    $str =  addslashes($str); ## Adiciona barras invertidas a uma string
    return $str;
}

function rt_valor($vl_db, $vl_campo)
{
    return $vl_campo == '' ? $vl_db : $vl_campo;    
}
    
/*
 * converte de um formato americano para um formato brasileiro
 */
function data_time_us_to_br($dateUSA)
{
    if($dateUSA) 
	{
		$ano = substr($dateUSA, 0, 4);
		$mes = substr($dateUSA, 5, 2);
		$dia = substr($dateUSA, 8, 2);

		$hora = substr($dateUSA, -8);

		$dateBR = $dia .'/'. $mes .'/'. $ano . ' - ' . $hora;	 

        return $dateBR;
    } 
	else 
	{
        return NULL;
    }
}

/*
 * converte de um formato americano para um formato brasileiro
 */
function data_us_to_br($dateUSA)
{
    if($dateUSA) 
	{
		$ano = substr($dateUSA, 0, 4);
		$mes = substr($dateUSA, 5, 2);
		$dia = substr($dateUSA, 8, 2);

		$dateBR = $dia .'/'. $mes .'/'. $ano;	 

        return $dateBR;
    } 
	else 
	{
        return NULL;
    }
}

/*
 * converte de um formato brasileiro para um formato americano
 */
function data_br_to_us($dateBR)
{
    if($dateBR) {
         $ano = substr($dateBR, 6, 4);
         $mes = substr($dateBR, 3, 2);
         $dia = substr($dateBR, 0, 2);
         $dateUSA = $ano .'-'. $mes .'-'. $dia;
         return $dateUSA;
    } else {
        return NULL;
    }
}

/*
 * recebe uma string e retorna somente os numeros
 */
function limpa_num($str)
{
    if($str != "") {
        return preg_replace("/[^0-9\s\.\,]/", "", $str);
    } 
    
    return $str;
}

/*
 * Incluir todos Zeros(0) à esquerda (sub-familia)
 */
function gera_matricula($v)
{
    return str_pad($v, 8, "0", STR_PAD_LEFT);		
}

/*
 * retorna o valor formatado para guardar no BD 
 */
function num_to_db($num = 0)
{
    if($num != 0)
	{
        $num = str_replace('.', '', $num);
        $num = str_replace(',', '.', $num);
        
        return number_format($num, 2, '.', '');
    }
    
    return '0.00';
}

/*
 * retorna o valor do BD e formata para exibir ao usuario final
 */
function num_to_user($num = 0)
{
    if($num != 0)
	{
        return number_format($num, 2, ',', '.');
    }
    
    return '0,00';
}

/*
 * retorna o valor formatado para guardar no BD 
 */
function num_cielo_to_db($num = 0)
{
    if($num != 0)
	{
        $num = str_replace('.', '', $num);
        $num = str_replace(',', '.', $num);
        
        return number_format(substr($num, 0, -2), 2, '.', '');
    }
    
    return '0.00';
}

/*
 * cria uma tag p
 */
function p($param)
{
    if($param != NULL)
	{
        return "<p>$param</p>";
    }
    
    return FALSE;
}

/*
 * cria uma tag span
 */
function span($string, $class)
{
    if($string)
	{
        $span = "<span ";
        if($class) $span.= "class='$class'";
        $span.= ">";
        $span.= $string;        
        $span.= "</span>";
        
        return $span;
    }
    return FALSE;
}

/*
 * Gera a posição dos numeros para o NN
 */
function inclui_zero_esq($v)
{
	return str_pad($v, 8, "0", STR_PAD_LEFT);		
}

/*
 * Exibe um menu de acordo com os 3 tipos de perfis
 */
function get_menu($id)
{
	$menu = '';
	
    if($id == 0)
    {
        $menu .= '<div id="Menu">
                    <ul class="adxm menu">
						<li><a href="javascript:void(0)">Eventos</a>
							<ul class="options">
								<li><a href="'.base_url().'eventos" title="Cadastrar um novo Evento">Gerenciar Eventos</a>    
								<li><a href="'.base_url().'links" title="Criar um lote para um Evento">Gerenciar Lotes de Eventos</a></li>
								<li><a href="'.base_url().'relatorios/participantes_eventos" title="Relatório Participantes">Relatório de Participantes</a></li>
							</ul>
						</li>
						<li><a href="javascript:void(0)">Financeiro</a>
							<ul class="options">
								<li><a href="'.base_url().'financeiro" title="Pagantes por Evento">Pagantes por Evento</a>    
								<li><a href="'.base_url().'relatorios/pagantes_eventos" title="Relatório Financeiro">Relatório Financeiro</a></li>
							</ul>
						</li>													
						<li class="title"><a href="'.base_url().'usuarios" title="Usuários">Usuários</a></li>
						<li><a href="javascript:void(0)">Check-in</a>
							<ul class="options">
								<li><a href="'.base_url().'presenca" title="Check-in Online">Check-in Online</a>    
								<li><a href="'.base_url().'presenca/cpf" title="Check-in CPF">Check-in CPF</a></li>
							</ul>
						</li>
						<!--<li class="title"><a href="'.base_url().'assets/doc/Manual.pdf" target="_blank" title="Ajuda">Ajuda para a Plataforma</a></li>
						<li class="title"><a href="'.base_url().'assets/doc/Evento_Inscricoes.pdf" target="_blank" title="Ajuda">Ajuda para os sites dos Eventos</a></li>-->
                        <li class="title"><a href="'.base_url().'logoff" title="Sair do Sistema">Sair do Sistema</a></li>
                    </ul>
                  </div>';
    }
	else
	{
        $menu .= '<div id="Menu">
                    <ul class="adxm menu">
						<li><a href="'.base_url().'links" title="Criar um lote para um Evento">Gerenciar Lotes de Eventos</a></li>
						<li><a href="javascript:void(0)">Check-in</a>
							<ul class="options">
								<li><a href="'.base_url().'presenca" title="Check-in Online">Check-in Online</a>    
								<li><a href="'.base_url().'presenca/cpf" title="Check-in CPF">Check-in CPF</a></li>
							</ul>
						</li>
						<li><a href="javascript:void(0)">Relatórios Financeiro</a>
							<ul class="options">
								<li><a href="'.base_url().'relatorios/pagantes_eventos_balcao" title="Pagantes pelo Balcão">Pagantes pelo Balcão</a>    
								<li><a href="'.base_url().'relatorios/pagantes_eventos_bilheteria" title="Pagantes pela Bilheteria">Pagantes pela Bilheteria</a></li>
								<li><a href="'.base_url().'relatorios/participantes_eventos" title="Relatório Participantes">Relatório de Participantes</a></li>
							</ul>
						</li>
                        <li class="title"><a href="'.base_url().'logoff" title="Sair do Sistema">Sair do Sistema</a></li>
                    </ul>
                  </div>';
    }

    return $menu;
}

/*
 * Converte os caracteres em minusculos
 */
function deepLower($texto)
{
    $texto = strtr($texto, "
    ACELNÓSZZABCDEFGHIJKLMNOPRSTUWYZQ
    XV?????????????????????????????????
    ÂÀÁÄÃÊÈÉËÎÍÌÏÔÕÒÓÖÛÙÚÜÇ
    ", "
    acelnószzabcdefghijklmnoprstuwyzq
    xv?????????????????????????????????
    âàáäãêèéëîíìïôõòóöûùúüç
    ");
    return strtolower($texto);
}

/*
 * Converte os caracteres em maiusculos
 */
function deepHigher($texto)
{
    $texto = strtr($texto, "
    acelnószzabcdefghijklmnoprstuwyzq
    xv?????????????????????????????????
    âàáäãêèéëîíìïôõòóöûùúüç
    ", "
    ACELNÓSZZABCDEFGHIJKLMNOPRSTUWYZQ
    XV?????????????????????????????????
    ÂÀÁÄÃÊÈÉËÎÍÌÏÔÕÒÓÖÛÙÚÜÇ
    ");
    return strtoupper($texto);
}

/*
* Method to load javascript files into your project.
* @param array $js
*/
function formatCPF_CNPJ($campo, $formatado = true)
{
	$codigoLimpo = preg_replace("[' '-./ t]", '', $campo);

	$tamanho = (strlen($codigoLimpo) -2);

	if($tamanho != 9 && $tamanho != 12)
	{
		return false; 
	}
	if($formatado)
	{ 
		$mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##'; 
		$indice = -1;

		for ($i=0; $i < strlen($mascara); $i++) 
		{
			if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];
		}
		$retorno = $mascara;
	}
	else
	{
		$retorno = $codigoLimpo;
	}
	
	return $retorno;
}

/*
* Metodo para gerar perfil
*/
function get_perfil($id)
{
	if($id == 0)
	{
		$perfil = "Sede";
	}
	if($id == 1)
	{
		$perfil = "Filial";
	}
	if($id == 2)
	{
		$perfil = "Filial";
	}

	return $perfil;
}

/*
* Metodo para exibir a imagem
*/
function get_picture($imagem, $largura, $altura)
{
	if(trim($imagem) == "" || trim($imagem) == NULL)
	{
		$imagem = '<img src="'.base_url().'thumbs/avatar/' . $largura . '/' . $altura . '/avatar" align="left">';
	}
	else
	{
		$imagem = str_replace('.jpg', '', $imagem);

		$imagem = '<img src="'.base_url().'thumbs/avatar/' . $largura . '/' . $altura . '/' . $imagem . '" align="left">';	
	}

	return $imagem;
}

/*
* Metodo para converter uma data
*/
function converteData($param, $tipo = "data")
{
	if($tipo=="sql"){
		$passo = explode("/", $param);
		$obj = $passo[2]."-".$passo[1]."-".$passo[0];
	}
	if($tipo=="data"){
		$passo = explode("-", $param);
		if(strlen($passo[0])==1){
			$passo[0] = "0".$passo[0];
		}
		if(strlen($passo[1])==1){
			$passo[1] = "0".$passo[1];
		}
		$obj = $passo[2]."/".$passo[1]."/".$passo[0];		
	}
	return $obj;
}

/*
* Metodo para remover strings
*/
function LimitaStr($str, $limit)
{
	$str = RemoveHTML($str);
	if(strlen($str)>$limit){
		$str = substr($str, 0, $limit);
		$ultChr = strrpos($str, ' ');
		$str = substr($str, 0, $ultChr).' ...';
	}
	return $str;
}

function LimitaStrPDF($str, $limit)
{
	$str = RemoveHTML($str);
	if(strlen($str)>$limit){
		$str = substr($str, 0, $limit);
		$ultChr = strrpos($str, ' ');
		$str = substr($str, 0, $ultChr);
	}
	return $str;
}

function RemoveHTML($str)
{
	$str = strip_tags($str);
	$str = trim($str);
	return $str;
}

/*
* Metodo para remover acentos
*/
function remove_accents($string)
{
	if(!preg_match('/[\x80-\xff]/', $string))
			return $string;

	if(seems_utf8($string)){
			$chars = array(
			// Decompositions for Latin-1 Supplement
			chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
			chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
			chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
			chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
			chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
			chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
			chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
			chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
			chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
			chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
			chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
			chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
			chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
			chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
			chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
			chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
			chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
			chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
			chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
			chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
			chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
			chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
			chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
			chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
			chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
			chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
			chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
			chr(195).chr(191) => 'y',
			// Decompositions for Latin Extended-A
			chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
			chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
			chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
			chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
			chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
			chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
			chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
			chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
			chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
			chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
			chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
			chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
			chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
			chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
			chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
			chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
			chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
			chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
			chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
			chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
			chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
			chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
			chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
			chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
			chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
			chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
			chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
			chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
			chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
			chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
			chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
			chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
			chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
			chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
			chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
			chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
			chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
			chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
			chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
			chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
			chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
			chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
			chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
			chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
			chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
			chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
			chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
			chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
			chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
			chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
			chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
			chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
			chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
			chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
			chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
			chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
			chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
			chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
			chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
			chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
			chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
			chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
			chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
			chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
			// Euro Sign
			chr(226).chr(130).chr(172) => 'E',
			// GBP (Pound) Sign
			chr(194).chr(163) => '');

			$string = strtr($string, $chars);
	}else{
			// Assume ISO-8859-1 if not UTF-8
			$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
					.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
					.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
					.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
					.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
					.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
					.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
					.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
					.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
					.chr(252).chr(253).chr(255);

			$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

			$string = strtr($string, $chars['in'], $chars['out']);
			$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
			$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
			$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

/*
* Metodo para converter para UTF8
*/
function seems_utf8($str)
{
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
			$c = ord($str[$i]);
			if ($c < 0x80) $n = 0; # 0bbbbbbb
			elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
			elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
			elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
			elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
			elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
			else return false; # Does not match any model
			for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
					if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
							return false;
			}
	}
	return true;
}

/*
* Metodo para encodar para UTF8
*/
function utf8_uri_encode( $utf8_string, $length = 0 )
{
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

			$value = ord( $utf8_string[ $i ] );

			if ( $value < 128 ) {
					if ( $length && ( $unicode_length >= $length ) )
							break;
					$unicode .= chr($value);
					$unicode_length++;
			} else {
					if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

					$values[] = $value;

					if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
							break;
					if ( count( $values ) == $num_octets ) {
							if ($num_octets == 3) {
									$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
									$unicode_length += 9;
							} else {
									$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
									$unicode_length += 6;
							}

							$values = array();
							$num_octets = 1;
					}
			}
	}

	return $unicode;
}

/*
* Metodo para gerar uma URL amigavel
*/
function sanitize_title_with_dashes($title)
{
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	$title = remove_accents($title);
	if (seems_utf8($title)) {
			if (function_exists('mb_strtolower')) {
					$title = mb_strtolower($title, 'UTF-8');
			}
			$title = utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}

/*
 *  Retorna o descritivo do modulo ex: CARÁTER
 */
function getModulo($idModulo)
{
   return $this->db
			   ->where('Id', $idModulo)
			   ->get('tb_modulo')
			   ->row()
			   ->Nome;                       
}

/*
 *  Funções da Amazon
 */
function rsa_sha1_sign($policy, $private_key_filename) 
{
    $signature = "";

    // load the private key
    $fp = fopen($private_key_filename, "r");
    $priv_key = fread($fp, 8192);
    fclose($fp);
    $pkeyid = openssl_get_privatekey($priv_key);

    // compute signature
    openssl_sign($policy, $signature, $pkeyid);

    // free the key from memory
    openssl_free_key($pkeyid);

    return $signature;
}

/*
 *  Funções da Amazon
 */
function url_safe_base64_encode($value) 
{
    $encoded = base64_encode($value);
    // replace unsafe characters +, = and / with 
    // the safe characters -, _ and ~
    return str_replace(
        array('+', '=', '/'),
        array('-', '_', '~'),
        $encoded);
}

/*
 *  Funções da Amazon
 */
function encode_query_params($stream_name) 
{
    // the adobe flash player has trouble with query parameters being passed into it, 
    // so replace the bad characters with their url-encoded forms
    return str_replace(
        array('?', '=', '&'),
        array('%3F', '%3D', '%26'),
        $stream_name);
}

/*
 *  Funções da Amazon
 */
function create_stream_name($stream, $policy, $signature, $key_pair_id, $expires) 
{
    $result = $stream;

    $path="";  //Change made here to fix missing $path variable

    // if the stream already contains query parameters, attach the new query parameters to the end
    // otherwise, add the query parameters
    $separator = strpos($stream, '?') == FALSE ? '?' : '&';

    // the presence of an expires time means we're using a canned policy
    if($expires) {
        $result .= $path . $separator . "Expires=" . $expires . "&Signature=" . $signature . "&Key-Pair-Id=" . $key_pair_id;
    } 
    // not using a canned policy, include the policy itself in the stream name
    else {
        $result .= $path . $separator . "Policy=" . $policy . "&Signature=" . $signature . "&Key-Pair-Id=" . $key_pair_id;
    }

    // new lines would break us, so remove them
    return str_replace('\n', '', $result);
}

/*
 *  Funções da Amazon
 */
function get_canned_policy_stream_name($video_path, $private_key_filename, $key_pair_id, $expires) 
{
    // this policy is well known by CloudFront, but you still need to sign it, 
    // since it contains your parameters
    $canned_policy = '{"Statement":[{"Resource":"' . $video_path . '","Condition":{"DateLessThan":{"AWS:EpochTime":'. $expires . '}}}]}';
    // the policy contains characters that cannot be part of a URL, 
    // so we Base64 encode it
    $encoded_policy = url_safe_base64_encode($canned_policy);
    // sign the original policy, not the encoded version
    $signature = rsa_sha1_sign($canned_policy, $private_key_filename);
    // make the signature safe to be included in a url
    $encoded_signature = url_safe_base64_encode($signature);

    // combine the above into a stream name
    $stream_name = create_stream_name($video_path, null, $encoded_signature, $key_pair_id, $expires);
    // url-encode the query string characters to work around a flash player bug
    //return encode_query_params($stream_name);
    return $stream_name;
}

/*
 *  Funções da Amazon
 */
function get_custom_policy_stream_name($video_path, $private_key_filename, $key_pair_id, $policy) 
{
    // the policy contains characters that cannot be part of a URL, 
    // so we Base64 encode it
    $encoded_policy = url_safe_base64_encode($policy);
    // sign the original policy, not the encoded version
    $signature = rsa_sha1_sign($policy, $private_key_filename);
    // make the signature safe to be included in a url
    $encoded_signature = url_safe_base64_encode($signature);

    // combine the above into a stream name
    $stream_name = create_stream_name($video_path, $encoded_policy, $encoded_signature, $key_pair_id, null);
    // url-encode the query string characters to work around a flash player bug
    //return encode_query_params($stream_name);    
    return $stream_name;
}