<?php 
namespace Hx\Http;

class HeaderReader implements \Hx\Http\HeaderReaderInterface {
	
	public function getContentType()
	{
		return  array_key_exists("CONTENT_TYPE", $_SERVER)? 
			$_SERVER["CONTENT_TYPE"] : '';
	}
	
	public function getMethod()
	{
		/*
		if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
				array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))
		{
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE')
			{
				return 'DELETE';
			}
			else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT')
			{
				return 'PUT';
			}
			else
			{
				Throw new \Hx\Exception\RouteException(
					"Http request method <$method> not support.",
					\Hx\Exception\RouteException::METHOD_NOT_SUPPORT);
			}
		}
		else
		{
			return $_SERVER['REQUEST_METHOD'];
		}
		*/
		
		return $_SERVER['REQUEST_METHOD'];
	}
	
	public function getRequestUri()
	{
		//x return $_SERVER['REQUEST_URI']; //full uri path
		
		return empty($_GET['__route__'])? '/' : $_GET['__route__'];
	}
}
?>