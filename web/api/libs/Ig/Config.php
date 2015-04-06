<?php
namespace Ig;

class Config {
	private static $_config = array();
	private static $recipes = array();
	private static $_defaultKey = '';
	private static $_guid = '';
	private static $_usr = 'admin'; 
	private static $_pwd = '';
	
	/**
	 * Get admin username
	 * @return string
	 */
	public static function getUsr() {
		return self::$_usr;
	}

	/**
	 * Get admin password
	 * @return string
	 */
	public static function getPwd() {
		return self::$_pwd;
	}
	
	/**
	 * Set login information
	 * @param string $username
	 * @param string $password
	 */
	public static function setLogin($username, $password) {
		self::$_usr = $username;
		self::$_pwd = $password;
	}
	
	/**
	 * Login user
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public static function isLoginMatch($username, $password) {
		return self::$_usr == $username && self::$_pwd == $password;
	}
	
	public static function getGuid() {
		/*
		if(empty(self::$_guid))
			self::$_guid = uniqid();
		*/
		return self::$_guid;
	}
	
	public static function setGuid($guid) {
		self::$_guid = $guid;
	}
	
	/**
	 * Get general configuration information
	 */
	public static function getConfig($name) {
		return self::$_config[$name];
	}
	
	/**
	 * Set general configuration
	 * @param string $name
	 * @param mixed $value
	 * @throws Exception
	 */
	public static function setConfig($name, $value) {
		self::$_config[$name] = $value;
	} 
	
	/**
	 * Get configuration key
	 */
	public static function getConfigKeys() {
		return array_keys(self::$_config);
	}

	/**
	 * Get configuration profile, leave blank to get default profile
	 * @param string $name
	 * @return \Ig\Config\Recipe
	 */
	public static function getProfile($name = null) {
		$name = empty($name)? self::$_defaultKey : $name;
		
		$tmp = self::$recipes[$name];
		
		if(empty($tmp))
			throw new \Exception("key <$name> not found in IgConfig profile");
		else
			return $tmp;
	}
	
	/**
	 * Set default profile selector
	 * @param unknown $name
	 * @throws Exception
	 */
	public static function setDefaultProfileKey($name) {
		if(!array_key_exists($name, self::$recipes))
			throw new \Exception("Set default profile rejected, key <$name> not found in IgConfig profile.");
		else
			self::$_defaultKey = $name;
	}
	
	/**
	 * Get default profile key
	 * @return string
	 */
	public static function getDefaultProfileKey() {
		return self::$_defaultKey;
	}
	
	/**
	 * Set configuration profile
	 * @param string $name
	 * @param \Ig\Config\Recipe $value
	 */
	public static function set($name, \Ig\Config\Recipe $value) {
		self::$recipes[$name] = $value;
		
		if(count(self::$recipes) == 1)
			self::$_defaultKey = $name;
	}
	
	/**
	 * Get profile keys
	 *  @return array
	 */
	public static function getProfileKeys() {
		return array_keys(self::$recipes);
	}
}
?>