<?php 
class JsCompact {
	
	public static function minimizeJs() {
		$destFile = \Ig\Config\Loader::getRootPath() . DIRECTORY_SEPARATOR . 
					'deploy' . DIRECTORY_SEPARATOR . 'app.min.js';
		
		file_put_contents($destFile, '');
		self::compact(\Ig\Config\Loader::getRootPath() . DIRECTORY_SEPARATOR . 'partials', $destFile, true);
		self::compact(\Ig\Config\Loader::getRootPath() . DIRECTORY_SEPARATOR . 'modules', $destFile, true);
	}
	
	public static function compact($directory, $destFile, $append = false) {
		if(!$append)
			file_put_contents($destFile, '');
		
		\Ig\Util::recursiveDir(
			$directory,
			function($filePath) use($destFile){
				if($filePath != $destFile) {
					$js = file_get_contents($filePath);
					$min = \JShrink\Minifier::minify($js);
					file_put_contents($destFile, $min, FILE_APPEND);
				}
			},
			'/^.+\.js$/');
	}
}
?>