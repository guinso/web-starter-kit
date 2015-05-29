<?php 
namespace Hx\Http;

class InputService implements InputServiceInterface {
	private $plugins, $classParser, $fileService, $headerReader, $iocContainer;
	
	public function __construct(
		\Hx\Http\HeaderReaderInterface $headerReader,
		\Hx\Parser\ClassParserInterface $classParser, 
		\Hx\File\FileInterface $fileService,
		\Hx\IocContainer\IocContainerInterface $iocContainer,
		$loadDefaultPlugins = true)
	{
		$this->headerReader = $headerReader;	
		
		$this->plugins = array();
		
		$this->classParser = $classParser;
		
		$this->fileService = $fileService;
		
		$this->iocContainer = $iocContainer;
		
		if($loadDefaultPlugins == true)
			$this->registerDefaultPlugin();
	}
	
	public function getInput()
	{
		return $this
			->getPlugin($this->headerReader->getContentType())
			->getInput($this->headerReader->getMethod());
	}
	
	public function registerPlugin(InputInterface $plugin)
	{
		if(array_key_exists($plugin->getContentType(), $this->plugins))
		{
			Throw new \Hx\Exception\HttpException(
				"Plugin for {$plugin->getContentType()} already registered.");
		}
		else 
		{
			$this->plugins[$plugin->getContentType()] = $plugin;
		}
	}
	
	public function removePlugin($contentType)
	{
		if(array_key_exists($contentType, $this->plugins))
			unset($this->plugins[$contentType]);
		else
			Throw new \Hx\Exception\HttpException(
				"No available InputHandler plugin for <$contentType> found.");
	}
	
	public function getPlugin($contentType)
	{
		//use url param (GET) if no cotnent type is specified
		if(empty($contentType))
			$contentType = 'urlparam';
		
		if(array_key_exists($contentType, $this->plugins))
			return $this->plugins[$contentType];
		else 
			Throw new \Hx\Exception\HttpException(
				"No available InputHandler plugin for <$contentType> found.");
	}
	
	private function registerDefaultPlugin()
	{
		$this->fileService->recursiveDir(
			__DIR__ . DIRECTORY_SEPARATOR . 'Input', 
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
							
							//var_dump($reflection->getInterfaceNames());
							//echo '<br/>';
							
							if (in_array(
								"Hx\\Http\\InputInterface",
								$reflection->getInterfaceNames()))
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