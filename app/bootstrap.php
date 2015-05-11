<?php 
// Tell PHP that we're using UTF-8 strings until the end of the script
mb_internal_encoding('UTF-8');

// Tell PHP that we'll be outputting UTF-8 to the browser
mb_http_output('UTF-8');

$rootDir = dirname(dirname(__FILE__));

$libDir = $rootDir . DIRECTORY_SEPARATOR . 'vendor';

session_start();



/******************** load Hx Core library *************************/
require_once $rootDir . DIRECTORY_SEPARATOR . 'src' .DIRECTORY_SEPARATOR .
	'core' . DIRECTORY_SEPARATOR . 'autoloader.php';
//x require_once 'phar://' . $rootDir . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'hxCore.phar';



/******************** configure TCPDF ****************/
define('K_TCPDF_EXTERNAL_CONFIG', 1); //to override default setting
define('K_PATH_MAIN', '');
define('K_PATH_URL', '');
define('K_PATH_FONTS', $libDir . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR);
define('K_PATH_CACHE', $rootDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
define('K_PATH_URL_CACHE', $rootDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
define('K_PATH_IMAGES', $rootDir . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
define('K_BLANK_IMAGE', K_PATH_IMAGES . DIRECTORY_SEPARATOR . '_blank.png');
define('PDF_PAGE_FORMAT', 'A4');
define('PDF_PAGE_ORIENTATION', 'P');
define('PDF_CREATOR', 'TCPDF');
define('PDF_AUTHOR', 'TCPDF');
define('PDF_HEADER_TITLE', 'Header');
define('PDF_HEADER_LOGO', K_BLANK_IMAGE);
define('PDF_HEADER_STRING', "-");
define('PDF_HEADER_LOGO_WIDTH', 30);
define('PDF_UNIT', 'mm');
define('PDF_MARGIN_HEADER', 20);
define('PDF_MARGIN_FOOTER', 10);
define('PDF_MARGIN_TOP', 5);
define('PDF_MARGIN_BOTTOM', 25);
define('PDF_MARGIN_LEFT', 15);
define('PDF_MARGIN_RIGHT', 15);
define('PDF_FONT_NAME_MAIN', 'helvetica');
define('PDF_FONT_SIZE_MAIN', 10);
define('PDF_FONT_NAME_DATA', 'helvetica');
define('PDF_FONT_SIZE_DATA', 8);
define('PDF_FONT_MONOSPACED', 'courier');
define('PDF_IMAGE_SCALE_RATIO', 1.25);
define('HEAD_MAGNIFICATION', 1.1);
define('K_CELL_HEIGHT_RATIO', 1.25);
define('K_TITLE_MAGNIFICATION', 1.3);
define('K_SMALL_RATIO', 2/3);
define('K_THAI_TOPCHARS', true);
define('K_TCPDF_CALLS_IN_HTML', true);



/*************** load external library **********************/
include_once $rootDir .DIRECTORY_SEPARATOR . 
	'vendor' . DIRECTORY_SEPARATOR . 'autoloader.php';
//x require_once 'phar://' . $rootDir . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'vendor.phar';



/*************** configure Epiphany ********************/
Epi::setPath('base', $libDir . DIRECTORY_SEPARATOR . 'epiphany');
Epi::init('api');
Epi::setSetting('exceptions', true);



/*************** configure IG ****************/
\Ig\Config\Loader::configure($rootDir . DIRECTORY_SEPARATOR . 
	'data' . DIRECTORY_SEPARATOR . 'config.php', 
	$rootDir);

$setting = \Ig\Config::getProfile();
\Ig\Db::configure(
	$setting->dsm, 
	$setting->dbUsr, 
	$setting->dbPwd,
	$setting->dbInitial, 
	$setting->dbLen);

\Ig\File::checkDirectory($setting->absUploadPath, true);
\Ig\File::checkDirectory($setting->absTemplatePath, true);
\Ig\File::checkDirectory($setting->absTemporaryPath, true);

\Ig\Email::configure(
	$setting->smtpHost,
	$setting->smtpEmail, 
	$setting->smtpName,
	$setting->smtpUsr, 
	$setting->smtpPwd,
	$setting->smtpSecure, 
	$setting->smtpPort);

if(\Ig\Config::getConfig('debugEmail')) {
	\Ig\Email::setDebug(
		true, 
		\Ig\Config::getConfig('debugEmailAddress'));
}

\Ig\File\Attachment::configure($setting->absUploadPath);



/*************** Time Zone ******************************/
date_default_timezone_set($setting->timeZone);



/**************** Server URL ****************************/
if ((\Ig\Config::getConfig('serverurl'))) {
	$serverHostUrl = \Ig\Config::getConfig('serverurl');
}
else {
	$serverHostUrl = substr(\Ig\Util::getServerUrl(), 0, -4);
}
define('SERVER_URL', $serverHostUrl); //to remove '/api'
define('SERVER_ADM_URL', SERVER_URL . '/adm');



/**************** configure Rain Tpl Engine ***********************/
$config = array(
	"tpl_ext"	=> 'tpl',
	"tpl_dir"	=> \Ig\Config::getProfile()->absTemplatePath . DIRECTORY_SEPARATOR,
	"cache_dir"	=> \Ig\Config::getProfile()->absTemporaryPath . DIRECTORY_SEPARATOR,
	"debug"		=> false, // set to false to improve the speed
);
\Rain\Tpl::configure( $config );
?>