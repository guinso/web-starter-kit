<?php 
namespace Hx\IocContainer\Loader;

class XmlRuleLoader implements \Hx\IocContainer\RuleLoaderInterface {
	
	private $lut, $fileService, $rootDir;
	
	public function __construct(\Hx\File\FileInterface $fileService, $rootDir)
	{
		if (!is_dir($rootDir))
			Throw new \RuntimeException("$rootDir is not a valid directory");
		
		$this->rootDir = $rootDir;
		
		$this->fileService = $fileService;
	
		$this->lut = array();
	}
	
	public function getRules()
	{
		return $this->lut;
	}
	
	public function getRootDir()
	{
		return $this->rootDir;
	}
	
	public function loadFile($filePath)
	{
		$this->lut = $this->loadSingleFile($filePath);
	}
	
	public function loadDir($directory)
	{
		$this->lut = array();
	
		$this->fileService->recursiveDir(
			$directory,
			function($filePath)
			{
				$temp = $this->loadSingleFile($filePath);
				
				foreach ($temp as $key => $value)
				{
					if (array_key_exists($key, $this->lut))
						Throw new \Hx\Exception\ParseException(
							"Cannot assign same rule class $key twice." .
							"Source:- $filePath");
				}
				
				$this->lut = array_merge(
					$this->lut,
					$temp
				);
			},
			'/^.+\.xml$/'
		);
	}
	
	private function loadSingleFile($filePath)
	{
		if (!file_exists($filePath))
		{
			Throw new \Hx\Exception\NotAccessibleException(
					"Source file <$filePath> not found.");
		}
		else if (!is_readable($filePath))
		{
			Throw new \Hx\Exception\NotAccessibleException(
					"Source file <$filePath> is not readable");
		}
		else
		{
			return $this->parse(
					file_get_contents($filePath),
					$filePath
			);
		}
	}
	
	public function loadString($content)
	{
		$this->lut = $this->parse($content, 'memory string');
	}
	
	private function parse($content, $filePath)
	{
		$xml = simplexml_load_string($content);
		
		$result = array();
		
		$ruleTags = $xml->xpath('/ioc/rules/rule');
		
		if ($ruleTags === false)
		{
			Throw new \Hx\Exception\ParseException(
				"There is no rule tag found in $filePath");
		}
		else 
		{
			$result = array();
			
			foreach ($ruleTags as $ruleTag)
			{
				$rule = $this->parseRule($ruleTag, $filePath);
				
				if (array_key_exists($rule->getClassName(), $result))
					Throw new \Hx\Exception\ParseException(
						"Cannot assign same rule class {$rule->getClassName()} twice. " .
						"Source:- $filePath");
				else 
					$result[$rule->getClassName()] = $rule;
			}
		}
		
		return $result;
	}
	
	private function parseRule(\SimpleXMLElement $tag, $filePath)
	{
		$this->validateNodeClass($tag, $filePath);
		
		$this->validateNodeClosure($tag, $filePath);
		
		$this->validateNodeService($tag, $filePath);
		
		return new \Hx\IocContainer\Rule(
			'\\' . $tag->class, 
			'\\' . $tag->replace, 
			isset($tag->service)? $this->castBool($tag->service) : false,
			$this->parseArgs($tag, $filePath),
			$this->loopSubRule($tag->xpath('rule'), $filePath),
			$this->parseClosure($tag, $filePath)
		);
	}
	
	private function parseClosure(\SimpleXMLElement $tag, $filePath)
	{
		if (isset($tag->code))
		{
			if($this->castClosure($tag->code) instanceof \Closure)
				return eval('return ' . $tag->code . ';');
			else
				return null;
		}
		else 
		{
			return null;
		}
	}
	
	private function castBool($value)
	{
		if (mb_strtolower($value) == 'true')
			return true;
		else if (mb_strtolower($value) == 'false')
			return true;
		else 
			return false;
	}

	private function loopSubRule(Array $tags, $filePath)
	{
		if (!empty($tags) && COUNT($tags) > 0)
		{
			$rule = $this->parseRule(
					array_shift($tags), 
					$filePath
				);
			
			return array_merge(
				array($rule->getClassName() => $rule),
				$this->loopSubRule($tags, $filePath)		
			);
		}
		else
		{
			return array();
		}
	}
	
	private function parseArgs(\SimpleXMLElement $tag, $filePath)
	{
		if (isset($tag->args))
		{
			$result = array();
			
			$argTags = $tag->xpath('args/arg');
			
			foreach($argTags as $arg)
			{
				$result[] = $arg;
			}
			
			return $result;
		}
		else 
		{
			return array();
		}
	}
	
	private function validateNodeClass(\SimpleXMLElement $tag, $filePath)
	{
		if(!isset($tag->class))
		{
			Throw new \Hx\Exception\ParseException(
				"Node <class> not found. Source:- $filePath");
		}

		try {
			$f = new \ReflectionClass($tag->class);
		}
		catch (\Exception $ex)
		{
			Throw new \Hx\Exception\ParseException(
				"Node <class> {$tag->class} not define! Source:- $filePath");
		}
		
		if($f->isInterface() || $f->isAbstract())
		{
			if (!isset($tag->replace))
			{
				Throw new \Hx\Exception\ParseException(
					"Node <replace> not found. Source:- $filePath");
			}
			else if (!class_exists($tag->replace))
			{
				Throw new \Hx\Exception\ParseException(
					"Node <replace> {$tag->replace} is not a valid class. " . 
					"Source:- $filePath");
			}
		}
	}
	
	private function validateNodeClosure(\SimpleXMLElement $tag, $filePath)
	{
		if (isset($tag->code))
		{
			if (!($this->castClosure($tag->code) instanceof \Closure))
			{
				Throw new \Hx\Exception\ParseException(
					"Node <code> is not a closure. Source:- $filePath, Code:- {$tag->code}");
			}
		}
	}
	
	private function castClosure($code)
	{
		return eval(
			'return ' . 
			mb_ereg_replace(
				'@', 
				$this->rootDir . DIRECTORY_SEPARATOR, 
				$code
			) . 
			';'
		);
	}
	
	private function validateNodeService(\SimpleXMLElement $tag, $filePath)
	{
		if (isset($tag->service))
		{
			if (	!(mb_strtolower($tag->service) == 'true') && 
					!(mb_strtolower($tag->service) == 'false')
				)
				Throw new \Hx\Exception\ParseException(
						"Node <service> is not boolean. Source:- $filePath, Value:- {$tag->service}");
		}
	}
}
?>