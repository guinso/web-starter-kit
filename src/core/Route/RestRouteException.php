<?php 
namespace Hx\Route;

class RestRouteException extends \Hx\Exception\RouteException
{
	public function __construct($errorType, $message, $code, $previous)
	{
		$this->errorType = $errorType;
		
		parent::__construct($message, $code, $previous);
	}
}
?>