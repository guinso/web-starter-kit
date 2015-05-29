<?php 
namespace Hx\Route;

class Info implements InfoInterface {
	private $uri, $method, $isStatic, $className, $functionName, $outputFormat;
	
	public function __construct($uri, $method, $className, $functionName, $outputFormat, $isStatic = false)
	{
		$this->uri = $uri;
		
		$this->method = $method;
		
		$this->className = $className;
		
		$this->functionName = $functionName;
		
		$this->outputFormat = $outputFormat;
		
		$this->isStatic = $isStatic;
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function getReqMethod()
	{
		return $this->method;
	}

	public function isStaticCall()
	{
		return $this->isStatic;
	}
	
	public function getClassName()
	{
		return $this->className;
	}

	public function getFunctionName()
	{
		return $this->functionName;
	}
	
	public function getOutputFormat()
	{
		return $this->outputFormat;
	}
}
?>