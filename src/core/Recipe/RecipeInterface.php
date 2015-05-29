<?php 
namespace Hx\Recipe;

interface RecipeInterface {

	/**
	 * Get general value based on key provided
	 * @param string $key
	 * @param mix $defaultValue
	 * @return mix
	 */
	public function get($key, $defaultValue = null);
	
	/**
	 * Set general value into specific key
	 * @param string $key
	 * @param mix $value
	 */
	public function set($key, $value);
	
	/**
	 * Get system profile
	 * @param string $name
	 */
	public function getProfile($name);
	
	/**
	 * Set system profile
	 * @param string $name	<p>Profile name</p>
	 * @param ProfileInterface $profile
	 */
	public function setProfile($name, ProfileInterface $profile);
	
	/**
	 * Get default system profile
	 * <p>If no default profile is set, first occurance of profile will be selected instead</p>
	 */
	public function getDefaultProfile();

	/**
	 * Set default profile
	 * @param string $name	<p>Profile name</p>
	 */
	public function setDefaultProfile($name);
	
	/**
	 * Get all profiles
	 * @return array <p>Associate array with profile name as array key, profile as array value</p>
	 */
	public function getAllProfiles();
	
	/**
	 * Get all general values
	 * @return array <p>Associate array</p>
	 */
	public function getAll();
}
?>