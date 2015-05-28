<?php 
class SoapSample {
	public static function runWcf()
	{
		try {
			/*
			//Load raw mock SOAP request data for wcf format
			$ds = file_get_contents(
			\Ig\Config::getProfile()->absTemplatePath .
			DIRECTORY_SEPARATOR . 'FireOrder.tpl');
			 
			//Start to initiate WCF service from host http://192.168.56.101
			$adaptor = new \Hx\Soap\Client\Adaptor\SoapClient(
				"http://192.168.56.101/testService/Service1.svc?wsdl",
				[]);
			
			//Start send command to end point -FireOrder- 
			$soap = new \Hx\Soap\Client\Handler($adaptor);
			
			$x = $soap->sent(
				'FireOrder', 
				[
					new \Hx\Soap\Client\Param('ds', $ds, array('rawXml' => true)),
					new \Hx\Soap\Client\Param('datetime', "2015-04-22T18:53:04")
				], 
				[]
			);
			
			return $x;
			*/
			
			//"http://192.168.56.101/testService/Service1.svc?wsdl"
			
			
			
			//FP style.... (almost)
			return (new \Hx\Soap\Client\Handler(
				new \Hx\Soap\Client\Adaptor\SoapClient(
					'http://192.168.56.101/testService/Service1.svc?wsdl',
					[
						'cache_wsdl' => WSDL_CACHE_NONE	
					]
				)
			))
			->sent(
				'FireOrder', 
				[
					new \Hx\Soap\Client\Param(
						'ds', 
						file_get_contents(
							__DIR__ . DIRECTORY_SEPARATOR . 'FireOrder.tpl'
						), 
						['rawXml' => true]
					),
					new \Hx\Soap\Client\Param(
						'datetime', 
						"2015-04-22T18:53:04"
					)
				], 
				[]
			);
			
		} 
		catch(\Hx\Exception\SoapException $ex) {
			//handle typical SOAP connection exception
			echo $ex->getMessage();
		} 
		catch(\Exception $ex) {
			//handle unexpected exception
			echo "unexpected SOAP operation:- " . $ex->getMessage();
		}
	}
}
?>