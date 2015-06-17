<?php 
/********************** Default Function Caller *******************/
function home()
{
	echo 'Web Starter Kit<br/>';
	echo  \Ig\Date::getDate() . "<br/>";

	$g = new \Parsedown();
	echo $g->text(
		file_get_contents(
			dirname(__DIR__) . DIRECTORY_SEPARATOR . 'log.md')
	);
}



/************************ Load System Configuration **************************/
$recipeService = $iocContainer->resolve("\Starter\Recipe\SimpleRecipeService");

$recipe = $recipeService->getRecipe();

$profile = $recipeService->getRecipe()->getDefaultProfile();

//server URL
if (!empty($recipe->getServerUrl()))
	$serverHostUrl = $recipe->getServerUrl();
else
	$serverHostUrl = substr(\Ig\Util::getServerUrl(), 0, -4); //to remove '/api'

define('SERVER_URL', $serverHostUrl);

//root path
\Ig\Config\Loader::setRootPath(dirname(__DIR__));

//timezone
date_default_timezone_set($profile->getTimeZone());

//database
\Ig\Db::configure(
		$profile->getPdoDsm(),
		$profile->getDbUsr(),
		$profile->getDbPwd(),
		$profile->getDbInitial(),
		$profile->getDbLen());

//email
\Ig\Email::configure(
		$profile->getSmtpHost(),
		$profile->getSmtpEmail(),
		$profile->getSmtpName(),
		$profile->getSmtpUsr(),
		$profile->getSmtpPwd(),
		$profile->getSmtpSecure(),
		$profile->getSmtpPort());

\Ig\Email::setDebug(
		$recipe->getIsDebugEmail(),
		$recipe->getDebugEmailAddress());

//attachment
\Ig\File\Attachment::configure($profile->getRealUploadPath());


/////////////////// BACKWARD COMPATIBLE WITH IG LIB ////////////////////
\Starter\Recipe\StaticRecipe::setService($recipeService);

\Ig\Config::setLogin($recipe->getUsername(), $recipe->getPassword());
\Ig\Config::setGuid($recipe->getGuid());
\Ig\Config::setConfig('maintenance', $recipe->getIsMaintenance());
\Ig\Config::setConfig('deploy', false);
\Ig\Config::setConfig('debugEmail', $recipe->getIsDebugEmail());
\Ig\Config::set(
		$recipe->get('defaultProfile'),
		new \Ig\Config\Recipe(
				$profile->getDbName(),
				$profile->getDbHost(),
				$profile->getDbUsr(),
				$profile->getDbPwd(),
				$profile->getDbLen(),
				$profile->getDbInitial(),
				$profile->getUploadPath(),
				$profile->getTemplatePath(),
				$profile->getCachePath(),
				$profile->getTimeZone(),
				$profile->getSmtpHost(),
				$profile->getSmtpUsr(),
				$profile->getSmtpPwd(),
				$profile->getSmtpEmail(),
				$profile->getSmtpName(),
				$profile->getSmtpSecure(),
				$profile->getSmtpPort()));
\Ig\Config::setDefaultProfileKey($recipe->get('defaultProfile'));
?>