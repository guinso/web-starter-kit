<?php 
namespace Hx\Route;

class RestRouter implements \Hx\Route\RouterInterface {
	private $mapper, $inputHandler, $outputHandler, $exeHandler, $httpHeadReader;
	
	public function __construct(
		\Hx\Route\MapperInterface $mapper, 
		\Hx\Http\HeaderReaderInterface $headReader,
		\Hx\Http\InputServiceInterface $inputHandler,
		\Hx\Http\OutputServiceInterface $outputHandler,
		\Hx\Route\HandlerInterface $handler)
	{
		$this->mapper = $mapper;
		
		$this->inputHandler = $inputHandler;
		
		$this->outputHandler = $outputHandler;
		
		$this->exeHandler = $handler;
		
		$this->httpHeadReader = $headReader;
	}
	
	public function run()
	{
		//1. handler command
		try 
		{
			$match = $this->mapper->find(
				$this->httpHeadReader->getRequestUri(),
				$this->httpHeadReader->getMethod());
		}
		catch (\Exception $ex)
		{
			Throw new \Hx\Route\RestRouterException(
				RestRouterException::INPUT_ERROR, 
				"Fail to get request path", 
				0, 
				$ex);
		}
		
		//2. handle input data
		try 
		{
			$input = $this->inputHandler->getInput();
		}
		catch (\Exception $ex)
		{
			Throw new \Hx\Route\RestRouterException(
				RestRouterException::INPUT_ERROR,
				"Fail to get user input",
				0,
				$ex);
		}
		
		$output = array();
		
		//3. handle business logic
		try 
		{
			$output = $this->exeHandler->handle(
				$match,
				$input
			);
		}
		catch (\Exception $ex)
		{
			Throw new \Hx\Route\RestRouterException(
				RestRouterException::DOMAIN_ERROR,
				"Fail to process content",
				0,
				$ex);
		}
		
		//4. handler output
		try
		{
			if($match->getOutputFormat() == 'none')
				return;
			else 
			{
				$this->outputHandler->generateOutput(
					$match->getOutputFormat(), 
					$output);
			}
		}
		catch (\Exception $ex)
		{
			Throw new \Hx\Route\RestRouterException(
				RestRouterException::OUTPUT_ERROR,
				"Fail to generate output content",
				0,
				$ex);
		}
	}
}
?>