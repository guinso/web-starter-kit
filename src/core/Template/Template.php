<?php 
namespace Hx\Template;

class Template implements TemplateInterface {
	private $templateEngine;
	
	public function __construct(EngineInterface $templateEngine) 
	{
		$this->templateEngine = $templateEngine;
	}
	
	public function generate(Array $data, $templateFilePath)
	{
		return $this->templateEngine
			->transform($data, $templateFilePath);
	}

	public function generateFromString(Array $data, $template)
	{
		return $this->templateEngine
			->transformInMemory($data, $template);
	}
	
}
?>