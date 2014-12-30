<?php 

if(IgConfig::getConfig('deploy')) {
?>
	<script src='deploy/app.min.js'></script>
<?php
} else {
	define('WEB_DIR', dirname(__FILE__));
	
	function rerJS($dir) {
		$dirHandle = opendir($dir);
	
		while(false !== ($file = readdir($dirHandle)) ) {
	
			if($file != '.svn' && $file != '.' && $file != '..') {
				$tmpDir = $dir . '/' . $file;
	
				if(is_dir($tmpDir . '/')) {
					rerJS($tmpDir);
				} else {
					//check file is JS
					if(preg_match('/.+\.js$/', $tmpDir)) {
						$subStr = substr($tmpDir, strlen(WEB_DIR) + 1);
						echo "<script src=\"$subStr\"></script>";
					}
				}
			}
		}
	}
	
	rerJS(WEB_DIR . '/partials');
	rerJS(WEB_DIR . '/modules');
}
?>