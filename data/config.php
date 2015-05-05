<?php
\Ig\Config::setLogin('admin','1q2w3e');

\Ig\Config::setGuid('54a0e8e43cbf1');

\Ig\Config::setConfig('maintenance', false);
\Ig\Config::setConfig('deploy', false);
\Ig\Config::setConfig('debugEmail', false);

\Ig\Config::set('web', new \Ig\Config\Recipe('starter','localhost','root','1q2w3e',10,'A','@data/upload','@data/template','@data/cache','Asia/Kuala_Lumpur','mail.domain.com','dev@domain.com','123456789','dev@domain.com','Development','tls',587));

\Ig\Config::setDefaultProfileKey('web');

?>