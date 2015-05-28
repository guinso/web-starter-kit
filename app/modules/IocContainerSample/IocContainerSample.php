<?php 
class IocContainerSample {
	public static function run()
	{
		$rulesLoader = new \Hx\IocContainer\Loader\XmlRuleLoader(
			new \Hx\File\File(),
			dirname(dirname(dirname(__DIR__)))
		);
		
		$rulesLoader->loadDir(__DIR__ . DIRECTORY_SEPARATOR . 'IocRecipe');
		
		$iocContainer = new \Hx\IocContainer\IocContainer(
			$rulesLoader->loadDir(
				__DIR__ . DIRECTORY_SEPARATOR . 'IocRecipe'
			)
		);
		
		$file = $iocContainer->resolve("\Hx\File\FileInterface");
	}
}
?>