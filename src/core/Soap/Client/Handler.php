<?php 
namespace Hx\Soap\Client;

class Handler implements HandlerInterface {
	protected $adaptor;
	
	public function __construct(AdaptorInterface $adaptor)
	{
		$this->adaptor = $adaptor;	
	}
	
	public function sent($functionName, Array $param, Array $options = null)
	{
		return $this->adaptor->sent($functionName, $param);
	}
}
?>