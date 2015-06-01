<?php 
namespace Hx\Parser;

/**
 * Read all Php classes available in targeted file
 * @author chingchetsiang
 *
 */
interface ClassParserInterface {
	
	public function load($sourcePath);
	
	public function loadString($content);
}
?>