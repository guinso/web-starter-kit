<?php 
namespace Hx\Route;

class RestRouter implements \Hx\Route\RouterInterface {
	private $mapper, $inputHandler, $exeHandler, $httpHeadReader;
	
	public function __construct(
		\Hx\Route\MapperInterface $mapper, 
		\Hx\Http\HeaderReaderInterface $headReader,
		\Hx\Http\InputServiceInterface $input,
		\Hx\Route\HandlerInterface $handler)
	{
		$this->mapper = $mapper;
		
		$this->inputHandler = $input;
		
		$this->exeHandler = $handler;
		
		$this->httpHeadReader = $headReader;
	}
	
	public function run()
	{
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
		
		try 
		{
			$this->exeHandler->handle(
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
	}
}
?>