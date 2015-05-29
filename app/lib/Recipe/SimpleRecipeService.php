<?php 
namespace Starter\Recipe;

class SimpleRecipeService {
	
	private $loader, $recipe, $configFilePath;
	
	public function __construct(SimpleXmlLoader $loader, $configFilePath) {
		
		$this->configFilePath = $configFilePath;
		
		$this->loader = $loader;
		
		$this->loadRecipe();
	}
	
	public function loadRecipe()
	{
		$this->recipe = $this->loader->load($this->configFilePath);
	}
	
	public function updateRecipe()
	{
		$this->loader->save(
			$this->configFilePath, 
			$this->recipe);
	}
	
	public function getRecipe()
	{
		return $this->recipe;
	}
}
?>