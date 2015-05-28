<?php 
namespace Hx\Route;

interface MatchInterface {
	
	public function getClassName();
	
	public function getFunctionName();
	
	public function isStaticCall();

	public function getArgs();
}
?>