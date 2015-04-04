<?php 
/** Manage configuration file in php format **/
Class IgConfigLoader {
	
private static $_filepath;
private static $_defaultConfig;
	
public static function configure($filepath) {
	
	self::$_filepath = null;
	
	self::$_defaultConfig = '';
	
	if(!file_exists($filepath))
		throw new Exception("Configuration file not found in server");
	else {
		self::$_filepath = $filepath;
		
		include self::$_filepath;
	}
}

public static function updateSetting() {
	$cache = '';
	if(!is_writable(self::$_filepath))
		throw new Exception("Configuration file is not writtable");
	else {
		//backup configuration file
		$cache = file_get_contents(self::$_filepath);
	}
	
	$output = '';

	//write login information
	$username = IgConfig::getUsr();
	$password = IgConfig::getPwd();
	$output .= "IgConfig::setLogin('$username','$password');\n\n";
	
	//write guid
	$guid = IgConfig::getGuid();
	$output .= "IgConfig::setGuid('$guid');\n\n";
	
	//write general configuration
	$keys = IgConfig::getConfigKeys();
	foreach($keys as $key) {
		$v = IgConfig::getConfig($key);
		$x = self::dumpVar($v);
		$output .= "IgConfig::setConfig('$key', $x);\n";
	}
	$output .= "\n";
	
	//write IgConfig settings
	$igConfigKeys = IgConfig::getProfileKeys();
	foreach($igConfigKeys as $k) {
		$tmp = IgConfig::getProfile($k);
		
		$output .= "IgConfig::set('$k', new IgConfigRecipe('" .
			$tmp->dbName . "','" . $tmp->dbHost . "','" . $tmp->dbUsr . "','" . $tmp->dbPwd . "'," .
			$tmp->dbLen . ",'" . $tmp->dbInitial . "','" . 
			$tmp->uploadPath . "','" . $tmp->templatePath . "','" . $tmp->temporaryPath . "','" . 
			$tmp->timeZone . "','" .
			$tmp->smtpHost . "','" . $tmp->smtpUsr . "','" . $tmp->smtpPwd . "','" .
			$tmp->smtpEmail . "','" . $tmp->smtpName . "','" .
			$tmp->smtpSecure . "'," . $tmp->smtpPort .
		"));\n\n";
	}
	
	//Set default profile if available
	$defaultKey = IgConfig::getDefaultProfileKey();
	if(!empty($defaultKey))
		$output .= "IgConfig::setDefaultProfilekey('$defaultKey');\n\n";

	$result = file_put_contents(self::$_filepath, "<?php\n" . $output . "?>");
	
	if($result == false) {
		//restore configuration file
		file_put_contents($cache);
		
		throw new Exception("Fail to update configuration file.");
	}
}

private static function dumpVar($var) {
	if(is_array($var)) {
		$keys = array_keys($var);
		$output = '';
		foreach($var as $k => $v)
			$output .= (is_int($k)? "$k => ": "'$k' => ") . dumpVar($v) . ', ';
		
		return 'array(' . $output . ')';
	} else if(is_float($var) || is_int($var)) {
		return "$var";
	} else if(is_string($var)) {
		return "'$var'";
	} else if(is_bool($var)) {
		return $var? 'true':'false';
	} else if(is_null($var)) {
		return 'null';
	} else {
		throw new Exception("ConfigUtil not support dump non-scala value.");
	}
}

}
?>