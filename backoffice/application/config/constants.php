<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE',  0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE',   0755);
define('DIR_WRITE_MODE',  0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
|
| Formas de pagamento
|
*/

define('SEMVALOR',  0);
define('DINHEIRO',  1);
define('CREDITO',   2);
define('DEBITO',    3);
define('CHEQUE',    4);

/*
|--------------------------------------------------------------------------
| Naturezas
|--------------------------------------------------------------------------
|
| Natureza de Operações
|
*/

define('ROMANEIO_ENTRADA',  1);
define('ROMANEIO_SAIDA',    2);
define('VENDA',             3);
define('DESPESA',           4);
define('RECEITA',           5);
define('TRANSFERENCIA',     6);
define('SALDOANTERIOR',     7);
define('BOLETOS',           8);

/*
|--------------------------------------------------------------------------
| Default for site and admin
|--------------------------------------------------------------------------
|
| These modes are used with site and admin
|
*/

define('NAME_SITE', 'Bola de Neve Eventos');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
