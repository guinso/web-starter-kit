<?php 
namespace Hx\Pattern\IocContainer;

/**
 * Rule interface class for Ioc container
 * Rule is apply in top-to-bottom fahsion
 * If no matching sub-rule found, general rule in Ioc container will choose instead
 * @author chingchetsiang
 *
 */
interface RuleInterface {
	
	/**
	 * Get service state
	 * @return boolean
	 */
	public function getIsService();
	
	/**
	 * Set service state 
	 * @param boolean 	$isService	is singleton or replicate instance
	 */
	public function setIsSerivce($isService);
	
	/**
	 * Set injected parameter(s)
	 * @param array 	$params		number based index array, only allow primitive value to inject
	 */
	public function setParam(Array $params);
	
	/**
	 * Add sub-rule which only apply to this domain only
	 * @param RuleInterface 	$subRule	sub rule for this rule domain
	 */
	public function addSubRule(RuleInterface $subRule);
}
?>