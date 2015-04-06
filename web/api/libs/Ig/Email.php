<?php 
namespace Ig;

/**
 * SMTP email utility class
 * 		depends on IG\Util classs
 * @author chingchetsiang
 *
 */
class Email {
	
private static $host;
private static $port = 25;
private static $stmpAuth = true;
private static $htmlContent = true;
private static $email;
private static $displayName;
private static $username;
private static $password;
private static $secure;

private static $debug = false;
private static $debugEmail = '';

/**
 * One time configuration STMP email setting
 * @param String $host
 * @param String $emailAddress
 * @param String $displayName
 * @param String $username
 * @param String $password
 * @param String $port
 * @param String $htmlContent
 * @param String $stmpAuth
 */
public static function configure(
	$host, $emailAddress, $displayName, 
	$username, $password, 
	$secure = '', $port = 25, 
	$htmlContent = true, $stmpAuth = true) {
	
	self::$host = $host;
	self::$port = $port;
	self::$stmpAuth = $stmpAuth;
	self::$htmlContent = $htmlContent;
	self::$secure = $secure;
	
	self::$email = $emailAddress;
	self::$displayName = $displayName;
	self::$username = $username;
	self::$password = $password;
}	

public static function setDebug($debug, $debugEmail) {
	self::$debug = $debug;
	self::$debugEmail = $debugEmail;
}

public static function sendEmail($tos, $ccs, $bccs, $subject, $message, $attachments) {
	$mail = new \PHPMailer();

	$mail->isSMTP();
	$mail->SMTPSecure = self::$secure;
	$mail->Host = self::$host;
	$mail->Port = self::$port;
	$mail->SMTPAuth = self::$stmpAuth;
	$mail->Username = self::$username;
	$mail->Password = self::$password;
	//x $mail->SMTPDebug = 1;

	$mail->From = self::$email;
	$mail->FromName = self::$displayName;
	
	if(self::$debug)
		$mail->addCC(self::$debugEmail, 'DEBUGGER');
	
	if(!empty($tos))
		foreach($tos as $to)
			$mail->addAddress($to['email'], $to['name']);
	
	if(!empty($ccs))
		foreach($ccs as $cc)
			$mail->addCC($cc['email'], $cc['name']);

	if(!empty($bccs))
		foreach($bccs as $bcc)
			$mail->addBCC($bcc['email'], $bcc['name']);

	
	if(!empty($attachments))
		foreach($attachments as $attachment)
			$mail->addAttachment($attachment['filepath'], $attachment['filename']);
	
	$mail->isHTML(self::$htmlContent);

	$mail->Subject = $subject;
	
	$mail->Body = $message;

	$result = $mail->send();
	
	$r = array(
		'success' => $result,
		'error' => $mail->ErrorInfo
	);
	
	$mail->smtpClose();
	
	return $r;
}

/**
 * Queue email to records
 * @param array $tos
 * @param array $ccs
 * @param array $bccs
 * @param string $subject
 * @param string $message
 * @param array $attachments
 */
public static function queueEmail($tos, $ccs, $bccs, $subject, $message, $attachments) {
	$db = \Ig\Db::getDb();

	$idd = \Ig\Db::getNextRunningNumber('email_queue');
	$item = array(
		'id' => $idd,
		'tos' => serialize($tos),
		'ccs' => serialize($ccs),
		'bccs' => serialize($bccs),
		'subject' => $subject,
		'msg' => $message,
		'attchs' => serialize($attachments),
		'last_update' => \Util::getDateTime(),
		'status' => 1, //not start yet
		'attempt' => 0
	);

	$db->email_queue->insert($item);
}

/**
 * Send first matching email queue record
 */
public static function runQueue() {
	$db = \Ig\Db::getDb();

	$maxAttempt = \Ig\Db::getKeyValue('email-max-attempt', 3);

	$raw = $db->email_queue()
		->where('status != ?', 2)
		->where('attempt <= ?', $maxAttempt)
		->fetch();

	if(empty($raw['id']))
		return;

	$result = false;
	$errorMsg = '';
	try {
		$tos = unserialize($raw['tos']);
		$ccs = unserialize($raw['ccs']);
		$bccs = unserialize($raw['bccs']);
		$attachments = unserialize($raw['attchs']);
		
		$result = self::sendEmail(
			$tos, $ccs, $bccs,
			$raw['subject'], $raw['msg'], 
			$attachments);

	} catch(\phpmailerException $ex) {
		$result = array(
			'success' => $result,
			'error' => $ex->getMessage()
		);
	} catch(\Exception $ex) {
		$result = array(
			'success' => $result,
			'error' => $ex->getMessage()
		);
	}

	$attempt = intVal($raw['attempt']) + 1;
	if($result['success']) {
		$raw->update(array(
			'last_update' => \Util::getDatetime(),
			'status' => 2, //success
			'attempt' => $attempt
		));
	} else {
		$raw->update(array(
			'last_update' => \Util::getDatetime(),
			'status' => 3, //fail
			'attempt' => $attempt,
			'error_msg' => $result['error']
		));
	}
}

}
?>