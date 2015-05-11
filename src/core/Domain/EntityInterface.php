<?php 
namespace Hx\Domain;

interface Entity {
	public function get(Array $param, Array $option);
	
	public function register(Array $param, Array $option);
	
	public function revise(Array $param, Array $option);
	
	public function remove(Array $param, Array $option);
	
	private function _getFormat($dbRow, Array $option);
}
?>