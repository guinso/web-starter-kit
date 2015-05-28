<?php 
namespace Hx\Http\Output;

class Text implements \Hx\Http\OutputInterface {

	private $statusCodeService;
	
	public function __construct(\Hx\Http\StatusCode $statusCodeService)
	{
		$this->statusCodeService = $statusCodeService;
	}
	
	public function generateOuput(Array $data, $statusCode)
	{
		$this->writeHeader($statusCode);
	
		$this->writeBody($data['data']);
	}
	
	private function writeHeader($statusCode) 
	{
		header('HTTP/1.1' . $statusCode . ' ' . 
			$this->statusCodeService->getStatusMessage($statusCode));
		
		header('Content-Type: text/plain; charset=utf-8');
	}
	
	private function writeBody($data)
	{
		echo $data;
	}
}
?>