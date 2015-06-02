<?php 
namespace Hx\IocContainer;


class IocContainer implements IocContainerInterface {
	private $rules, $serviceInstances;
	
	public function __construct(Array $rules)
	{
		$this->cloneRules($rules);
		
		$this->serviceInstances = array();
	}
	
	private function cloneRules(Array $rules)
	{
		$temp = array();
		
		$count = COUNT($rules);
		
		foreach ($rules as $key => $rule)
		{
			if (!($rule instanceof \Hx\IocContainer\RuleInterface))
				Throw new \Hx\IocContainer\IocException(
					"Rules index of $key is not " . 
					"type of \\Hx\\IocContainer\\RuleInterface");
			else
			{
				$temp[$key] = clone $rule;
			}
		}
		
		$this->rules = $temp;
	}
	
	public function resolve($className)
	{
		if (!array_key_exists($className, $this->rules))
		{
			Throw new \Hx\IocContainer\IocException("Ioc rule for $className not found");
		}
		else 
		{
			return $this->getInstance($className, $this->rules[$className]);
		}
	}
	
	private function getInstance(
		$className, 
		\Hx\IocContainer\RuleInterface $rule)
	{
		if($rule->isService() && !empty($rule->getServiceInstance())) 
		{
			return $rule->getServiceInstance();
		}
		else 
		{
			if($rule->isCodeOverride())
			{
				$function = $rule->getClosure();
				
				$object = $function($this);
			}
			else 
			{
				$reflection = new \ReflectionClass($rule->getInstanceClassName());
				
				$parameters = $reflection->getConstructor()? 
					$reflection->getConstructor()->getParameters() : 
					array();
				
				$aa = $this->getArguments(
						$parameters,
						$rule,
						$rule->getPrimitiveArgs()
					);
				
				$object = $reflection->newInstanceArgs(
					$aa
				);
			}
			
			if($rule->isService() && empty($rule->getServiceInstance()))
				$rule->setServiceInstance($object);
			
			return $object;
		}
	}
	
	private function getArguments(
		Array $parameters, 
		\Hx\IocContainer\RuleInterface $rule,
		Array $args)
	{
		if(COUNT($parameters) > 0)
		{
			return array_merge(
				[$this->getArgumentValue(
					array_shift($parameters),
					$rule,
					$args
				)], 
				$this->getArguments(
					$parameters, 
					$rule,
					$args				
				)
			);
		}
		else 
		{
			return array();
		}
	}
	
	private function getArgumentValue(
		\ReflectionParameter $reflectionParam, 
		\Hx\IocContainer\RuleInterface $rule,
		Array &$args)
	{
		if (	!empty($reflectionParam->getClass()) && 
				!empty($reflectionParam->getClass()->getName()))
		{
			$className = "\\" . $reflectionParam->getClass()->getName();
			
			if (array_key_exists($className, $rule->getSubRules()))
			{
				/*
				return array(
					'value' => $this->getInstance(
						$className, 
						$rule->getSubRules()[$className]
					),
					'args' => $args
				);*/
				
				return $this->getInstance(
					$className,
					$rule->getSubRules()[$className]
				);
			}
			else if (array_key_exists($className, $this->rules))
			{
				/*
				return array(
					'value' => $this->getInstance(
						$className, 
						$this->rules[$className]
					),
					'args' => $args
				);*/
				
				return $this->getInstance(
					$className, 
					$this->rules[$className]
				);
			}
			else 
			{
				Throw new \Hx\IocContainer\IocException(
					"Ioc rule for $className not found.");
			}
		}
		else if ($reflectionParam->isDefaultValueAvailable())
		{
			/*
			return array(
					'value' => $reflectionParam->getDefaultValue(),
					'args' => $args
			);*/
			
			return $reflectionParam->getDefaultValue();
		}
		else if ($reflectionParam->canBePassedByValue())
		{
			return array_shift($args);
			
			/*
			return array(
					'value' => array_shift($args),
					'args' => $args
			);*/
		}
		else 
		{
			Throw new \Hx\IocContainer\IocException(
				"Ioc contructor argument " . $reflectionParam->getName() . 
				" unable to handled.");;
		}
	}
}
?>