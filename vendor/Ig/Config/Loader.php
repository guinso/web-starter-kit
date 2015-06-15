<?php 
namespace Ig\Config;

/** Manage configuration file in php format **/
Class Loader {
	
	private static $_filepath;
	private static $_defaultConfig;
	
	private static $_rootpath;
		
	public static function configure($filepath, $rootPath) 
	{	
		self::$_filepath = null;
		self::$_defaultConfig = '';
		
		self::$_rootpath = $rootPath;
		
		if (!file_exists($filepath)) {
			throw new \Exception("Configuration file not found in server");
		} else {
			self::$_filepath = $filepath;
			include self::$_filepath;
			
			self::_updateAbsPath();
		}
	}
	
	public static function setRootPath($rootPath)
	{
		if (file_exists($rootPath))
			self::$_rootpath = $rootPath;
		else 
			throw new \Exception("Root path $rootPath not found.");
	}
	
	public static function getRootPath()
	{
		return self::$_rootpath;
	}
	
	private static function _updateAbsPath()
	{
		$keys = \Ig\Config::getProfileKeys();
		foreach ($keys as $k) {
			$profile = \Ig\Config::getProfile($k);
			$profile->setRootPath(self::$_rootpath);
		}
	}
	
	public static function updateSetting() 
	{
		$cache = '';
		if (!is_writable(self::$_filepath))
			throw new \Exception("Configuration file is not writtable");
		else {
			//backup configuration file
			$cache = file_get_contents(self::$_filepath);
		}
		
		$output = '';
	
		//write login information
		$username = \Ig\Config::getUsr();
		$password = \Ig\Config::getPwd();
		$output .= "\\Ig\\Config::setLogin('$username','$password');\n\n";
		
		//write guid
		$guid = \Ig\Config::getGuid();
		$output .= "\\Ig\\Config::setGuid('$guid');\n\n";
		
		//write general configuration
		$keys = \Ig\Config::getConfigKeys();
		foreach ($keys as $key) {
			$v = \Ig\Config::getConfig($key);
			$x = self::dumpVar($v);
			$output .= "\\Ig\\Config::setConfig('$key', $x);\n";
		}
		$output .= "\n";
		
		//write IgConfig settings
		$igConfigKeys = \Ig\Config::getProfileKeys();
		foreach ($igConfigKeys as $k) {
			$tmp = \Ig\Config::getProfile($k);
			
			$output .= "\\Ig\\Config::set('$k', new \\Ig\\Config\\Recipe('" .
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
		$defaultKey = \Ig\Config::getDefaultProfileKey();
		if (!empty($defaultKey)) {
			$output .= "\\Ig\\Config::setDefaultProfilekey('$defaultKey');\n\n";
		}
	
		$result = file_put_contents(self::$_filepath, "<?php\n" . $output . "?>");
		
		if ($result == false) {
			//restore configuration file
			file_put_contents($cache);
			
			throw new \Exception("Fail to update configuration file.");
		}
	}
	
	private static function dumpVar($var) 
	{
		if (is_array($var)) {
			$keys = array_keys($var);
			$output = '';
			foreach ($var as $k => $v) {
				$output .= (is_int($k)? "$k => ": "'$k' => ") . dumpVar($v) . ', ';
			}
			
			return 'array(' . $output . ')';
			
		} elseif (is_float($var) || is_int($var)) {
			return "$var";
			
		} elseif (is_string($var)) {
			return "'$var'";
			
		} elseif (is_bool($var)) {
			return $var? 'true':'false';
			
		} elseif (is_null($var)) {
			return 'null';
			
		} else {
			throw new \Exception("ConfigUtil not support dump non-scala value.");
		}
	}

}
?>