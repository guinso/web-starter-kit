<?php 
$igDir = dirname(__FILE__);

require_once $igDir . DIRECTORY_SEPARATOR . 'util.php';

//implement PSR-4 autoloading
function IgAutoload($className)
{
	$xx = explode('\\', $className);
	
	if($xx[0] == 'Ig') {
		$baseDir = dirname(dirname(__FILE__));
		
		$filePath = $baseDir . DIRECTORY_SEPARATOR .
		str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
		
		if(is_readable($filePath)) {
			require_once $filePath;
		}
	}
}
spl_autoload_register('IgAutoload', true, true);
?>