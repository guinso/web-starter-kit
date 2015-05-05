<?php 
namespace Hx\File;

interface FileInterface {
	public function getDirectories($directory);
	
	public function deleteFile($filePath);
}
?>