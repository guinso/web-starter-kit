<?php 
namespace Hx\Template;

interface EngineInterface {
	
	/**
	 * Transform content
	 * @param 	Array 	$data		data of reference, value must primitive type only
	 * @param 	string 	$template	template content, in text
	 * @return 	string	content with associate with injected data
	 */
	public function transform(Array $data, $template);
	
	/**
	 * Direct transform content from string variable
	 * @param 	array 	$data
	 * @param 	string 	$templateContext
	 */
	public function transformInMemory(Array $data, $templateContext);
}
?>