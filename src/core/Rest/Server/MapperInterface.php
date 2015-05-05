<?php 
namespace Hx\Rest\Server;

interface MapperInterface {
	
	/**
	 * Load Rest API mapper recipe, prefer XML file type
	 * @param string $filePath
	 * @param array $option
	 */
	public function loadXml($filePath, Array $option);
	
	/**
	 * Search matching Rest Api record
	 * @param string $requestUrl
	 * @param string $method
	 * @return \Hx\Rest\Server\InfoInterface
	 */
	public function getApi($requestUrl, $method);
}
?>