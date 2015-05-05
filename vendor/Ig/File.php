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
	
	/**
	 * 
	 * @param string $path
	 */
	public static function getDirectories($path) {
		$files = array();
		$dirs = array();
		
		$dir_iterator = new \RecursiveDirectoryIterator($path);
		$dir_iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
		
		$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
		// could use CHILD_FIRST if you so wish
		
		//SplFileInfo class
		foreach ($iterator as $file) {
			$filename = $file->getFilename();
			$filteredFilepath = str_replace($path, '', $file);
			
			if ($file->isDir()) {
				$dirs[] = $filteredFilepath;
			} elseif($file->isFile()) {
				$files[] = $filteredFilepath;
			}
		}
		
		return array(
			'files' => $files,
			'dirs' => $dirs
		);
	}
	
	public static function compareDir($dirBase, $dirNew) {
		$base = self::getDirectories($dirBase);
		$new = self::getDirectories($dirNew);
		
		$delDirs = array_diff($base['dirs'], $new['dirs']);
		$addDirs = array_diff($new['dirs'], $base['dirs']);
		
		$delFiles = array_diff($base['files'], $new['files']);
		$addFiles = array_diff($new['files'], $base['files']);
		
		$remainFiles = array_intersect($base['files'], $new['files']);
		
		$overwriteFiles = array();
		foreach($remainFiles as $r) {
			$newFile = $dirNew . DIRECTORY_SEPARATOR . $r;
			$baseFile = $dirBase . DIRECTORY_SEPARATOR . $r;
			
			$mdfBase = md5_file($baseFile);
			$mdfNew = md5_file($newFile); 
			
			if($mdfBase === false)
				Throw new \Exception('Compare file failed:- can read file <' . 
					$dirBase . DIRECTORY_SEPARATOR . $r);
			
			if($mdfNew === false)
				Throw new \Exception('Compare file failed:- can read file <' . 
					$dirNew . DIRECTORY_SEPARATOR . $r);
			
			if($mdfBase != $mdfNew || filesize($newFile) != filesize($baseFile))
				$overwriteFiles[] = $r;
		}
		
		return array(
			'addDirs' => $addDirs,
			'deleteDirs' => $delDirs,
			'addFiles' => $addFiles,
			'deleteFiles' => $delFiles,
			'overwriteFiles' => $overwriteFiles
		);
	}
/*	
	public static function writePatch($diff, $newDir) {
		$patch = '';
		
		//TODO create temporary directory
		$tmpPath = \Ig\Config::getProfile()->absTemporaryPath . DIRECTORY_SEPARATOR . uniqid();
		$tmpFsPath = $tmpPath . DIRECTORY_SEPARATOR . 'files';

		if (!mkdir($tmpFsPath, 0777, true))
			Throw new \Exception("Fail to create directory <$tmpFsPath>.");
		
		foreach ($diff['deleteFiles'] as $c) {
			//TODO write delete files script
		}
		
		foreach ($diff['deleteDirs'] as $d) {
			//TODO write delete directory script
		}
		
		foreach ($diff['addDirs'] as $e) {
			//TODO write create directory script
		}
		
		foreach ($diff['addFiles'] as $a) {
			//TODO copy files to files directroy
			
			//TODO write copy file script
		}
		
		foreach ($diff['overwriteFiles'] as $b) {
			//TODO copy files to files directroy
				
			//TODO write overwrite file script
		}
	}
*/
}
?>