<?php 
namespace Hx\Route;

use Hx\IocContainer\RuleInterface;
class Mapper implements \Hx\Route\MapperInterface {
	
	private $lut;
	
	public function __construct(Array $mapTable)
	{
		foreach ($mapTable as $key => $map)
		{
			if (!($map instanceof InfoInterface))
				Throw new \Hx\Exception\RouteException(
					"Map table index of $key is not " .
					"type of \\Hx\\Route\\InfoInterface");
		}
		
		$this->lut = $mapTable;
	}
	
	public function find($requestUri, $method)
	{
		return $this->_loopSearch(
			$this->lut, 
			$method, 
			$requestUri
		);
	}
	
	private function _loopSearch(Array $table, $method, $requestUri)
	{
		if(COUNT($table) > 0)
		{
			if (!(reset($table) instanceof \Hx\Route\InfoInterface))
			{
				Throw new \Hx\Exception\RouteException(
					"Mapper table's element is not type of \\Hx\\Route\\InfoInterface");
			}
			else if ($this->_isMatch(reset($table), $method, $requestUri))
			{
				preg_match('*^' . reset($table)->getUri() . '$*', $requestUri, $args);
			
				return new \Hx\Route\Match(reset($table), $args);
			}
			else 
			{
				array_shift($table);
				
				return $this->_loopSearch($table, $method, $requestUri);
			}
		}
		else 
		{
			Throw new  \Hx\Exception\RouteException(
				"No matching candidate for method <$method>, uri <$requestUri>");
		}
	}
	
	private function _isMatch(InfoInterface $info, $method, $uri)
	{
		return 
			$info->getReqMethod() == $method && 
			preg_match(
				'*^' . $info->getUri() . '$*', 
				$uri) === 1;
	}
}
?>