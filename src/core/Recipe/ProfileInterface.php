<?php 
namespace Hx\Recipe;

interface ProfileInterface {
	
	/**
	 * Get value from profile
	 * @param string $key
	 * @param mix $defaultValue		<p>default value to return if not found</p>
	 */
	public function get($key, $defaultValue = null);
	
	/**
	 * Set value into profile
	 * @param string $key
	 * @param mix $value
	 */
	public function set($key, $value);
	
	/**
	 * Return all profile values
	 * @return array	<p>associate array</p>
	 */
	public function getAllValues();
}
?>