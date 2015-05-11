<?php 
namespace Hx\Exception;

/**
 * To catch routing exception
 * @author chingchetsiang
 *
 */
class RouterException extends \LogicException {
	
	const PATH_NOT_MATCH = 1;
	const METHOD_NOT_SUPPORT = 2;
	
}
?>