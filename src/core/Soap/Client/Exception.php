<?php 
namespace Hx\Soap\Client;

class Exception extends \Exception {
	protected $functionName;
	
	public function __construct($functionName, $message, $code = 0, Exception $previous = null) {
	
		$this->functionName = $functionName;
	
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Get SOAP request fucntion name
	 */
	public function getFunctionName() {
		return $this->functionName;
	}
}
?>