<?php 
namespace Hx\Recipe;

interface LoaderInterface {
	
	public function load($sourcePath);
	
	public function save($sourcePath, RecipeInterface $recipe);
}
?>