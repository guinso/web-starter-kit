<?php 
define('URL', dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']))));
define('ADM_URL', URL . '/adm');

define('DS' , DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(dirname(__FILE__)));

define('API_DIR', dirname(__FILE__));
define('ADM_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'adm');
define('ADM_API_DIR', ADM_DIR . DIRECTORY_SEPARATOR . 'api');

session_start();

/******************** Library *************************/
include_once API_DIR . DS . 'libs'. DS . 'notorm' . DS . 'NotORM.php';
include_once API_DIR . DS . 'libs' . DS . 'epiphany' . DS . 'Epi.php';

require_once API_DIR . DS . 'libs' . DS . 'phpexcel' . DS . 'PHPExcel.php';
require_once API_DIR . DS . 'libs' . DS . 'phpexcel' . DS . 'PHPExcel' . DS . 'IOFactory.php';
require_once API_DIR . DS . 'libs' . DS . 'phpmailer' . DS . 'PHPMailerAutoload.php';
//x require_once API_DIR . '/libs/guzzle/autoloader.php';

require_once API_DIR . DS . 'libs' . DS . 'jshrink' . DS . 'Minifier.php';

require_once API_DIR . DS . 'libs' . DS . 'ig' . DS . 'autoloader.php';

Epi::setPath('base', API_DIR . DS . 'libs' . DS . 'epiphany');
Epi::init('api');
Epi::setSetting('exceptions', true);

/**************** SPL-0 autoloading *********************/

function myAutoload($className)
{
	$filePath = API_DIR. DS .'libs'.DS .
	str_replace('\\', DS, $className) . '.php';

	if(is_readable($filePath)) {
		require_once $filePath;
	}
}

spl_autoload_register('myAutoload', true, true);


/*************** CORE AND CONFIGURATION ****************/
IgConfigLoader::configure(API_DIR . DS . 'config.php');
$setting = IgConfig::getProfile();

Util::configure(
$setting->dsm, $setting->dbUsr, $setting->dbPwd,
$setting->dbInitial, $setting->dbLen,
$setting->absUploadPath, $setting->absTemporaryPath, $setting->absTemplatePath);

EmailUtil::configure(
$setting->smtpHost,
$setting->smtpEmail, $setting->smtpName,
$setting->smtpUsr, $setting->smtpPwd,
$setting->smtpSecure, $setting->smtpPort);

if(IgConfig::getConfig('debugEmail')) {
	EmailUtil::setDebug(true, IgConfig::getConfig('debugEmailAddress'));
}

FileUtil::configure($setting->absUploadPath);

date_default_timezone_set($setting->timeZone);
?>