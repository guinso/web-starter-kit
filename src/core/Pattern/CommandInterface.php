<?php 
namespace Hx\Design;

/**
 * Implement command design pattern
 * @author chingchetsiang
 *
 */
interface CommandInterface {
	public function execute();
	
	public function undo();
	
	public function estimateExecTime();
}
?>