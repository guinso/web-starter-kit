<?php 
/********************** library and settings **********/
$dir = dirname(dirname(dirname(__FILE__)));
include $dir . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'loader.php';

/********************** Modules ***********************/

//automatic load all module files
Util::recursiveDir(
	ADM_API_DIR . '/modules',
	function($filePath){
		include_once $filePath;
	}, 
	'/^.+\.php$/');

//place API definition here
require_once ADM_API_DIR . '/api.php';

function home() {
	echo 'Web Admin.<br/>';
	echo Util::getDate() . "<br/>";
}

/********************* Execute ***********************/

getRoute()->get('/', 'home');
getRoute()->run();
?>
