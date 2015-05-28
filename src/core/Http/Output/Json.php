<?php 
namespace Hx\Http\Output;

class Json implements \Hx\Http\OutputInterface {

	private $statusCodeService;
	
	public function __construct(\Hx\Http\StatusCode $statusCodeService)
	{
		$this->statusCodeService = $statusCodeService;
	}
	
	public function generateOuput(Array $data, $statusCode)
	{
		$this->writeHeader($statusCode);
	
		$this->writeBody($data);
	}
	
	private function writeHeader($statusCode) 
	{
		header('HTTP/1.1' . $statusCode . ' ' . 
			$this->statusCodeService->getStatusMessage($statusCode));
		
		header('Content-Type: application/json; charset=utf-8');
	}
	
	private function writeBody($data)
	{
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
}
?>