<?php 
/********************* Default Fatal Error Handler ******************/
error_reporting(E_ALL);

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	error_log('StarterKitCron error: [' . $errfile . ':' . $errline . '] ' . $errstr);
}

function myFatalErrorHandler()
{
	$error = error_get_last();

	if ($error['type'] === E_ERROR)
	{
		errorHandler(E_ERROR, $error['message'], $error['file'], $error['line']);
	}
}



/********************* Bootstrap ******************/
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php';



/********************* Project Modules *************************/
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .
	'autoloader.php';

//load all module files
\Ig\Util::recursiveDir(
	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'module',
	function($filePath){
		include_once $filePath;
	},
	'/^.+\.php$/'
);



/********************** Ioc Container *****************************/
$iocLoader = new \Hx\IocContainer\RuleLoader\XmlRuleLoader(
	new \Hx\File\File(),
	dirname(__DIR__)
);

$iocContainer = new \Hx\IocContainer\IocContainer(
	$iocLoader->loadDir(
		__DIR__ .DIRECTORY_SEPARATOR .
		'recipe' . DIRECTORY_SEPARATOR .
		'ioc'
	)
);



/********************** load and set configuration ****************/
include_once __DIR__ . DIRECTORY_SEPARATOR . 'sysConfig.php';



/*********************** RESTful Router ****************************/
try 
{
	$logger = $iocContainer->resolve('\Hx\Logger\LoggerInterface');
	
	if ($recipe->getIsMaintenance())
	{
		echo "system under maintenance";
	}
	
	//run schedule
	\Ig\Scheduler::run();
	
	echo "Run schedule done\n";
}
catch(\Exception $ex)
{
	$logger->error(
		$ex->getMessage(), 
		array(
			'filepath' => $ex->getFile(), 
			'linecode' => $ex->getLine())
	);
	
	$prevEx = $ex->getPrevious();
	
	if(!empty($prevEx))
		$logger->error(
			$prevEx->getMessage(),
			array(
				'filepath' => $prevEx->getFile(),
				'linecode' => $prevEx->getLine())
		);
	
	echo $ex->getMessage();
}

?>