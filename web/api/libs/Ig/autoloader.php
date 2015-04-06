<?php 
$igDir = dirname(__FILE__);

require_once $igDir . DIRECTORY_SEPARATOR . 'util.php';

require_once $igDir . DIRECTORY_SEPARATOR . 'loginUtil.php';
require_once $igDir . DIRECTORY_SEPARATOR . 'authorizeUtil.php';
require_once $igDir . DIRECTORY_SEPARATOR . 'fileUtil.php';

require_once $igDir . DIRECTORY_SEPARATOR . 'igConfig.php';
require_once $igDir . DIRECTORY_SEPARATOR . 'igConfigLoader.php';


//implement PSR-4 autoloading
function IgAutoload($className)
{
	$baseDir = dirname(dirname(__FILE__));
	
	$filePath = $baseDir . DIRECTORY_SEPARATOR .
	str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	
	if(is_readable($filePath)) {
		require_once $filePath;
	}
}
spl_autoload_register('IgAutoload', true, true);
?>