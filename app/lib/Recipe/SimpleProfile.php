<?php 
namespace Starter\Recipe;

class SimpleProfile implements \Hx\Recipe\ProfileInterface {
	
	private $lut, $rootDir;
	
	public function __construct($rootDir)
	{
		$this->lut = array();
		
		$this->rootDir = $rootDir;
	}
	
	/**
	 * Get value from profile
	 * @param string $key
	 * @param mix $defaultValue		<p>default value to return if not found</p>
	 */
	public function get($key, $defaultValue = null)
	{
		if (array_key_exists($key, $this->lut))
			return $this->lut[$key];
		else
			return $defaultValue;
	}
	
	/**
	 * Set value into profile
	 * @param string $key
	 * @param mix $value
	 */
	public function set($key, $value)
	{
		$this->lut[$key] = $value;
	}
	
	/**
	 * Return all profile values
	 * @return array	<p>associate array</p>
	 */
	public function getAllValues()
	{
		return $this->lut;
	}
	
	///////////////////// Custom Getter and Setter ///////////
	
	public function getRealUploadPath()
	{
		return $this->getPathString(
			$this->get('uploadPath'));
	}
	
	public function getRealTemplatePath()
	{
		return $this->getPathString(
			$this->get('templatePath'));
	}
	
	public function getRealCachePath()
	{
		return $this->getPathString(
			$this->get('cachePath'));
	}
	
	public function getPdoDsm()
	{
		return 'mysql:' .
			'dbname=' . $this->getDbName() . 
			';host=' . $this->getDbHost() . 
			';charset=utf8mb4';
	}
	
	public function getName()
	{
		return $this->get('name');
	}
	
	public function setName($value)
	{
		return $this->set('name', $value);
	}
	
	public function getDbName()
	{
		return $this->get('dbName');
	}
	
	public function setDbName($value)
	{
		return $this->set('dbName', $value);
	}
	
	public function getDbHost()
	{
		return $this->get('dbHost');
	}
	
	public function setDbHost($value)
	{
		return $this->set('dbHost', $value);
	}
	
	public function getDbUsr()
	{
		return $this->get('dbUsr');
	}
	
	public function setDbUsr($value)
	{
		return $this->set('dbUsr', $value);
	}
	
	public function getDbPwd()
	{
		return $this->get('dbPwd');
	}
	
	public function setDbPwd($value)
	{
		return $this->set('dbPwd', $value);
	}
	
	public function getDbLen()
	{
		return $this->get('dbLen');
	}
	
	public function setDbLen($value)
	{
		return $this->set('dbLen', $value);
	}
	
	public function getDbInitial()
	{
		return $this->get('dbInitial');
	}
	
	public function setDbInitial($value)
	{
		return $this->set('dbInitial', $value);
	}
	
	public function getTimeZone()
	{
		return $this->get('timeZone');
	}
	
	public function setTimeZone($value)
	{
		return $this->set('timeZone', $value);
	}
	
	public function getUploadPath()
	{
		return $this->get('uploadPath');
	}
	
	public function setUploadPath($value)
	{
		return $this->set('uploadPath', $value);
	}
	
	public function getTemplatePath()
	{
		return $this->get('templatePath');
	}
	
	public function setTemplatePath($value)
	{
		return $this->set('templatePath', $value);
	}
	
	public function getCachePath()
	{
		return $this->get('cachePath');
	}
	
	public function setCachePath($value)
	{
		return $this->set('cachePath', $value);
	}
	
	public function getSmtpHost()
	{
		return $this->get('smtpHost');
	}
	
	public function setSmtpHost($value)
	{
		return $this->set('smtpHost', $value);
	}
	
	public function getSmtpUsr()
	{
		return $this->get('smtpUsr');
	}
	
	public function setSmtpUsr($value)
	{
		return $this->set('smtpUsr', $value);
	}
	
	public function getSmtpPwd()
	{
		return $this->get('smtpPwd');
	}
	
	public function setSmtpPwd($value)
	{
		return $this->set('smtpPwd', $value);
	}
	
	public function getSmtpName()
	{
		return $this->get('smtpName');
	}
	
	public function setSmtpName($value)
	{
		return $this->set('smtpName', $value);
	}
	
	public function getSmtpEmail()
	{
		return $this->get('smtpEmail');
	}
	
	public function setSmtpEmail($value)
	{
		return $this->set('smtpEmail', $value);
	}
	
	public function getSmtpSecure()
	{
		return $this->get('smtpSecure');
	}
	
	public function setSmtpSecure($value)
	{
		return $this->set('smtpSecure', $value);
	}
	
	public function getSmtpPort()
	{
		return $this->get('smtpPort');
	}
	
	public function setSmtpPort($value)
	{
		return $this->set('smtpPort', $value);
	}
	
	private function getPathString($value)
	{
		return mb_ereg_replace(
			'@',
			$this->rootDir . DIRECTORY_SEPARATOR,
			$value);
	}
}
?>