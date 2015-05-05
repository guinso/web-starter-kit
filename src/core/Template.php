<?php 
namespace Hx;

class Template implements Template\TemplateInterface {
	private $templateEngine;
	
	public function __construct(Template\EngineInterface $templateEngine) 
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