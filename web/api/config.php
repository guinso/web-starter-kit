<?php
IgConfig::setLogin('admin','1q2w3e');

IgConfig::setGuid('54a0e8e43cbf1');

IgConfig::setConfig('maintenance', false);
IgConfig::setConfig('deploy', false);
IgConfig::setConfig('debugEmail', false);

IgConfig::set('web', new IgConfigRecipe('starter','localhost','root','1q2w3e',10,'A','@api/upload','@api/template','@api/tmp','Asia/Kuala_Lumpur','mail.domain.com','dev@domain.com','123456789','dev@domain.com','Development','tls',587));

IgConfig::set('test', new IgConfigRecipe('test','localhost','root','1q2w3e',10,'B','@api/upload','@api/template','@api/tmp','Asia/Kuala_Lumpur','mail.ingensolution.com','ingen','123456789','ingen@ingensolution.com','ingen','tls',587));

IgConfig::setDefaultProfilekey('web');

?>