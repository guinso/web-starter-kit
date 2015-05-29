<?php 
namespace Hx\Route;

class Match implements MatchInterface {
	private $className, $functionName, $isStaticCall, $args, $outputFormat;

	
	public function __construct(InfoInterface $info, Array $arguments)
	{
		$this->className = $info->getClassName();
		
		$this->functionName = $info->getFunctionName();
		
		$this->outputFormat = $info->getOutputFormat();
		
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
	
	public function getOutputFormat()
	{
		return $this->outputFormat;
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