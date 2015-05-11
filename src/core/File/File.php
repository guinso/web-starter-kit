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
	
	public function deleteFile($filePath)
	{
		if(!file_exists($filePath))
			Throw new \Hx\Exception\NotAccessibleException(
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
				$tmpDir = $baseDir . '/' . $file;
	
				if (is_dir($tmpDir . '/')) {
					self::recursiveDir($tmpDir, $closure, $filePattern, $ignoreDir);
				} else {
					//check file path's pattern via regular expression
					if(mb_ereg_match($filePattern, $file)) {
						$closure($tmpDir);
					}
				}
			}
	
			$keepLoop = !empty($file);
		}
	}
}
?>