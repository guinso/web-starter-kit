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

function rerHtml($dir, $parentDir) {
	$dirHandle = opendir($dir);

	while(false !== ($file = readdir($dirHandle)) ) {

		if($file != '.svn' && $file != '.' && $file != '..') {
			$tmpDir = $dir . '/' . $file;

			if(is_dir($tmpDir . '/')) {
				rerHtml($tmpDir, $parentDir);
			} else {
				//check file is html
				if(preg_match('/.+\.html$/', $tmpDir)) {
					$subStr = substr($tmpDir, strlen($parentDir) + 1);
					
					echo "arr.push((function(){
							var d = \$q.defer();
							\$http.get('$subStr', {cache: \$templateCache}).then(function(data){d.resolve(data);});
							return d.promise;})());\n";
				}
			}
		}
	}
}

function cacheHtml() {
	echo "\n<script type=\"text/javascript\">angular.module('MyApp').run([
		\"\$templateCache\", \"\$http\", \"\$route\", \"\$q\", 
		function(\$templateCache, \$http, \$route, \$q){ 
			var url;
			var arr = [];\n";

	rerHtml(__DIR__ . '/modules', __DIR__);
	
	rerHtml(__DIR__ . '/partials', __DIR__);
	
	echo "\$q.all(arr).then(function(response){
				$('#loading-desc').html('Loading completed.');
				$('#loading-msg').fadeOut(1500, function(){
					$('#bodyy').fadeIn(500);
				});	
			});
		}]);	
		</script>\n";
}

rerJS(__DIR__ . '/partials', __DIR__);
rerJS(__DIR__ . '/modules', __DIR__);

cacheHtml();
?>