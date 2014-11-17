<?php 
//set your defined configuration
define('MAIN_ROOT_DIR', dirname(dirname(dirname(dirname(__FILE__)))));

define('WEB_API_DIR', MAIN_ROOT_DIR . DIRECTORY_SEPARATOR . 'api');
define('WEB_UPLOAD', WEB_API_DIR . DIRECTORY_SEPARATOR . 'upload');
define('WEB_TPL', WEB_API_DIR . DIRECTORY_SEPARATOR . 'template');
define('WEB_TMP', WEB_API_DIR . DIRECTORY_SEPARATOR . 'tmp');

define('ADM_API_DIR', MAIN_ROOT_DIR . DIRECTORY_SEPARATOR . 'adm' . DIRECTORY_SEPARATOR . 'api');
define('ADM_UPLOAD', ADM_API_DIR . DIRECTORY_SEPARATOR . 'upload');
define('ADM_TPL', ADM_API_DIR . DIRECTORY_SEPARATOR . 'template');
define('ADM_TMP', ADM_API_DIR . DIRECTORY_SEPARATOR . 'tmp');

//web deploy configuration
IgConfig::set('web', new IgConfigRecipe(
	'mysql:dbname=starter;host=localhost;charset=utf8', 'your-db-username', '123456789', 
	10, 'A', 
	WEB_UPLOAD, WEB_TPL, WEB_TMP,
	'Asia/Kuala_Lumpur',
	'mail.domain.com', 'dev@domain.com', '123456789',
	'dev@domain.com', 'Development', 
	'tls', 587));

//admin configuration
IgConfig::set('adm', new IgConfigRecipe(
	'mysql:dbname=starter_adm;host=localhost;charset=utf8', 'your-db-username', '123456789', 
	10, 'A', 
	ADM_UPLOAD, ADM_TPL, ADM_TMP,
	'Asia/Kuala_Lumpur',
	'mail.domain.com', 'dev@domain.com', '123456789',
	'dev@domain.com', 'Development', 
	'tls', 587));

?>