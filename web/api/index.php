<?php 
//uncomment this to observe error message
//x error_reporting(E_ALL);
//x ini_set( 'display_errors','1');

/********************* Load libraries and Settings ******************/
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'loader.php';

/********************** Load Modules ********************************/

//load all module files
Util::recursiveDir(
		API_DIR . '/modules',
		function($filePath){
	include_once $filePath;
},
'/^.+\.php$/');

//load REST API
require_once API_DIR . '/api.php';

function home() {
	echo 'Web App.<br/>';
	echo Util::getDate() . "<br/>";
	
	$g = new Parsedown();
	$f = file_get_contents(ROOT_DIR . DS . 'log.md');
	echo $g->text($f);
}

/********************* Execute *************************************/

//disable service if maintenance is on
if(\Ig\Config::getConfig('maintenance'))
	Util::sendErrorResponse(0, "Site under maintenance", null, 503);

$profile = \Ig\Config::getProfile();

//Ping current login user to keep alive
\Ig\Login::ping();

getRoute()->get('/', 'home');
getRoute()->run();
?>