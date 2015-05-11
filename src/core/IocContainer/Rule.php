<?php 
namespace Hx\IocContainer;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
class Rule implements RuleInterface {
	
	private $className, $instanceClassName, $isService, $args, $rules, $func;
	
	private $serviceInstance;
	
	public function __construct(
		$className, 
		$instanceClassName, 
		$isService, 
		Array $args = null, 
		Array $rules = null, 
		\Closure $closure = null)
	{
		$this->className = $className;
		
		$this->isService = $isService;
		
		$this->args = $args;
		
		if(isset($closure))
		{
			$this->instanceClassName = null;
			
			$this->func = $closure;
		}
		else 
		{
			if (empty($instanceClassName))
				Throw new \InvalidArgumentException(
					"Cannot pass empty value on instanceClassName parameter.");
			else 
			{
				$this->instanceClassName = $instanceClassName;
					
				$this->func = null;
			}
			
		}

		if (!empty($rules)) {
			foreach($rules as $k => $r)
			{
				if (! ($r instanceof RuleInterface))
					Throw new InvalidArgumentException(
						"Fail to initiate Rule instance, rules index of " . 
						"$k is not type of \Hx\IocContainer\RuleInterface.");
			}
			
			$this->rules = $rules;
		}
		else 
		{
			$this->rules = array();
		}
	}
	
	public function __clone()
	{
		if (!empty($this->func))
			$this->func = clone $this->func;
		
		if (!empty($this->rules))
		{
			$this->rules = array();
			
			foreach($this->rules as $k => $rule)
			{
				$this->rules[$k] = clone $rule;
			}
		}
	}
	
	public function getClassName()
	{
		return $this->className;
	}
	
	public function getInstanceClassName()
	{
		return $this->instanceClassName;
	}
	
	public function getClosure()
	{
		return $this->func;
	}
	
	public function isCodeOverride()
	{
		return isset($this->func);
	}
	
	public function isService()
	{
		return $this->isService;
	}
	
	public function getPrimitiveArgs()
	{
		return $this->args;
	}
	
	public function getSubRules()
	{
		return $this->rules;
	}
	
	public function getServiceInstance()
	{
		return $this->serviceInstance;
	}
	
	public function setServiceInstance($object)
	{
		if (empty($this->serviceInstance))
			$this->serviceInstance = $object;
		else if(!$this->isService)
			Throw new \RuntimeException(
				"Rule {$this->className} is not service.");
		else
			$this->serviceInstance = $object;
	}
}
?>