<?php 
namespace Hx\IocContainer;

interface RuleLoaderInterface {
	
	public function getRules();
	
	/**
	 * Get project root directory
	 */
	public function getRootDir();
	
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