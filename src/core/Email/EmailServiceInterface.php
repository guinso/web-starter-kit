<?php 
namespace Hx\Email;

interface EmailServiceInterface {
	
	/**
	 * Sent an email message
	 * @param 	array 	$to			main receipient, format ['email A' => 'name A', 'email B' => 'name B']
	 * @param 	array 	$cc			carbon copy receipient, format ['email A' => 'name A', 'email B' => name B']
	 * @param 	array 	$bcc		bind carbon copy receipient, format ['email A' => 'name A', 'email B' => 'name B']
	 * @param 	string 	$subject	title of email
	 * @param 	string 	$message	message body
	 * @param 	array 	$attachment	physical file attachment, format ['file name A' => 'absolute file path A', 'file name B' => 'absolute file path B']
	 * @return 	boolean	sent email result
	 */
	public function sent(
		Array $to, 
		Array $cc, 
		Array $bcc, 
		$subject, 
		$message, 
		Array $attachment);
	
	/**
	 * Add email into a queue in order to defer for late time deliver
	 * @param array 	$to			main receipient, format ['email A' => 'name A', 'email B' => 'name B']
	 * @param array 	$cc			carbon copy receipient, format ['email A' => 'name A', 'email B' => name B']
	 * @param array 	$bcc		bind carbon copy receipient, format ['email A' => 'name A', 'email B' => 'name B']
	 * @param string 	$subject	title of email
	 * @param string 	$message	message body
	 * @param array 	$attachment	physical file attachment, format ['file name A' => 'absolute file path A', 'file name B' => 'absolute file path B']
	 */
	public function addQueue(
		Array $to, 
		Array $cc, 
		Array $bcc, 
		$subject, 
		$message, 
		Array $attachment);
	
	/**
	 * Run first awaiting email queue and discard it once successfully sent
	 * @return boolean	sent email result
	 */
	public function runQueue();
}
?>