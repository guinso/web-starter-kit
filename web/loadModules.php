<?php 

function rerJS($dir, $parentDir) {
	$dirHandle = opendir($dir);
	
	while(false !== ($file = readdir($dirHandle)) ) {

		if($file != '.svn' && $file != '.' && $file != '..') {
			$tmpDir = $dir . '/' . $file;

			if(is_dir($tmpDir . '/')) {
				rerJS($tmpDir, $parentDir);
			} else {
				//check file is JS
				if(preg_match('/.+\.js$/', $tmpDir)) {
					$subStr = substr($tmpDir, strlen($parentDir) + 1);
					echo "<script src=\"$subStr\"></script>";
				}
			}
		}
	}
}

rerJS(__DIR__ . '/partials', __DIR__);
rerJS(__DIR__ . '/modules', __DIR__);
?>