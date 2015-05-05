<?php 
namespace Hx\Excel;

interface ExcelInterface {
	public function load($filePath);
	
	public function save($obj, $filePath, Array $options);
}
?>