<?php 
//load minimum setting file
define('ROOT_DIR', dirname(__FILE__));
require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 
			'libs' . DIRECTORY_SEPARATOR . 'ig' . DIRECTORY_SEPARATOR . 'igConfig.php';

require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config.php';

//show 503 if mantenance mode is on
if(IgConfig::getConfig('maintenance')) {
?>
	Site on maintence. Please try later
<?php
	die();
}
?>