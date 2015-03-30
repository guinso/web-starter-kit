<?php
	$profile = IgConfig::getProfile();
	$db = $profile->dbName; //database name
	$user = $profile->dbUsr; //database username
	$pass = $profile->dbPwd; //database password
	$server = $profile->dbHost; //database host; e.g. localhost
?>