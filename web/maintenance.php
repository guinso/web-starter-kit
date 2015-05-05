<?php 
//load minimum setting file

require_once dirname(dirname(__FILE__)) . 
	DIRECTORY_SEPARATOR . 'vendor' . 
	DIRECTORY_SEPARATOR . 'Ig' . 
	DIRECTORY_SEPARATOR . 'autoloader.php';

//show 503 if mantenance mode is on
if(\Ig\Config::getConfig('maintenance')) {
?>
	Site on maintence. Please try later
<?php
	die();
}
?>