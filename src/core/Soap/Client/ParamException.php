<?php 
namespace Hx\Soap\Client;

class ParamException extends \Exception {
	protected $paramName;
	
	public function __construct($paramName, $message, $code = 0, Exception $previous = null) {
	
		$this->paramName = $paramName;
	
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Get parameter name
	 */
	public function getParamName() {
		return $this->paramName;
	}
}
?>