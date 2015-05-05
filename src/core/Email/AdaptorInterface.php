<?php 
namespace Hx\Email;

interface AdaptorInterface {
	public function sent(Array $to, Array $cc, Array $bcc, $subject, $message, Array $attachment);
}
?>