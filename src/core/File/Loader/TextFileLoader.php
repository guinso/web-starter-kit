<?php 
namespace Hx\File\Loader;

class TextFileLoader implements \Hx\File\LoaderInterface {
	
	public function load($filePath, Array $option)
	{
		if(!file_exists($source))
			Throw new \Hx\File\FileException("Source file <$source> not found.");
		
		if(!is_readable($source))
			Throw new \Hx\File\FileException("Source file <$source> is not readable");
		
		return file_get_contents($source);
	}
}
?>