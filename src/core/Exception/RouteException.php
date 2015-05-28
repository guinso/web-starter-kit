<?php 
namespace Hx\Exception;

class RouteException extends \RuntimeException {
	private $errorType;
	
	const INPUT_ERROR = 1;
	const MSG_ERROR = 2;
	const DOMAIN_ERROR = 4;
	
	public function getErrorType()
	{
		return $this->errorType;
	}
}
?>