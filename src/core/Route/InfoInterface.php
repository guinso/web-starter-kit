<?php 
namespace Hx\Route;

interface InfoInterface {
	
	/**
	 * Get URI patern
	 */
	public function getUri();
	
	/**
	 * Get http request method name
	 */
	public function getReqMethod();
	
	/**
	 * Determine calling method in static or in Object instance way
	 */
	public function isStaticCall();
	
	/**
	 * Get Targetted Class name
	 */
	public function getClassName();
	
	/**
	 * Get targetted Function name
	 */
	public function getFunctionName();
	
	/**
	 * Get output format
	 */
	public function getOutputFormat();
}
?>