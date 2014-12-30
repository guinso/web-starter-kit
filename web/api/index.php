<?php 
define('URL', dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']))));
define('ROOT_DIR', dirname(__FILE__));
define('MAIN_ROOT', dirname(ROOT_DIR));

session_start();

/******************** Library *************************/
include_once ROOT_DIR . '/libs/notorm/NotORM.php';
include_once ROOT_DIR . '/libs/epiphany/Epi.php';

require_once ROOT_DIR . '/libs/phpexcel/PHPExcel.php';
require_once ROOT_DIR . '/libs/phpexcel/PHPExcel/IOFactory.php';
require_once ROOT_DIR . '/libs/phpmailer/PHPMailerAutoload.php';
//x require_once ROOT_DIR . '/libs/guzzle/autoloader.php';

require_once ROOT_DIR . '/libs/jshrink/Minifier.php';

require_once ROOT_DIR . '/libs/ig/autoloader.php';

Epi::setPath('base', ROOT_DIR . '/libs/epiphany');
Epi::init('api');
Epi::setSetting('exceptions', true);

/**************** SPL-0 autoloading *********************/

function myAutoload($className)
{
	$filePath = ROOT_DIR.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR .
	str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

	if(is_readable($filePath)) {
		require_once $filePath;
	}
}

spl_autoload_register('myAutoload', true, true);


/*************** CORE AND CONFIGURATION ****************/
IgConfigLoader::configure(ROOT_DIR . DIRECTORY_SEPARATOR . 'config.php');
$setting = IgConfig::getProfile();

Util::configure(
	$setting->dsm, $setting->dbUsr, $setting->dbPwd, 
	$setting->dbInitial, $setting->dbLen, 
	$setting->uploadPath, $setting->temporaryPath, $setting->templatePath);

EmailUtil::configure(
	$setting->smtpHost,
	$setting->smtpEmail, $setting->smtpName,
	$setting->smtpUsr, $setting->smtpPwd,
	$setting->smtpSecure, $setting->smtpPort);

FileUtil::configure($setting->uploadPath);

date_default_timezone_set($setting->timeZone);

/********************** Modules ***********************/

//automatic load all module files
Util::recursiveDir(
		ROOT_DIR . '/modules',
		function($filePath){
	include_once $filePath;
},
'/^.+\.php$/');

//place API definition here
require_once ROOT_DIR . '/api.php';

function home() {
	echo 'Web App.<br/>';
	echo Util::getDate() . "<br/>";
}

/********************* Execute ***********************/

//Ping current login user to keep alive
LoginUtil::ping();

getRoute()->get('/', 'home');
getRoute()->run();
?>