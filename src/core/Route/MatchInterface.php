<?php 
namespace Hx\Route;

interface MatchInterface {
	
	public function getClassName();
	
	public function getFunctionName();
	
	public function getOutputFormat();
	
	public function isStaticCall();

	public function getArgs();
}
?>