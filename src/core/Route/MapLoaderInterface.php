<?php 
namespace Hx\Route;

/**
 * Router mapping loader tools
 * @author chingchetsiang
 *
 */
interface MapLoaderInterface {
	
	/**
	 * Load from file
	 * @param string $filePath
	 */
	public function loadFile($filePath);
	
	/**
	 * Load from all XML file under targeted directory
	 * @param string $directory
	*/
	public function loadDir($directory);
	
	/**
	 * Load from in memory string
	 * @param string $content
	*/
	public function loadString($content);
}
?>