<?php 
namespace Hx\Soap\Client;

/**
 * SOAP adaptor interface class
 * @author chingchetsiang
 *
 */
interface AdaptorInterface {
	
	/**
	 * Wrapper to invoke SOAP function call
	 * @param String $functionName
	 * @param Array $parameters
	 */
	public function sent($functionName, Array $parameters);
}
?>