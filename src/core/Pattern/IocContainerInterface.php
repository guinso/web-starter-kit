<?php 
namespace Hx\Pattern;

use Hx\Pattern\IocContainerInterface;
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
	public function make($className);
	
	/**
	 * Get factory rule
	 * @param String 	$className		targeted class name
	 */
	public function getRule($className);
	
	/**
	 * Set new rule into Ioc container
	 * @param String 						$className	targeted class name
	 * @param IocContainer\RuleInterface 	$rule		Ioc rule
	 */
	public function setRule($className, IocContainer\RuleInterface $rule);
	
	/**
	 * Remove Ioc container rule
	 * @param String 	$className		targeted class name
	 */
	public function removeRule($className);
}
?>