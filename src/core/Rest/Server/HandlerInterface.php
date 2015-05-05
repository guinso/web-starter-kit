<?php 
namespace Hx\Rest\Server;

interface HandlerInterface {

	public function handleRequest();
	
	public function getRequestUrl();
	
	public function getRequestMethod();
}
?>