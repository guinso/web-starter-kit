<?php 
namespace Starter\Recipe;

class StaticRecipe {
	private static $recipeService;
	
	public static function setService(SimpleRecipeService $service)
	{
		self::$recipeService = $service;
	}
	
	public static function getService()
	{
		return self::$recipeService;
	}
}
?>