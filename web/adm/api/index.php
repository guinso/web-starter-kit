<?php 
define('URL', dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']))));
define('ROOT_DIR', dirname(__FILE__));

/******************* LOAD PROFILES ********************/
include_once ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'IgConfig.php';
include_once ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'myConfig.php';

session_start();

/******************** Library *************************/
include_once WEB_API_DIR . '/libs/notorm/NotORM.php';
include_once WEB_API_DIR . '/libs/epiphany/Epi.php';

require_once WEB_API_DIR . '/libs/phpexcel/PHPExcel.php';
require_once WEB_API_DIR . '/libs/phpexcel/PHPExcel/IOFactory.php';
require_once WEB_API_DIR . '/libs/phpmailer/PHPMailerAutoload.php';
require_once WEB_API_DIR . '/libs/guzzle/autoloader.php';

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
$setting = IgConfig::get('adm');

Util::configure(
	$setting['dsm'], $setting['dbUsr'], $setting['dbPwd'], 
	$setting['dbInitial'], $setting['dbLen'], 
	$setting['uploadPath'], $setting['temporaryPath'], $setting['templatePath']);

EmailUtil::configure(
	$setting['smtpHost'], 
	$setting['smtpEmail'], $setting['smtpName'], 
	$setting['smtpUsr'], $setting['smtpPwd'], 
	$setting['smtpSecure'], $setting['smtpPort']);

FileUtil::configure($setting['uploadPath']);

date_default_timezone_set($setting['timeZone']);

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
	echo 'Starter Kit Web Admin.<br/>';
	echo Util::getDate() . "<br/>";
}

/********************* Execute ***********************/

//Ping current login user to keep alive
LoginUtil::ping();

getRoute()->get('/', 'home');
getRoute()->run();
?>
