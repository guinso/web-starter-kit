<?php 
namespace Hx\IocContainer;

/**
 * Rule interface class for Ioc container
 * Rule is apply in top-to-bottom fahsion
 * If no matching sub-rule found, general rule in Ioc container will choose instead
 * @author chingchetsiang
 *
 */
interface RuleInterface {
	
	/**
	 * Get targeted class name
	 */
	public function getClassName();
	
	/**
	 * Get class name use to instantiate targeted class
	 */
	public function getInstanceClassName();
	
	/**
	 * Get closure to generate object instance
	 */
	public function getClosure();
	
	/**
	 * Check whether object stantiation is overrriden by closure
	 */
	public function isCodeOverride();
	
	/**
	 * Get service state
	 * @return boolean
	 */
	public function isService();

	/**
	 * Get primitive arguments
	 */
	public function getPrimitiveArgs();

	/**
	 * Get children rules
	 * @return	array	array of \Hx\IocContainer\RuleInterface
	 */
	public function getSubRules();
	
	/**
	 * Get service instance
	 * non service will throw exception
	 */
	public function getServiceInstance();
	
	/**
	 * Set service instance
	 * only rule is service type and haven't assign is allowable
	 * @param unknown $object
	 */
	public function setServiceInstance($object);
}
?>