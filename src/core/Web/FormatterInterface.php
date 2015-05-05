<?php 
namespace Hx\Web;

interface FormatterInterface {
	public function getHeader();
	
	public function setValue(Array $value, Array $option);
	
	public function generateOuput();
}
?>