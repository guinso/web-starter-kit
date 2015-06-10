<?php 
//load minimum setting file

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 
	'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 
	'app' . DIRECTORY_SEPARATOR . 'lib' . 
	DIRECTORY_SEPARATOR . 'autoloader.php';

/********************** Ioc Container *****************************/
$iocLoader = new \Hx\IocContainer\RuleLoader\XmlRuleLoader(
		new \Hx\File\File(),
		dirname(__DIR__)
);

$iocContainer = new \Hx\IocContainer\IocContainer(
	$iocLoader->loadDir(
		dirname(__DIR__ ) . DIRECTORY_SEPARATOR .
		'app' . DIRECTORY_SEPARATOR .
		'recipe' . DIRECTORY_SEPARATOR .
		'ioc'
	)
);

/********************* Recipe *******************************/
$recipeService = $iocContainer->resolve("\Erp\Recipe\SimpleRecipeService");

$recipe = $recipeService->getRecipe();

//show 503 if mantenance mode is on
if($recipe->getIsMaintenance()) {
?>
	Site on maintenance. Please try later
<?php
	die();
}
?>