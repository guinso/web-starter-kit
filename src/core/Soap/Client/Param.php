<?php 
namespace Hx\Soap\Client;

/**
 * SOAP parameter definition
 * @author chingchetsiang
 *
 */
class Param implements ParamInterface {
	private $name;
	
	private $value;
	
	private $configure;
	
	public function __construct($name, $value, $config = null)
	{
		$this->name = $name;
		
		$this->value = $value;
		
		$this->configure = $config;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;	
	}

	public function getConfig()
	{
		return $this->configure;
	}
}
?>
