<?php 
namespace Hx\Parser;

interface ParserInterface {
	
	public function load($sourcePath);
	
	public function loadString($content);
}
?>