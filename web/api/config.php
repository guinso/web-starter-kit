<?php
IgConfig::setLogin('admin','1q2w3e');

IgConfig::setGuid('54a0e8e43cbf1');

IgConfig::setConfig('maintenance', false);
IgConfig::setConfig('deploy', false);

IgConfig::set('web', new IgConfigRecipe('starter','localhost','root','1q2w3e',10,'A','@api/upload','@api/template','@api/tmp','Asia/Kuala_Lumpur','mail.domain.com','dev@domain.com','123456789','dev@domain.com','Development','tls',587));

IgConfig::setDefaultProfilekey('web');

?>