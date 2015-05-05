<?php 
namespace HxExtra\Email\Adaptor;

class SmtpPhpMailer implements \Hx\Email\AdaptorInterface {
	private $mailer;
	
	/**
	 * One time configuration SMTP email setting
	 * @param String $host			email hostname
	 * @param String $emailAddress	email address
	 * @param String $displayName	name appear in 'from' tag
	 * @param String $username		username
	 * @param String $password		password
	 * @param String $method		method of encryption, TLS, SSL, default is blank - no implmentation		
	 * @param String $port			smtp outgoing port number, default is 25
	 */
	public function __construct(
			$host, 
			$emailAddress, 
			$displayName,
			$username, 
			$password,
			$method = '', 
			$port = 25) 
	{
		$this->mailer = new \PHPMailer();
		
		$this->mailer->isSMTP();
		$this->mailer->SMTPSecure = $method;
		$this->mailer->Host = $host;
		$this->mailer->Port = $port;
		$this->mailer->SMTPAuth = true;
		$this->mailer->Username = $username;
		$this->mailer->Password = $password;
		
		$this->mailer->isHTML(true);
		$this->mailer->From = $emailAddress;
		$this->mailer->FromName = $displayName;
	}
	
	public function sent(
		Array $to, 
		Array $cc, 
		Array $bcc, 
		$subject, 
		$message, 
		Array $attachment)
	{
		$this->mailer->clearAddresses();
		foreach($to as $k => $v) 
			$this->mailer->addAddress($k, $v);
		
		$this->mailer->clearCCs();
		foreach($cc as $k => $v)
			$this->mailer->addCC($k, $v);
		
		$this->mailer->clearBCCs();
		foreach($bcc as $k => $v)
			$this->mailer->addBCC($k, $v);
		
		$this->mailer->Subject = $subject;
		
		$this->mailer->Body = $message;
		
		$result = $this->mailer->send();
		
		if($result === false)
			Throw new \Hx\Email\SendException('SmtpPhpMailer:- ' . $this->mailer->ErrorInfo);
		
		$this->mailer->smtpClose();
		
		return $result;
	}
	
	public function __destruct()
	{
		$this->mailer->smtpClose();
		
		$this->mailer = null;
	}
}
?>