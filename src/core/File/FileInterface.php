<?php 
namespace Hx\File;

interface FileInterface {
	public function getDirectories($directory);
	
	public function deleteFile($filePath);
	
	/**
	 * Recursive a directory and excecute anonymous function once file name matched regular expression
	 * @param String 	$baseDir		start point of directory user wish to travase
	 * @param Closure 	$closure		anonymous function provided by user, function($filePath)...
	 * @param String 	$filePattern	PEARL style regular expression expression
	 * @param Array 	$ignoreDir		directory which wanna ignore from the search
	 */
	public function recursiveDir(
			$baseDir,
			$closure,
			$filePattern = '/.+/',
			$ignoreDir = array('.svn', '.', '..')
	);
}
?>