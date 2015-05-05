<?php 
namespace Hx\Email;

interface QueueInterface {
	public function addQueue(
		Array $to, 
		Array $cc, 
		Array $bcc, 
		$subject, 
		$message, 
		Array $attachment);
	
	public function runQueue();
}
?>