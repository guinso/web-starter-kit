<?php 
namespace Hx\Rest\Server;

class Handler implements HandlerInterface {
	private $mapper;
	
	public function __construct(MapperInterface $mapper)
	{
		$this->mapper = $mapper;
	}
	
	public function handleRequest()
	{
		
	}
	
	public function getRequestUrl()
	{
		
	}
	
	public function getRequestMethod()
	{
		
	}
	
	private function _sanitizeInput()
	{
		
	}
}
?>