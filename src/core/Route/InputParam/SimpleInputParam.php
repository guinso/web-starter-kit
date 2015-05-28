<?php 
namespace Hx\Route\InputParam;

class SimpleInputParam implements \Hx\Route\InputParamInterface {
	
	private $data, $args;
	
	public function __construct(Array $data, Array $args)
	{
		$this->data = $data;
		
		$this->args = $args;
	}
	
	/**
	 * Get array value
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Get indexed based array
	 */
	public function getArgs()
	{
		return $this->args;
	}
}
?>