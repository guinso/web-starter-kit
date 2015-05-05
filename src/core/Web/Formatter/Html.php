<?php 
namespace Hx\Web\Formatter;

class Html implements \Hx\Web\FormatterInterface {
	private $value;
	
	private $optons;

	public function __construct(Array $value, Array $option)
	{
		$this->value = array_merge(
			array('head' => array(
				'meta' => array(
					'value' => '',
					'attr' => array(
						'http-equiv' => 'Content-Type',
						'content' => 'text/html; charset=utf-8'
					)
				)
			)), 
			$value);
	
		$this->optons = $option;
	}
	
	public function getHeader()
	{
		return array(
			'Content-Type' => 'text/html; charset=utf-8',
		);
	}
	
	public function setValue(Array $value, Array $option)
	{
		$this->value = $value;
		
		$this->optons = $option;
	}
	
	public function generateOuput()
	{
		$header = '';
		if(is_array($this->value['head'])) {
			foreach($this->value['head'] as $key => $val) {
				$value = $val['value'];
				$tag = $key;
				$attr = '';
				
				if(is_array($val['attr'])) {
					foreach($val['attr'] as $k => $v) 
						$attr .= "$k=\"$v\"";
				}
				
				$header .= "<$tag $attr>$value</$tag>";
			}
		}
		
		// this should be templatized in a real-world solution
		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' .
					$header .	
				'</head>' .
				$this->value['body'] .
			'</html>';
		
		echo $body;
	}
	
	private function getServerSignature() {
		return ($_SERVER['SERVER_SIGNATURE'] == '') ? 
			$_SERVER['SERVER_SOFTWARE'] . ' Server at ' . 
				$_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : 
			$_SERVER['SERVER_SIGNATURE'];
	}
}
?>