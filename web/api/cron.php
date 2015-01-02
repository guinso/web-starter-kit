<?php 
/********************* Load libraries and Settings ******************/
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'loader.php';

/********************** Modules ***********************/

//automatic load all module files
Util::recursiveDir(
	API_DIR . '/modules',
	function($filePath){
		include_once $filePath;
	},
	'/^.+\.php$/');

/********************* Execute *************************************/

//disable service if maintenance is on
if(IgConfig::getConfig('maintenance'))
	Util::sendErrorResponse(0, "Site under maintenance", null, 503);

//run schedule
ScheduleUtil::run();
?>