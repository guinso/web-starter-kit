<?php 
namespace Hx\Route;

class RestRouterException extends \RuntimeException
{
	private $errorType;
	
	const INPUT_ERROR = 1;
	const MSG_ERROR = 2;
	const DOMAIN_ERROR = 4;
	const OUTPUT_ERROR = 8;
	
	public function __construct($errorType, $message, $code, $previous)
	{
		$this->errorType = $errorType;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getErrorType()
	{
		return $this->errorType;
	}
}
?>