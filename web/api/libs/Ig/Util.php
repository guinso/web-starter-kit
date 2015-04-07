<?php
namespace Ig;

class Util {

	/**
	 * Recursive a directory and excecute anonymous function once file name matched regular expression
	 * @param String $baseDir	start point of directory user wish to travase
	 * @param Closure $closure	anonymous function provided by user, function($filePath)...
	 * @param String $filePattern	PEARL style regular expression expression
	 * @param Array $ignoreDir	directory which wanna ignore from the search
	 */
	public static function recursiveDir(
			$baseDir,
			$closure,
			$filePattern = '/.+/',
			$ignoreDir = array('.svn', '.', '..')
	) {
	
		$dirHandle = opendir($baseDir);
		$keepLoop = true;
	
		while ($keepLoop) {
	
			$file = readdir($dirHandle);
	
			$isIgnoreDir = false;
			foreach ($ignoreDir as $igr) {
				if ($file == $igr) {
					$isIgnoreDir = true;
				}
			}
	
			if (!$isIgnoreDir && $file) {
				$tmpDir = $baseDir . '/' . $file;
	
				if (is_dir($tmpDir . '/')) {
					self::recursiveDir($tmpDir, $closure, $filePattern, $ignoreDir);
				} else {
					//check file path's pattern via regular expression
					if(preg_match($filePattern, $file) == 1) {
						$closure($tmpDir);
					}
				}
			}
	
			$keepLoop = !empty($file);
		}
	}
	
	/**
	 * Get server host name with support reverse proxy request as well
	 * @return string
	 */
	public static function getServerHostname() 
	{
		$hostname = '';
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			$hostname = $_SERVER['HTTP_X_FORWARDED_HOST'];
			
		} elseif (isset($_SERVER['SERVER_HOST'])) {
			$hostname = $_SERVER['SERVER_HOST'];
			
		} else {
			$hostname = $_SERVER['HTTP_HOST'];
		}
		
		if (!empty($_SERVER['REQUEST_SCHEME'])) {
			$protocal = $_SERVER['REQUEST_SCHEME'];
			
		} elseif (isset($_SERVER['HTTPS'])) {
			$protocal = 'https:';
			
		} else {// bo idea liao
			$protocal = 'http';
		}
		
		return $protocal .  '://' . $hostname;
	}
	
	/**
	 * Get server full URL with support reverse proxy request as well
	 * @return string
	 */
	public static function getServerUrl() 
	{
		$uri = dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT'])));
	
		return self::getServerHostname() . $uri;
	}
	
	/**
	 * Check is associative array or not
	 * @param unknown $array
	 * @return boolean
	 */
	function isAssociatoveArray($array) 
	{
		//skip this step to fastern process but resource wise, is expensive
		$array = array_keys($array); 
		
		return ($array !== array_keys($array));
	}
}
?>