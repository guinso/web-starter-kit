<?php 
namespace HxExtra\Template\Engine;

/**
 * Implement Rain\Tpl template engine library (adaptor)
 * @author chingchetsiang
 *
 */
class RainTpl implements \Hx\Template\EngineInterface {
	
	private $rainTpl;
	
	public function __construct($cachePath, $debug = false) 
	{
		$this->rainTpl = new \Rain\Tpl();
		
		$this->rainTpl->configure(array(
			"cache_dir"	=> $cachePath,
			'debug' => $debug
		));
	}
	
	public function transform(Array $data, $template) 
	{
		//create the Tpl object
		$this->rainTpl->configure(array(
			'tpl_ext' => pathinfo($template, PATHINFO_EXTENSION),
			'tpl_dir' => dirname($template) . DIRECTORY_SEPARATOR
		));
		
		$this->rainTpl->assign($data);
		
		return $this->rainTpl->draw(
			basename(
				$template, 
				pathinfo($template, PATHINFO_EXTENSION)), 
			true);
	}
	
	public function transformInMemory(Array $data, $templateContext)
	{
		$this->rainTpl->assign($data);
		
		return $this->rainTpl->drawString($templateContext, true);
	}
	
	public function __destruct()
	{
		$this->rainTpl = null;
	}
}
?>