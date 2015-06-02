<?php 
namespace Hx\Http;

class OutputService implements OutputServiceInterface {
	private $plugins, $classParser, $fileService, $iocContainer;
	
	public function __construct(
		\Hx\Parser\ClassParserInterface $classParser, 
		\Hx\File\FileInterface $fileService,
		\Hx\IocContainer\IocContainerInterface $iocContainer,
		$loadDefaultPlugins = true)
	{
		$this->plugins = array();
		
		$this->classParser = $classParser;
		
		$this->fileService = $fileService;
		
		$this->iocContainer = $iocContainer;
		
		if($loadDefaultPlugins == true)
			$this->registerDefaultPlugin();
	}
	
	public function generateOutput($outputFormat, array $data = null)
	{
		return $this
			->getPlugin($outputFormat)
			->generateOutput(200, $data);
	}
	
	public function registerPlugin(OutputInterface $plugin)
	{
		if(array_key_exists($plugin->getFormatType(), $this->plugins))
		{
			Throw new \Hx\Http\HttpException(
				"Plugin for {$plugin->getFormatType()} already registered.");
		}
		else 
		{
			$this->plugins[$plugin->getFormatType()] = $plugin;
		}
	}
	
	public function removePlugin($contentType)
	{
		if(array_key_exists($contentType, $this->plugins))
			unset($this->plugins[$contentType]);
		else
			Throw new \Hx\Http\HttpException(
				"No available InputHandler plugin for <$contentType> found.");
	}
	
	public function getPlugin($formatType)
	{
		//use url param (GET) if no cotnent type is specified
		if(empty($formatType))
			$formatType = 'text';
		
		if(array_key_exists($formatType, $this->plugins))
			return $this->plugins[$formatType];
		else 
			Throw new \Hx\Http\HttpException(
				"No available OutputHandler plugin for <$formatType> found.");
	}
	
	private function registerDefaultPlugin()
	{
		$this->fileService->recursiveDir(
			__DIR__ . DIRECTORY_SEPARATOR . 'Output', 
			function($filePath)
			{
				$namespace = $this->classParser->load($filePath);
				
				foreach ($namespace as $namespaceKey => $ns)
				{
					foreach ($ns as $className => $classType)
					{
						if ($classType == 'class')
						{
							$reflection = new \ReflectionClass($namespaceKey . '\\' . $className);
							
							if (in_array(
								"Hx\\Http\\OutputInterface",
								$reflection->getInterfaceNames())
							)
								$this->registerPlugin(
									$this->iocContainer->resolve($namespaceKey . '\\' . $className)
								);
						}
					}
				}
			},
			'#^.+\.php$#'
		);
	}
}
?>