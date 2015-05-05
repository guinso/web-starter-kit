<?php 
//uncomment this to observe error message
//x error_reporting(E_ALL);
//x ini_set( 'display_errors','1');

/********************** library and settings **********/
$dir = dirname(dirname(dirname(dirname(__FILE__))));
include $dir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';

/********************** Modules ***********************/

//automatic load all module files
\Ig\Util::recursiveDir(
	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules',
	function($filePath){
		include_once $filePath;
	}, 
	'/^.+\.php$/');

//place API definition here
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api.php';

function home() {
	echo 'Web Admin.<br/>';
	echo  \Ig\Date::getDate() . "<br/>";
}

/********************* Execute ***********************/

getRoute()->get('/', 'home');
getRoute()->run();
?>
