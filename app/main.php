<?php 
/********************* Default Fatal Error Handler ******************/
error_reporting(E_ALL);

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	error_log('StarterKit error: [' . $errfile . ':' . $errline . '] ' . $errstr);
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
		$httpOutput = $iocContainer->resolve('\Hx\Http\Output\Text');
		
		$httpOutput->generateOuput(
			503,
			array('data' => "System under maintenance")
		);
	}
	
	$router = $iocContainer->resolve("\Hx\Route\RestRouter");
	
	\Ig\Login::ping();
	
	$router->run();
}
catch(\Hx\Route\RestRouterException $ex)
{
	//unexpected error ocurred, need to fix ASAP
	
	$logger->error(
		$ex->getMessage(),
		array(
			'filepath' => $ex->getFile(),
			'linecode' => $ex->getLine()
		)
	);
	
	$prevEx = $ex->getPrevious();
	if(!empty($prevEx))
		$logger->error(
			$prevEx->getMessage(),
			array(
				'filepath' => $prevEx->getFile(),
				'linecode' => $prevEx->getLine())
		);
	
	$httpOutput = $iocContainer->resolve('\Hx\Http\Output\Text');
	
	switch($ex->getErrorType())
	{
		case \Hx\Route\RestRouterException::INPUT_ERROR: //delivery mechanism
			$httpOutput->generateOuput(
				500,
				array('data' => "Invalid request info: " . $ex->getMessage())
			);
			break;
			
		case \Hx\Route\RestRouterException::MSG_ERROR: //application pipeline
			$httpOutput->generateOuput(
				500,
				array('data' => date('Y-m-d H:s:i') . 
					"System encountered application pipeline error," . 
					" kindly contact system administrator to solve issue.")
			);
			break;
			
		case \Hx\Route\RestRouterException::DOMAIN_ERROR: //business logic
			$httpOutput->generateOuput(
				406,
				array('data' => $ex->getMessage())
			);
			break;
			
		default:
			$httpOutput->generateOutput(
				500,
				array('data' => date('Y-m-d H:s:i') .
					"Unknown routing error code catched: {$ex->getErrorType()}")
			);
			break; //something wrong with the code...
	}
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
	
	//send 500 error code with message
	$httpOutput = $iocContainer->resolve('\Hx\Http\Output\Text');
	
	$httpOutput->generateOuput(
		500,
		array('data' => 
			date('Y-m-d H:s:i') . ' System encounter internal runtime error. ' . 
			'kindly contact system administrator to solve the issue.'
		)
	);
}

?>