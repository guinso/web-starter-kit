<?php 
namespace Starter\Recipe;

class SimpleRecipe implements \Hx\Recipe\RecipeInterface {
	
	const DEFAULT_PROFILE_KEY = 'defaultProfile';
	
	private $lut, $profile, $rootDir;
	
	public function __construct($rootDir)
	{
		$this->lut = array();
		
		$this->profile = array();
		
		$this->rootDir = $rootDir;
	}
	
	/**
	 * Get general value based on key provided
	 * @param string $key
	 * @param mix $defaultValue
	 * @return mix
	*/
	public function get($key, $defaultValue = null)
	{
		if (!array_key_exists($key, $this->lut))
			return $defaultValue;
		else
			return $this->lut[$key];
	}
	
	/**
	 * Set general value into specific key
	 * @param string $key
	 * @param mix $value
	*/
	public function set($key, $value)
	{
		$this->lut[$key] = $value;
	}
	
	/**
	 * Get system profile
	 * @param string $name	<p>Profile name</p>
	*/
	public function getProfile($name)
	{
		return $this->profile[$name];
	}
	
	/**
	 * Set system profile
	 * @param string $name	<p>Profile name</p>
	 * @param ProfileInterface $profile
	*/
	public function setProfile($name, \Hx\Recipe\ProfileInterface $profile)
	{
		$this->profile[$name] = $profile;
	}
	
	public function removeProfile($name)
	{
		if (array_key_exists($name, $this->profile))
			unset($this->profile[$name]);
		else
			Throw new \Starter\Recipe\RecipeException(
				"Fail to remove profile $name, there is no such profile found in LUT.");
	}
	
	/**
	 * Get default system profile
	 * <p>If no default profile is set, first occurance of profile will be selected instead</p>
	*/
	public function getDefaultProfile()
	{
		return $this->profile[$this->lut[self::DEFAULT_PROFILE_KEY]];
	}

	/**
	 * Set default profile
	 * @param string $name	<p>Profile name</p>
	 */
	public function setDefaultProfile($name)
	{
		if (!array_key_exists($name, $this->profile))
			Throw new \Starter\Recipe\RecipeException("There is no such profile $name.");
		else
			$this->lut[self::DEFAULT_PROFILE_KEY] = $name;
	}
	
	/**
	 * Get all profiles
	 * @return array <p>Associate array with profile name as array key, profile as array value</p>
	*/
	public function getAllProfiles()
	{
		return $this->profile;
	}
	
	public function getAll()
	{
		return $this->lut;
	}
	
	///////////////////// Custom Getter and Setter ///////////////
	public function getRootPath()
	{
		return $this->rootDir;
	}
	
	public function getGuid()
	{
		return $this->get('guid');
	}
	
	public function setGuid($value)
	{
		$this->set('guid', $value);
	}
	
	public function getIsMaintenance()
	{
		return $this->get('maintenance');
	}
	
	public function setIsMaintenance($value)
	{
		$this->set('maintenance', $value);
	}
	
	public function getIsDebugEmail()
	{
		return $this->get('debugEmail');
	}
	
	public function setIsDebugEmail($value)
	{
		$this->set('debugEmail', $value);
	}
	
	public function getServerUrl()
	{
		return $this->get('serverUrl');
	}
	
	public function setServerUrl($value)
	{
		$this->set('serverUrl', $value);
	}
	
	public function getUsername()
	{
		return $this->get('username');
	}
	
	public function setUsername($value)
	{
		$this->set('username', $value);
	}
	
	public function getPassword()
	{
		return $this->get('password');
	}
	
	public function setPassword($value)
	{
		$this->set('password', $value);
	}
	
	public function getDebugEmailAddress()
	{
		return $this->get('debugEmailAddress');
	}
	
	public function setDebugEmailAddress($value)
	{
		$this->set('debugEmailAddress', $value);
	}
}
?>