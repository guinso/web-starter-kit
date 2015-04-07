<?php 
/********************* Load libraries and Settings ******************/
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'loader.php';

/********************** Modules ***********************/

//automatic load all module files
\Ig\Util::recursiveDir(
	API_DIR . '/modules',
	function($filePath){
		include_once $filePath;
	},
	'/^.+\.php$/');

/********************* Execute *************************************/

//disable service if maintenance is on
if(\Ig\Config::getConfig('maintenance'))
	\Ig\Web::sendErrorResponse(0, "Site under maintenance", null, 503);

//run schedule
\Ig\Scheduler::run();
?>