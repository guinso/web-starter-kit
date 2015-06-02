<?php 
namespace Hx\Soap\Client\Adaptor;

class SoapClient implements \Hx\Soap\Client\AdaptorInterface {
	private $soapClient;
	
	public function __construct($url, Array $options) {
		try {
			$this->soapClient = new \SoapClient(
				$url,
				array_merge(
					[
						'trace' => true,
						'exceptions' => true,
						'connection_timeout' => 15
					],
					$options
				)
			);
				
		} catch(\SoapFault $fault) {
			throw new \Hx\Soap\SoapException(
				"Fail to instantiate SOAP client :- " . $fault->getMessage(),
				$fault->getCode(),
				$fault);
		}
	}
	
	public function sent($functionName, Array $parameters)
	{
		try {
				
			return $this->_handleSent(
				$this->soapClient->__soapCall(
					$functionName, 
					['parameters' => $this->_processParams($parameters)]
				), 
				$functionName
			);
		} 
		catch (\SoapFault $fault) 
		{
			throw new \Hx\Soap\SoapException(
				"SOAP client fail to send on function <$functionName> :- " . $fault->getMessage(),
				$fault->getCode(),
				$fault);
		}
	}
	
	private function _handleSent($result, $functionName)
	{
		if($result)
		{
			return $result->{$functionName . 'Result'};
		}
		else 
		{
			throw new \Hx\Soap\SoapException(
				"Fail to send SOAP request on function <$functionName>");
		}
	}
	
	private function _processParams(Array $params, $i = 0)
	{
		if ($i < COUNT($params))
			return array_merge(
				$this->_atomProcesParam($params[$i]),
				$this->_processParams($params, $i + 1)
			);
		else
			return [];
	}
	
	private function _atomProcesParam(\Hx\Soap\Client\ParamInterface $p)
	{
		return array($p->getName() =>
			is_array($p->getConfig() && 
			array_key_exists('rawXml', $p->getConfig()) && 
			$p->getConfig()['rawXml'] == true)  ?
				
				new \SoapVar($p->getValue(), XSD_ANYXML, null, null, null) :
				
				$p->getValue()
		);
	}
	
	public function __destruct()
	{
		$this->soapClient = null;
	}
}
?>