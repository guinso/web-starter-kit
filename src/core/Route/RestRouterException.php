<?php 
namespace Hx\Route;

class RestRouterException extends RouteException
{
	public function __construct($errorType, $message, $code, $previous)
	{
		$this->errorType = $errorType;
		
		parent::__construct($message, $code, $previous);
	}
}
?>