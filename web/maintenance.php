<?php 
//load minimum setting file
define('ROOT_DIR', dirname(__FILE__));
define('DS' , DIRECTORY_SEPARATOR);

define('API_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api');
define('ADM_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'adm');
define('ADM_API_DIR', ADM_DIR . DIRECTORY_SEPARATOR . 'api');

require_once API_DIR . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'Ig' . DIRECTORY_SEPARATOR . 'autoloader.php';

//show 503 if mantenance mode is on
if(\Ig\Config::getConfig('maintenance')) {
?>
	Site on maintence. Please try later
<?php
	die();
}
?>