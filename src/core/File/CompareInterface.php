<?php 
namespace Hx\File;

interface CompareInterface {
	
	/**
	 * Compare two directory to calculate it's differences
	 * @param string $baseDir		base directory
	 * @param string $compareDir	directory use as comparator
	 */
	public function compare($baseDir, $compareDir);
}
?>