<?php 
/********************* Default Fatal Error Handler ******************/
error_reporting(E_ALL);

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	error_log('StarterKitAdmin error: [' . $errfile . ':' . $errline . '] ' . $errstr);
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
	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'moduleAdmin',
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
		'iocAdmin'
	)
);



/********************** load and set configuration ****************/
include_once __DIR__ . DIRECTORY_SEPARATOR . 'sysConfig.php';



/*********************** RESTful Router ****************************/
try 
{
	$logger = $iocContainer->resolve('\Hx\Logger\LoggerInterface');
	
	$httpOutput = $iocContainer->resolve('\Hx\Http\Output\Json');
	
	if ($recipe->getIsMaintenance())
	{
		$httpOutput->generateOuput(
			503,
			array('msg' => "System under maintenance", 'code' => -1)
		);
	}
	
	$router = $iocContainer->resolve("\Hx\Route\RestRouter");
	
	$router->run();
}
catch(\Hx\Route\RestRouterException $ex)
{
	//unexpected error ocurred, need to fix ASAP

	$prevEx = $ex->getPrevious();
	if(!empty($prevEx))
		$logger->error(
			$prevEx->getMessage(),
			array(
				'filepath' => $prevEx->getFile(),
				'linecode' => $prevEx->getLine())
		);
	
	$logger->error(
		$ex->getMessage(),
		array(
			'filepath' => $ex->getFile(),
			'linecode' => $ex->getLine()
		)
	);
	
	switch($ex->getErrorType())
	{
		case \Hx\Route\RestRouterException::INPUT_ERROR: //delivery mechanism
			$httpOutput->generateOutput(
				500,
				array(
					'msg' => "Invalid request info: " . $ex->getMessage(), 
					'code' => $ex->getCode())
			);
			break;
			
		case \Hx\Route\RestRouterException::MSG_ERROR: //application pipeline
			$httpOutput->generateOutput(
				500,
				array(
					'msg' => date('Y-m-d H:s:i') . 
						"System encountered input error," . 
						" kindly contact system administrator to solve issue.", 
					'code' => $ex->getCode())
			);
			break;
			
		case \Hx\Route\RestRouterException::DOMAIN_ERROR: //business logic
			$httpOutput->generateOutput(
				406,
				array(
					'msg' => $ex->getMessage(), 
					'code' => $ex->getCode())
			);
			break;

		case \Hx\Route\RestRouterException::OUTPUT_ERROR: //delivery mechnism
			$httpOutput->generateOutput(
				500,
				array(
					'msg' => date('Y-m-d H:s:i') .
						" System encountered ouput error," .
						" kindly contact system administrator to solve issue.", 
					'code' => $ex->getCode())
			);
			break;
				
		default:
			$httpOutput->generateOutput(
				500,
				array(
					'msg' => date('Y-m-d H:s:i') .
						"Unknown routing error code catched: {$ex->getErrorType()}", 
					'code' => $ex->getCode())
			);
			break; //something wrong with the code...
	}
}
catch(\Exception $ex)
{
	$prevEx = $ex->getPrevious();
	
	if(!empty($prevEx))
		$logger->error(
			$prevEx->getMessage(),
			array(
				'filepath' => $prevEx->getFile(),
				'linecode' => $prevEx->getLine())
		);
	
	$logger->error(
		$ex->getMessage(),
		array(
			'filepath' => $ex->getFile(),
			'linecode' => $ex->getLine())
	);
	
	$httpOutput->generateOutput(
		500,
		array(
			'msg' => 
				date('Y-m-d H:s:i') . ' System encounter internal runtime error. ' . 
				'kindly contact system administrator to solve the issue.', 
			'code' => $ex->getCode()
		)
	);
}

?>