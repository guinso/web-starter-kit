<?php 
namespace Hx\Soap\Client;

interface HandlerInterface {
	/**
	 * Sent SOAP request
	 * @param 	String	$functionName	SOAP request function name
	 * @param 	Array	$param			Collections of <\Hx\Soap\Client\ParamInterface> instances
	 * @param 	Array	$options		Options for invoking request, optional
	 * @return	Mix
	 */
	public function sent($functionName, Array $param, Array $options);
}
?>