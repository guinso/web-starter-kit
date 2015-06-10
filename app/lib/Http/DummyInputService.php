<?php 
namespace Starter\Http;

class DummyInputService implements \Hx\Http\InputServiceInterface {
	
	public function __construct()
	{
		
	}
	
	public function getInput()
	{
		return array();
	}
	
	public function registerPlugin(\Hx\Http\InputInterface $plugin)
	{
		//do nothing	
	}
	
	public function removePlugin($contentType)
	{
		//do nothing
	}
	
	public function getPlugin($contentType)
	{
		return null;
	}
}
?>