<?php 

//implement PSR-4 autoloading
function PsrAutoload($className)
{
	$xx = explode('\\', $className);
	
	if($xx[0] == 'Psr') {
		$baseDir = dirname(dirname(__FILE__));
		
		$filePath = $baseDir . DIRECTORY_SEPARATOR .
		str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
		
		if(is_readable($filePath)) {
			require_once $filePath;
		}
	}
}
spl_autoload_register('PsrAutoload', true, true);
?>