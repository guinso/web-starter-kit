<?php 
//configure libs, setting
define('URL', dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']))));
define('ROOT_DIR', dirname(__FILE__));
define('MAIN_ROOT', dirname(ROOT_DIR));
define('CONFIG_DIR', MAIN_ROOT . DIRECTORY_SEPARATOR . 'adm' .
		DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'config');

/******************* LOAD PROFILES ********************/
include_once CONFIG_DIR . DIRECTORY_SEPARATOR . 'IgConfig.php';
include_once CONFIG_DIR . DIRECTORY_SEPARATOR . 'myConfig.php';

session_start();

/******************** Library *************************/
include_once ROOT_DIR . '/libs/notorm/NotORM.php';
include_once ROOT_DIR . '/libs/epiphany/Epi.php';

require_once ROOT_DIR . '/libs/phpexcel/PHPExcel.php';
require_once ROOT_DIR . '/libs/phpexcel/PHPExcel/IOFactory.php';
require_once ROOT_DIR . '/libs/phpmailer/PHPMailerAutoload.php';
//x require_once ROOT_DIR . '/libs/guzzle/autoloader.php';

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
$setting = IgConfig::get('web');

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

//run schedule
ScheduleUtil::run();
?>