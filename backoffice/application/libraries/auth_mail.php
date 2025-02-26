<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Email Authentication Class
 *
 * Permits email authentication to be sent using Mail or SMTP.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Leandro Martins <leandrodma@hotmail.com>
 */
  
class Auth_mail {
  
	var $servidor 				=  "smtp.boladeneve.com";							// Host address. Example: mail.test.com
	var $porta					=  587; 											// Mail or SMTP port. Default: 25
	var $remetente              =  "Instituto Global";								// From name
	var $email                  =  "secretaria@boladeneve.com";						// Email address of institute
	var $usuario				=  "institutoglobal@boladeneve.com";				// Mail or SMTP Username. Example: admin@test.com
	var $senha					=  "Global777";										// Mail or SMTP Password
	var $tempoespera 			=  5; 												// Server Timeout. Required
	var $conexao				=  ""; 												// Socket Connection
	var $crlf					=  "";			
	var $debug					=  "";
	
	/**
	 * Constructor - Sets Email Preferences
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
	}
	
	protected function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$method = 'set_' . $key;

				if (method_exists($this, $method))
				{
					$this->$method($val);
				}
				else
				{
					$this->$key = $val;
				}
			}
		}
	}
	
	public function set_servidor($host_address)
	{
		$this->servidor = $host_address;
	}
	
	public function set_porta($host_port)
	{
		$this->porta = $host_port;
	}
	
	public function set_usuario($usermail)
	{
		$this->usuario = $usermail;
	}
	
	public function set_senha($passmail)
	{
		$this->senha = $passmail;
	}	

	public function set_tempoespera($time)
	{
		$this->tempoespera = $time;
	}

	private function set_hospedagem($sys)
	{
		switch($sys)
		{
			case "WINNT":
				$this->crlf = "\r\n";
			break;
			
			case "UNIX":
				$this->crlf = '\n';
			break;
		}
	}
	
	public function set_debug($bldebug)
	{
		$this->debug = $bldebug;
	}
	
	/**
	 * Send Mail
	 * @param string
	 * @param string
	 * @access	public
	 * @return bool
	 */
	public function send($strMail, $strSubject, $strMessage)
	{
		if($this->connect())
		{
			$this->put("EHLO ".$this->servidor);
			$this->auth();
			$this->put('MAIL FROM: <'.$this->usuario.'>');
			$this->put('RCPT TO: <'.$strMail.'>');
			$this->put("DATA");
			$this->put($this->toHeader($strMail, $strSubject));
			$this->put("\r\n");
			$this->put($strMessage);
			$this->put(".");						
			$this->close_connection();
			
			return true;
		}
		else
		{
			return false;
		}
	}	
	
	/**
	 * Function Auth
	 * @acess private
	 * @return bool
	**/	
	private function auth()
	{
		$this->put("AUTH LOGIN");
		$this->put(base64_encode($this->usuario));
		$this->put(base64_encode($this->senha));
	}
	
	function toHeader($to, $subject)
	{
		$header =  "Message-Id: <". date('YmdHis').".". md5(microtime()).".". strtoupper($this->usuario) ."> \r\n";
		$header .= "From: ".$this->remetente." <".$this->email."> \r\n";
		$header .= "To: <".$to."> \r\n";
		$header .= "Subject:".$subject."\r\n";
		$header .= "Date:".date('r',time())."\r\n";
		$header .= "X-MSMail-Priority: High \r\n";
		$header .= "Content-Type:text/html; charset=utf-8";
			
		return $header;
	}
	
	/**
	 * Function Socket
	 * @acess private
	 * @return bool
	**/
	private function connect()
	{
		$this->conexao  = @fsockopen(	
										$this->servidor,
										$this->porta,
										$errno,
										$errstr,
										$this->tempoespera
									);
		
		return (($errno) ? false : true);
	}

		
	/*
	 * Connection Close
	 * @return bool
	*/
	private function close_connection()
	{
		$this->put("QUIT");
		if($this->debug == true)
		{
			while (!feof ($this->conexao)) 
			{
   				echo fgets($this->conexao) . "<br />" . $this->crlf;
			}
		}
		return fclose($this->conexao);
	 }
	
	private function put($cmd)
	{
		fputs($this->conexao, $cmd . "\n");
	}
}
  
?>