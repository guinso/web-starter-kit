<?php 
namespace Hx\Soap\Client\Adaptor;

class SoapClient implements \Hx\Soap\Client\AdaptorInterface {
	private $soapClient;
	
	public function __construct($url, Array $options) {
		try {
			$x = array_merge(
				array(
					'trace' => true,
					'exceptions' => true,
					'connection_timeout' => 15
				),
				$options);
			
			$this->soapClient = new \SoapClient($url, $x);
				
		} catch(\SoapFault $fault) {
			throw new \Hx\Soap\Client\Exception(
					$functionName,
					"SoapClient Adaptor constructor fault:- " . $fault->getMessage(),
					$fault->getCode());
		} catch (\Exception $ex) {
			throw new \Hx\Soap\Client\Exception(
					$functionName,
					"Unknown SoapClient Adaptor exception encountered on constructor.",
					$ex->getCode(),
					$ex
			);
		}
	}
	
	public function sent($functionName, Array $parameters)
	{
		$param = $this->_processParam($parameters);
		
		try {
				
			$result = $this->soapClient->__soapCall(
				$functionName, 
				array('parameters' => $param));
			
			if($result) {
				$resultCallback = $functionName . 'Result';
				$x = $result->$resultCallback;
				
				return $x;
			} else {
				throw new \Hx\Soap\Client\Exception($functionName, "Fail to send request $functionName");
			}
			
		} catch(\SoapFault $fault) {
			throw new \Hx\Soap\Client\Exception(
				$functionName, 
				"SoapClient Adaptor sent request fault:- " . $fault->getMessage(),
				$fault->getCode());
		} catch (\Exception $ex) {
			throw new \Hx\Soap\Client\Exception(
				$functionName, 
				"Unknown SoapClient Adaptor exception encountered during sent request.",
				$ex->getCode(),
				$ex
			);
		}
	}
	
	private function _processParam(Array $params)
	{
		$x = array();
		
		foreach($params as $key => $p)
		{
			if($p instanceof \Hx\Soap\Client\ParamInterface)
				$x[$p->getName()] = $this->_handleParameter($p);
			else
				Throw new \Hx\Soap\Client\ParamException($key,
					"Index of '$key' is not instance of type \Hx\Soap\Client\ParamInterface.");
		}
		
		return $x;
	}
	
	private function _handleParameter(\Hx\Soap\Client\ParamInterface $param)
	{
		$opt = $param->getConfig();
	
		if(is_array($opt) &&
				array_key_exists('rawXml', $opt) &&
				$opt['rawXml'] == true)
		{
			$var = new \SoapVar($param->getValue(), XSD_ANYXML, null, null, null);
				
			return $var;
		} else {
			return $param->getValue();
		}
	}
}
?>