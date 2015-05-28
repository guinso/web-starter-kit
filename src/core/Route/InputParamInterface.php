<?php 
namespace Hx\Route;

interface InputParamInterface {
	
	/**
	 * Get array value
	 */
	public function getData();
	
	/**
	 * Get indexed based array
	 */
	public function getArgs();
}
?>