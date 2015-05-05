<?php 
namespace Hx\Soap\Client;

/**
 * SOAP parameter definition
 * @author chingchetsiang
 *
 */
interface ParamInterface {
	
	/**
	 * Get parameter name
	 */
	public function getName();
	
	/**
	 * Get parameter value
	 */
	public function getValue();
	
	/**
	 * Get parameter extra configuration
	 * Current support flags:-
	 * Boolean rawXml: enable pass raw XML document as parameter
	 */
	public function getConfig();
}
?>