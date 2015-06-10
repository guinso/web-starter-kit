<?php 
namespace Hx\Route\MapLoader;

/**
 * Router mapping loader tools
 * @author chingchetsiang
 *
 */
class XmlMapLoader implements \Hx\Route\MapLoaderInterface {
	
	private $fileService;
	
	public function __construct(\Hx\File\FileInterface $fileService)
	{
		$this->fileService = $fileService;
	}

	public function loadFile($filePath)
	{
		return $this->loadSingleFile($filePath);
	}
	
	public function loadDir($directory)
	{
		$lut = array();
	
		$this->fileService->recursiveDir(
			$directory,
			function($filePath) use (&$lut)
			{
				$lut = array_merge(
					$lut,
					$this->loadSingleFile($filePath)
				);
			},
			'/^.+\.xml$/'
		);
		
		return $lut;
	}
	
	private function loadSingleFile($filePath)
	{
		if (!file_exists($filePath))
		{
			Throw new \Hx\Route\RouteException(
					"Source file <$filePath> not found.");
		}
		else if (!is_readable($filePath))
		{
			Throw new \Hx\Route\RouteException(
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
		return $this->parse($content, 'memory string');
	}

	private function parse($content, $filePath) 
	{
		$xmlObj = simplexml_load_string($content);

		$mapGroup = $xmlObj->xpath('/route/maps');
		
		if ($mapGroup === false)
		{
			Throw new \Hx\Route\RouteException(
				"There is no </route/maps> xpath found. source:- $filePath");
		}
		else 
		{
			$temp = array();
			
			//parse xml into LUT array
			foreach($mapGroup as $maps)
			{
				$mm = $maps->xpath('map');
				
				if ($mm === false)
					Throw new \Hx\Route\RouteException(
						"There is no </route/maps/map> xpath found. source:- $filePath");
				
				foreach($mm as $map)
				{
					if (empty($map->uri))
					{
						Throw new \Hx\Route\RouteException(
								"Node <uri> not found, Source:- $filePath");
					}
					else if (empty($map->method))
					{
						Throw new \Hx\Route\RouteException(
								"Node <method> not found, Source:- $filePath");
					}
					else if (!empty($map->static) &&
							$this->parseBool($map->static) == false &&
							empty($map->class))
					{
						Throw new \Hx\Route\RouteException(
								"Node <class> not found, Source:- $filePath");
					}
					else if (empty($map->function))
					{
						Throw new \Hx\Route\RouteException(
								"Node <function> not found, Source:- $filePath");
					}
					else if (empty($map->outputFormat))
					{
						Throw new \Hx\Route\RouteException(
								"Node <outputFormat> not found, Source:- $filePath");
					}
					else
					{
						$uri = (string) $map->uri;
						$method = (string) mb_strtoupper($map->method);
							
						$temp[$method . '-' .$uri] = new \Hx\Route\Info(
								$uri,
								$method,
								empty($map->class)? '' : (string) $map->class,
								(string) $map->function,
								(string) $map->outputFormat,
								empty($map->static)? false : $this->parseBool($map->static)
						);
					}
				}
			}
			
			return $temp;
		}
		
	}
	
	private function parseBool($value)
	{
		if(mb_strtolower($value) == 'true')
			return true;
		else if (mb_strtolower($value) == 'false')
			return false;
		else
			Throw new \Hx\Route\RouteException("Fail to parse value to boolean: $value");
	}
}
?>