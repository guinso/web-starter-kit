<?php 
namespace Hx\Web\Formatter;

class Text implements \Hx\Web\FormatterInterface {
	private $value;
	
	private $optons;

	public function __construct(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function getHeader() 
	{
		$filePath = $this->value['filePath'];
		$fileName = $this->value['fileName'];
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$finfo_mime = finfo_file($finfo, $filePath);
		
		return array(
			'Content-Type' => $finfo_mime,
			'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
			'Content-Length' => filesize($filePath),
			'Pragma' => 'public',
			'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
		);
	}

	public function setValue(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function generateOuput()
	{
		$filePath = $this->value['filePath'];
		
		readfile($filePath);
	}
}
?>