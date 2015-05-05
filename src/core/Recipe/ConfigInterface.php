<?php 
namespace Hx\Recipe;

interface ConfigInterface {
	public function updateConfigure();
	
	public function loadConfigure();
	
	public function getRecipe($name);
	
	public function setRecipe($name, RecipeInterface $recipe);
	
	public function getDefaultRecipe();
	
	public function setDefaultRecipe($name);
	
	public function getAllRecipe();
	
	public function getKeyValue($key, $defaultValue);
	
	public function setKeyValue($key, $value);
}
?>