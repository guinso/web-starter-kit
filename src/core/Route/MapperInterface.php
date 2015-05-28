<?php 
namespace Hx\Route;

interface MapperInterface {

	/**
	 * Search matching Rest Api route
	 * @param string $requestUri
	 * @param string $method
	 * @return \Hx\Route\MatchInterface
	 */
	public function find($requestUri, $method);
}
?>