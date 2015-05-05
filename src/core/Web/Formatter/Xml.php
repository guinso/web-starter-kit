<?php 
namespace Hx\Web\Formatter;

class Xml implements \Hx\Web\FormatterInterface {
	private $value;
	
	private $optons;

	public function __construct(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = array_merge(
			array('encoding' => 'utf-8'), 
			$option);
	}
	
	public function getHeader()
	{
		return array(
			'Content-Type' => 'text/xml; charset=utf-8',
		);
	}
	
	public function setValue(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function generateOuput()
	{
		echo '<? xml version="1.0" encoding="' . $this->optons['encoding'] . '" ?>';
		
		self::_writeMarkup($this->value['data']);
	}
	
	//XXX use SimpleXmlElement instead to support attribute
	private function _writeMarkup(&$data)
	{
		foreach($data as $key => $value)
		{
			if(is_array($value))
			{
				echo "<$key>" . self::_writeMarkup($data[$key]) . "</$key>";
			} else 
			{
				echo "<$key>" . $value . "</$key>";
			}
		}
	}
}
?>