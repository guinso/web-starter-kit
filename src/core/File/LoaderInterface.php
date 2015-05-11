<?php 
namespace Hx\File;

interface LoaderInterface {
	
	/**
	 * Load file from file path
	 * @param string	$filePath
	 * @param array 	$option
	 */
	public function load($filePath, Array $option);
}
?>