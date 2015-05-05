<?php 
namespace Hx\Email;

class EmailService implements EmailServiceInterface {
	
	private $adaptor, $queue;
	
	public function __construct(AdaptorInterface $adaptor, QueueInterface $queue)
	{
		$this->adaptor = $adaptor;
		
		$this->queue = $queue;
	}
	
	public function sent(
		Array $to,
		Array $cc,
		Array $bcc,
		$subject,
		$message,
		Array $attachment)
	{
		$this->adaptor->sent(
			$to, $cc, $bcc, $subject, $message, $attachment);
	}

	public function addQueue(
		Array $to,
		Array $cc,
		Array $bcc,
		$subject,
		$message,
		Array $attachment)
	{
		$this->queue->addQueue(
			$to, $cc, $bcc, $subject, $message, $attachment);
	}
	
	public function runQueue()
	{
		$this->queue->runQueue();	
	}
	
}
?>