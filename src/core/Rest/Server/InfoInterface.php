<?php 
namespace Hx\Rest\Server;

interface InfoInterface {
	
	/**
	 * Get URL path
	 */
	public function getUrl();
	
	/**
	 * Get http request method name
	 */
	public function getMethod();
	
	/**
	 * Get wildcard variables found in URL path
	 * NOTE: not url parameter, but wildcard in URL path
	 * @return array of string, empty array return if no wildcard found
	 */
	public function getUrlVals();
}
?>