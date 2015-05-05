<?php 
namespace Hx\Web\Formatter;

class Text implements \Hx\Web\FormatterInterface {
	private $value;
	
	private $optons;

	public function __construct(Array $value, Array $option)
	{
		$this->value = array();

		$this->optons = array_merge(
			array('newline' => true), 
			$option);
	}
	
	public function getHeader()
	{
		return array(
				'Content-Type' => 'text/plain; charset=utf-8',
		);
	}
	
	public function setValue(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function generateOuput()
	{
		$newline = '';
		
		if(array_key_exists('newline', $this->optons) && 
			$this->optons['newline'] == true)
		{
			$newline = '\r\n';
		}
		
		foreach($this->value as $x)
		{
			echo $x . $newline;
		}
	}
}
?>