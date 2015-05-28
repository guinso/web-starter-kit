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
		$fileName = $data['fileName'];
	
		$filePath = $data['filePath'];
	
		$this->writeHeader($statusCode, $fileName, $filePath);
	
		$this->writeBody($filePath);
	}
	
	private function writeHeader($statusCode, $fileName, $filePath) 
	{
		header('HTTP/1.1' . $statusCode . ' ' . 
			$this->statusCodeService->getStatusMessage($statusCode));
		
		header('Content-Type: ' . finfo_file(
				finfo_open(FILEINFO_MIME_TYPE), 
				$filePath));
		
		header('Content-Disposition: ' . 'attachment;filename="' . $fileName . '"');
		
		header('Content-Length: ' . filesize($this->value['filePath']));
		
		header('Pragma: public');
		
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	}
	
	private function writeBody($filePath)
	{
		readfile($filePath);
	}
}
?>