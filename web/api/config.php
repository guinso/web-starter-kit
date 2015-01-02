<?php
IgConfig::setLogin('admin','1q2w3e');

IgConfig::setGuid('54a0e8e43cbf1');

IgConfig::setConfig('maintenance', false);
IgConfig::setConfig('deploy', false);

define('WEB_UPLOAD', API_DIR . DS . 'upload');
define('WEB_TPL', API_DIR . DS . 'template');
define('WEB_TMP', API_DIR . DS . 'tmp');

define('ADM_UPLOAD', ADM_API_DIR . DS . 'upload');
define('ADM_TPL', ADM_API_DIR . DS . 'template');
define('ADM_TMP', ADM_API_DIR . DS . 'tmp');

//web deploy configuration
IgConfig::set('web', new IgConfigRecipe(
'mysql:dbname=starter;host=localhost;charset=utf8', 'root', '1q2w3e',
10, 'A',
WEB_UPLOAD, WEB_TPL, WEB_TMP,
'Asia/Kuala_Lumpur',
'mail.domain.com', 'dev@domain.com', '123456789',
'dev@domain.com', 'Development',
'tls', 587));

//admin configuration
IgConfig::set('adm', new IgConfigRecipe(
'mysql:dbname=starter_adm;host=localhost;charset=utf8', 'root', '1q2w3e',
10, 'A',
ADM_UPLOAD, ADM_TPL, ADM_TMP,
'Asia/Kuala_Lumpur',
'mail.domain.com', 'dev@domain.com', '123456789',
'dev@domain.com', 'Development',
'tls', 587));

IgConfig::setDefaultProfilekey('web');

?>