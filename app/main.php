<?php 
//uncomment this to observe error message
//x error_reporting(E_ALL);
//x ini_set( 'display_errors','1');

/********************* Load libraries and Settings ******************/
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/********************** Load Modules ********************************/


//load all module files
\Ig\Util::recursiveDir(
	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules',
	function($filePath){
		include_once $filePath;
	},
	'/^.+\.php$/'
);

//load REST API
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api.php';

function home() {
	echo 'Web App.<br/>';
	echo  \Ig\Date::getDate() . "<br/>";

	$g = new Parsedown();
	$f = file_get_contents(\Ig\Config\Loader::getRootPath() . DIRECTORY_SEPARATOR . 'log.md');
	echo $g->text($f);
}

/********************* Execute *************************************/

//disable service if maintenance is on
if(\Ig\Config::getConfig('maintenance'))
	\Ig\Web::sendErrorResponse(0, "Site under maintenance", null, 503);

//Ping current login user to keep alive
\Ig\Login::ping();

getRoute()->get('/', 'home');
getRoute()->run();
?>