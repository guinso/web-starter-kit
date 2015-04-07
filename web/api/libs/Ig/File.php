<?php 
namespace Ig;

class File {
	
	/**
	 * Check provided directory exists or not, create if allowed
	 * @param string $directory
	 * @param boolean $create	set to true if want to create if not found
	 * @return boolean
	 */
	public static function checkDirectory($directory, $create = true) 
	{
		$found = false;
		
		if (file_exists($directory)) {
			$found = true;
		}
		elseif (!file_exists($directory) && $create) {
			mkdir($directory, 0775, true);
			$found = true;
		}
		
		return $found;
	}
	
	/**
	 * Send file to client for downloading purposes
	 * @param String $filepath	absolute file path
	 * @param String $filename	return file name
	 * @param Boolean $compress	compress file before download flag
	 */
	public static function getFile($filepath, $filename, $compress = false) 
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$finfo_mime = finfo_file($finfo, $filepath);
	
		header('Content-Type: '. $finfo_mime);
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Content-Length: ' . filesize($filepath));
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	
		//TODO support resumable features
	
		//TODO support compress file before sending download
	
		readfile($filepath);
	}
	
	/**
	 * Remove file or directory recursively
	 * @param string $dir	directory path OR file path
	 */
	public static function removeFile($dir) 
	{
		if (is_file($dir)) {
			unlink($dir);
		}
		elseif (is_dir($dir)) {
			foreach (glob($dir . '/*') as $file)
				self::removeFile($file);
				
			rmdir($dir);
		}
	}
}
?>