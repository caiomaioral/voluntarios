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
    if ($die === TRUE) {
        die();
    }
}

function jwt_generator()
{
    //
    // Chave passada pela Granito
    //
    $key = '2KUppgtGMpA0feJd7djBNbd48zKa/TJX6666NTCvly/J75hY99v5Od+sXstKvlymznj6KzR62niDTGv2EK/Ps6e1HcJDTMIKlDev+IYNzoZpB0p1clUFEwbWoENQka245Y7tWRCQOdYhouU9WQ7sW4HXwpf9X9N8o0ni56FSOHsp/VY0ZuyYqbbpR33PSV1gpSqdfqOGi4lGAEsLItxWDzS6m1xgN9FOL8kDfMWMdzxoKrHkQmXmOCdUIco1t4X6WQvS8zAPbVmiwdUolRLzSE5kyOd5ExIVgcxkc79uFtTmVwx+u0tNXu2b1CVSIvYqYAEUP91gEm3U13QEkecwow==';

    //
    // Header Token
    //
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $exp = new DateTime('now');
    $exp->add(new DateInterval('P0DT0H5M0S'));

    $jti = base64_encode(12345);

    //
    // Payload - Content
    //
    $payload = [
        'jti' => uniqid(),
        'iat' => (new DateTime('now'))->getTimestamp(), 
        'exp' => $exp->getTimestamp(), 
        'iss' => '000113c4-0000-0000-7cd5-749adf9ed908'     
    ];

    //
    // JSON
    //
    $header = json_encode($header);
    $payload = json_encode($payload);

    //Base 64
    $header = base64UrlEncode($header);
    $payload = base64UrlEncode($payload);

    //Sign
    //Cria a assinatura concatenando o header64 e o payload64 e passando para base64, assinando com o a secret decodada de base64
    $sign = hash_hmac("sha256", ($header. "." .$payload), base64_decode($key), true);
    $sign = base64UrlEncode($sign);

    //Token
    $token = $header . '.' . $payload . '.' . $sign;

    return $token;    
}

//
// Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'
//
function base64UrlEncode($data)
{
    // First of all you should encode $data to Base64 string
    $b64 = base64_encode($data);

    // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
    if ($b64 === false) {
        return false;
    }

    // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
    $url = strtr($b64, '+/', '-_');

    // Remove padding character from the end of line and return the Base64URL result
    return rtrim($url, '=');
}

//
// Mostra a string do nome do mês
//
function show_month($month)
{
    switch ($month) 
    {
        case 1:
            $month = 'Janeiro';
            break;
        case 2:
            $month = 'Fevereiro';
            break;
        case 3:
            $month = 'Março';
            break;
        case 4:
            $month = 'Abril';
            break;
        case 5:
            $month = 'Maio';
            break;
        case 6:
            $month = 'Junho';
            break;
        case 7:
            $month = 'Julho';
            break;
        case 8:
            $month = 'Agosto';
            break;
        case 9:
            $month = 'Setembro';
            break;
        case 10:
            $month = 'Outubro';
            break;
        case 11:
            $month = 'Novembro';
            break;
        case 12:
            $month = 'Dezembro';
            break;                        
    }

    return $month;
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
		$status_description  =  'Rejeitada';
    }
    else if($payment_status == 2)
	{
		$payment_status  	 =  2;
		$status_description  =  'Aprovado';
    }
    else if($payment_status == 80)
	{
		$payment_status  	 =  3;
		$status_description  =  'Negado';
    }
    else if($payment_status == 70)
	{
		$payment_status  	 =  4;
		$status_description  =  'Rejeitada';
    }
    else if($payment_status == 4)
	{
		$payment_status  	 =  5;
		$status_description  =  'Cancelado';
    }
    else if($payment_status == 3)
	{
		$payment_status  	 =  6;
		$status_description  =  'Não finalizada';
    }
    else if($payment_status == 1)
	{
		$payment_status	 	 =  7;
		$status_description  =  'Autorizada';
    }
    else if($payment_status == 99)
	{
		$payment_status	 	 =  6;
		$status_description  =  'Não finalizada';
    }
    else
    {
		$payment_status	 	 =  6;
		$status_description  =  'Não finalizada';
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

/*
 * Gera a posição dos numeros para o NN
 */
function inclui_zero_esq($v)
{
    return str_pad($v, 8, "0", STR_PAD_LEFT);		
}

/*
 * Função que me retorna somente numero
 */
function get_numbers($str) 
{
    return preg_replace("/[^0-9]/", "", $str);
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
function data_us_to_br($dateUSA)
{
    if($dateUSA) {
         $ano = substr($dateUSA, 0, 4);
         $mes = substr($dateUSA, 5, 2);
         $dia = substr($dateUSA, 8, 2);
         $dateBR = $dia .'/'. $mes .'/'. $ano;
         return $dateBR;
    } else {
        return NULL;
    }
}

/*
 * converte de um formato americano para um formato brasileiro
 */
function month_us_to_br($dateUSA)
{
    if($dateUSA) {
         $mes = substr($dateUSA, 5, 2);
         $dateBR = $mes;
         return $dateBR;
    } else {
        return NULL;
    }
}

/*
 * converte de um formato americano para um formato brasileiro
 */
function year_us_to_br($dateUSA)
{
    if($dateUSA) {
         $ano = substr($dateUSA, 0, 4);
         $dateBR = $ano;
         return $dateBR;
    } else {
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
    if($str != "") 
	{
        return preg_replace("/[^0-9\s\.\,]/", "", $str);
    } 
    
    return $str;
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
 * retiro todos Zeros(0) à esquerda
 */
function limpa_zero_esq($v)
{
    return ltrim($v, '0');
}

/*
 * Incluir todos Zeros(0) à esquerda (sub-familia)
 */
function gera_matricula($v)
{
    return str_pad($v, 8, "0", STR_PAD_LEFT);		
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
 *  @param string $cpf O CPF com ou sem pontos e traço
 *  @return bool True para CPF correto - False para CPF incorreto
 */
function validaCPF($cpf = false) 
{
    /**
     * Multiplica dígitos vezes posições 
     *
     * @param string $digitos Os digitos desejados
     * @param int $posicoes A posição que vai iniciar a regressão
     * @param int $soma_digitos A soma das multiplicações entre posições e digitos
     * @return int Os digitos enviados concatenados com o último dígito
     *
     */

    if(!function_exists('calc_digitos_posicoes'))
    {
        function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0) 
        {
            // Faz a soma dos digitos com a posição
            // Ex. para 10 posições: 
            //   0    2    5    4    6    2    8    8   4
            // x10   x9   x8   x7   x6   x5   x4   x3  x2
            // 	 0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
            for ($i = 0; $i < strlen($digitos); $i++) 
            {
                $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                $posicoes--;
            }

            // Captura o resto da divisão entre $soma_digitos dividido por 11
            // Ex.: 196 % 11 = 9
            $soma_digitos = $soma_digitos % 11;

            // Verifica se $soma_digitos é menor que 2
            if($soma_digitos < 2) 
            {
                // $soma_digitos agora será zero
                $soma_digitos = 0;
            } 
            else 
            {
                // Se for maior que 2, o resultado é 11 menos $soma_digitos
                // Ex.: 11 - 9 = 2
                // Nosso dígito procurado é 2
                $soma_digitos = 11 - $soma_digitos;
            }

            // Concatena mais um digito aos primeiro nove digitos
            // Ex.: 025462884 + 2 = 0254628842
            $cpf = $digitos . $soma_digitos;

            // Retorna
            return $cpf;
        }
    }

    // Verifica se o CPF foi enviado
    if(!$cpf) 
    {
        return false;
    }

    // Remove tudo que não é número do CPF
    // Ex.: 025.462.884-23 = 02546288423
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

    // Verifica se o CPF tem 11 caracteres
    // Ex.: 02546288423 = 11 números
    if(strlen($cpf) != 11) 
    {
        return false;
    }	

    // Verifica se esta com sequencia sacana
    if($cpf == 00000000000 || $cpf == 11111111111 || $cpf == 22222222222 || $cpf == 33333333333 || $cpf == 44444444444 || $cpf == 55555555555 || $cpf == 66666666666 || $cpf == 77777777777 || $cpf == 88888888888 || $cpf == 99999999999)
    {
        return false;
    }     

    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    $digitos = substr($cpf, 0, 9);

    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    $novo_cpf = calc_digitos_posicoes($digitos);

    // Faz o cálculo dos 10 digitos do CPF para obter o último dígito
    $novo_cpf = calc_digitos_posicoes($novo_cpf, 11);

    // Verifica se o novo CPF gerado é identico ao CPF enviado
    if($novo_cpf === $cpf)
    {
        // CPF válido
        return true;
    } 
    else 
    {
        // CPF inválido
        return false;
    }
}
