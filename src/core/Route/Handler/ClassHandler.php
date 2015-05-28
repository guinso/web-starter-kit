<?php 
namespace Hx\Route\Handler;

class ClassHandler implements \Hx\Route\HandlerInterface {
	
	private $iocContainer;
	
	public function __construct(
		\Hx\IocContainer\IocContainerInterface $iocContainer)
	{
		$this->iocContainer = $iocContainer;
	}

	public function handle(\Hx\Route\MatchInterface $match, Array $data)
	{
		if ($match->isStaticCall())
		{
			call_user_func(
				(empty($match->getClassName())? '' : $match->getClassName() . '::') . 
					$match->getFunctionName(), 
				new \Hx\Route\InputParam\SimpleInputParam(
					$data['data'], 
					$match->getArgs()
				)
			);
		}
		else 
		{
			$this->iocContainer->make($match->getClassName())->
				{$match->getFunctionName()}(
					new \Hx\Route\InputParam\SimpleInputParam(
						$data['data'], 
						$match->getArgs()
					)
				);
		}
	}
}
?>