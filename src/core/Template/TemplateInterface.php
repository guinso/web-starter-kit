<?php 
namespace Hx\Template;

interface TemplateInterface {
	/**
	 * Generate content based on array data and template file
	 * @param 	array 	$data				data of reference, value must primitive type only
	 * @param 	string 	$templateFilePath	file path of the template file
	 * @return 	string	content with associate with injected data
	 */
	public function generate(Array $data, $templateFilePath);
	
	/**
	 * Generate content based on array data and template string
	 * @param array 	$data		data of reference, value must primitive type only
	 * @param string 	$template	template content, in text
	 * @return 	string	content with associate with injected data
	 */
	public function generateFromString(Array $data, $template);
}
?>