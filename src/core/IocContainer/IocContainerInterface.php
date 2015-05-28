<?php 
namespace Hx\IocContainer;

/**
 * Implement factory design pattern
 * @author chingchetsiang
 *
 */
interface IocContainerInterface {
	/**
	 * make instance of class
	 * @param String 	$className		targeted class name
	 */
	public function resolve($className);
}
?>