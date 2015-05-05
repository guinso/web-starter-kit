<?php 
namespace Hx\Web\Formatter;

class Json implements \Hx\Web\FormatterInterface {
	private $value;
	
	private $optons;

	public function __construct(Array $value, Array $option)
	{
		$this->value = $value;
	
		$this->optons = $option;
	}
	
	public function getHeader() 
	{
		return array(
			'Content-Type' => 'application/json; charset=utf-8',
		);
	}
	
	public function setValue(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function generateOuput()
	{
		echo json_encode($this->value, JSON_UNESCAPED_UNICODE);
	}
}
?>