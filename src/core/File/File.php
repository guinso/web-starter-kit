<?php 
namespace Hx\File;

class File implements FileInterface {
	
	public function __construct()
	{
		
	}
	
	public function getDirectories($directory)
	{
		$files = array();
		$dirs = array();
		
		$dir_iterator = new \RecursiveDirectoryIterator($directory);
		$dir_iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
		
		$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
		// could use CHILD_FIRST if you so wish
		
		//SplFileInfo class
		foreach ($iterator as $file) {
			$filename = $file->getFilename();
			$filteredFilepath = str_replace($directory, '', $file);
				
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
	
	public function deleteFile($filePath)
	{
		if(!file_exists($filePath))
			Throw new \Hx\Exception\FileException(
					"Targeted delete file <$filePath> not found.");
			
		unlink($filePath);
	}
	
	public function recursiveDir(
			$baseDir,
			$closure,
			$filePattern = '/.+/',
			$ignoreDir = array('.svn', '.', '..')
	) {
		$dirHandle = opendir($baseDir);
		$keepLoop = true;
	
		while ($keepLoop) {
	
			$file = readdir($dirHandle);
	
			$isIgnoreDir = false;
			foreach ($ignoreDir as $igr) {
				if ($file == $igr) {
					$isIgnoreDir = true;
				}
			}
	
			if (!$isIgnoreDir && $file) {

				$tmpDir = $baseDir . DIRECTORY_SEPARATOR . $file;
	
				if (is_dir($tmpDir . DIRECTORY_SEPARATOR)) {
					$this->recursiveDir($tmpDir, $closure, $filePattern, $ignoreDir);
				} else {
					//check file path's pattern via regular expression
					if(preg_match($filePattern, $file)) {
						$closure($tmpDir);
					}
				}
			}
	
			$keepLoop = !empty($file);
		}
	}
}
?>