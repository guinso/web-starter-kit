<?php 
define('URL', dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']))));
define('ROOT_DIR', dirname(__FILE__));
define('MAIN_ROOT_DIR', dirname(dirname(dirname(__FILE__))));
define('WEB_API_DIR', MAIN_ROOT_DIR . DIRECTORY_SEPARATOR . 'api');

session_start();

/******************** Library *************************/
include_once WEB_API_DIR . '/libs/notorm/NotORM.php';
include_once WEB_API_DIR . '/libs/epiphany/Epi.php';

require_once WEB_API_DIR . '/libs/phpexcel/PHPExcel.php';
require_once WEB_API_DIR . '/libs/phpexcel/PHPExcel/IOFactory.php';
require_once WEB_API_DIR . '/libs/phpmailer/PHPMailerAutoload.php';
//x require_once WEB_API_DIR . '/libs/guzzle/autoloader.php';
require_once WEB_API_DIR . '/libs/jshrink/Minifier.php';

require_once WEB_API_DIR . '/libs/ig/autoloader.php';

Epi::setPath('base', WEB_API_DIR . '/libs/epiphany');
Epi::init('api');
Epi::setSetting('exceptions', true);

/**************** SPL-0 autoloading *********************/
function myAutoload($className)
{
	$filePath = ROOT_DIR.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR . 
		str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
	
	if(is_readable($filePath)) {
		echo $filePath . '<br/>';
		require_once $filePath;
	}
}

spl_autoload_register('myAutoload', true, true);


/*************** CORE AND SETTINGS ****************/
IgConfigLoader::configure(MAIN_ROOT_DIR . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config.php');
$setting = IgConfig::getProfile();

Util::configure(
	$setting->dsm, $setting->dbUsr, $setting->dbPwd, 
	$setting->dbInitial, $setting->dbLen, 
	$setting->uploadPath, $setting->temporaryPath, $setting->templatePath);

EmailUtil::configure(
	$setting->smtpHost, $setting->smtpEmail, $setting->smtpName, 
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
	echo 'Web Admin.<br/>';
	echo Util::getDate() . "<br/>";
}

function updateConfig() {
	IgConfigLoader::updateSetting();
	echo 'update config done.';
}

/********************* Execute ***********************/

getRoute()->get('/', 'home');
getRoute()->get('/update-config', 'updateConfig');
getRoute()->run();
?>
