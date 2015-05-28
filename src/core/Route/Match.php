<?php 
namespace Hx\Route;

class Match implements MatchInterface {
	private $className, $functionName, $isStaticCall, $args;

	
	public function __construct(InfoInterface $info, Array $arguments)
	{
		$this->className = $info->getClassName();
		
		$this->functionName = $info->getFunctionName();
		
		$this->isStaticCall = $info->isStaticCall();
		
		$this->args = $arguments;
	}
	
	public function getClassName()
	{
		return $this->className;
	}
	
	public function getFunctionName()
	{
		return $this->functionName;
	}
	
	public function isStaticCall()
	{
		return $this->isStaticCall;
	}
	
	public function getArgs()
	{
		return $this->args;
	}
}
?>