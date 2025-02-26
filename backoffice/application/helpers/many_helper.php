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

//
// Teste de ordenação
//
function bubbleSort($array)
{
  for($i = 0; $i < count($array); $i++)
  {
     for($j = 0; $j < count($array) - 1; $j++)
     {
       if($array[$j] > $array[$j + 1])
       {
         $aux = $array[$j];
         $array[$j] = $array[$j + 1];
         $array[$j + 1] = $aux;
       }
     }
  }

  return $array;
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
// uses regex that accepts any word character or hyphen in last name
//
function split_name($name) 
{
    $parts = array();

    while ( strlen( trim($name)) > 0 ) {
        $name = trim($name);
        $string = preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $parts[] = $string;
        $name = trim( preg_replace('#'.preg_quote($string,'#').'#', '', $name ) );
    }

    if (empty($parts)) {
        return false;
    }

    $parts = array_reverse($parts);
    $name = array();
    $name['first_name'] = $parts[0];
    $name['middle_name'] = (isset($parts[2])) ? $parts[1] : '';
    $name['last_name'] = (isset($parts[2])) ? $parts[2] : ( isset($parts[1]) ? $parts[1] : '');
    
    return $name;
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

    $path = "";  //Change made here to fix missing $path variable

    // if the stream already contains query parameters, attach the new query parameters to the end
    // otherwise, add the query parameters
    $separator = strpos($stream, '?') == FALSE ? '?' : '&';

    // the presence of an expires time means we're using a canned policy
    if($expires) 
    {
        $result .= $path . $separator . "Expires=" . $expires . "&Signature=" . $signature . "&Key-Pair-Id=" . $key_pair_id;
    } 
    // not using a canned policy, include the policy itself in the stream name
    else 
    {
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
