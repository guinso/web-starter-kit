<?php 
// Tell PHP that we're using UTF-8 strings until the end of the script
mb_internal_encoding('UTF-8');

// Tell PHP that we'll be outputting UTF-8 to the browser
mb_http_output('UTF-8');

define('DS' , DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(dirname(__FILE__)));

define('API_DIR', dirname(__FILE__));
define('ADM_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'adm');
define('ADM_API_DIR', ADM_DIR . DIRECTORY_SEPARATOR . 'api');

session_start();

/******************** Library *************************/
require_once API_DIR . DS . 'libs'. DS . 'notorm' . DS . 'NotORM.php';
require_once API_DIR . DS . 'libs' . DS . 'epiphany' . DS . 'Epi.php';

require_once API_DIR . DS . 'libs' . DS . 'phpexcel' . DS . 'PHPExcel.php';
require_once API_DIR . DS . 'libs' . DS . 'phpexcel' . DS . 'PHPExcel' . DS . 'IOFactory.php';
require_once API_DIR . DS . 'libs' . DS . 'phpmailer' . DS . 'PHPMailerAutoload.php';
//x require_once API_DIR . '/libs/guzzle/autoloader.php';

require_once API_DIR . DS . 'libs' . DS . 'parsedown' . DS . 'Parsedown.php';

require_once API_DIR . DS . 'libs' . DS . 'jshrink' . DS . 'Minifier.php';

require_once API_DIR . DS . 'libs' . DS . 'raintpl' . DS . 'library' . DS . 'Rain' . DS . 'autoload.php';

//TCPDF
define('K_TCPDF_EXTERNAL_CONFIG', 1); //to override default setting
define('K_PATH_MAIN', '');
define('K_PATH_URL', '');
define('K_PATH_FONTS', API_DIR . DS . 'libs' . DS . 'tcpdf' . DS . 'fonts' . DS);
define('K_PATH_CACHE', API_DIR . DS . 'tmp' . DS);
define('K_PATH_URL_CACHE', API_DIR . DS . 'tmp' . DS);
define('K_PATH_IMAGES', ROOT_DIR . DS . 'img' . DS);
define('K_BLANK_IMAGE', K_PATH_IMAGES . DS . '_blank.png');
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
include_once API_DIR . DS . 'libs' . DS . 'tcpdf' . DS . 'tcpdf.php';

include_once API_DIR . DS . 'libs' . DS . 'phpjasperxml' . DS . 'PHPJasperXML.inc.php';

require_once API_DIR . DS . 'libs' . DS . 'Ig' . DS . 'autoloader.php';

Epi::setPath('base', API_DIR . DS . 'libs' . DS . 'epiphany');
Epi::init('api');
Epi::setSetting('exceptions', true);

/*************** CORE AND CONFIGURATION ****************/
\Ig\Config\Loader::configure(API_DIR . DS . 'config.php');
$setting = \Ig\Config::getProfile();

\Ig\Db::configure(
	$setting->dsm, $setting->dbUsr, $setting->dbPwd,
	$setting->dbInitial, $setting->dbLen);

\Ig\File::checkDirectory($setting->absUploadPath, true);
\Ig\File::checkDirectory($setting->absTemplatePath, true);
\Ig\File::checkDirectory($setting->absTemporaryPath, true);

\Ig\Email::configure(
	$setting->smtpHost,
	$setting->smtpEmail, $setting->smtpName,
	$setting->smtpUsr, $setting->smtpPwd,
	$setting->smtpSecure, $setting->smtpPort);

if(\Ig\Config::getConfig('debugEmail')) {
	\Ig\Email::setDebug(true, \Ig\Config::getConfig('debugEmailAddress'));
}

\Ig\File\Attachment::configure($setting->absUploadPath);

//timezone
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

//template engine
$config = array(
	"tpl_ext"	=> 'tpl',
	"tpl_dir"	=> \Ig\Config::getProfile()->absTemplatePath . DS,
	"cache_dir"	=> \Ig\Config::getProfile()->absTemporaryPath . DS,
	"debug"		=> false, // set to false to improve the speed
);
\Rain\Tpl::configure( $config );
?>