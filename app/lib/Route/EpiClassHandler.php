<?php 
namespace Starter\Route;

class EpiClassHandler implements \Hx\Route\HandlerInterface {
	
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
			return call_user_func_array(
				(empty($match->getClassName())? '' : $match->getClassName() . '::') . 
					$match->getFunctionName(), 
				$match->getArgs()
			);
		}
		else 
		{
			return $this->iocContainer->make($match->getClassName())->
				{$match->getFunctionName()}($match->getArgs());
		}
	}
}
?>