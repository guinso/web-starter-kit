<?php 
namespace Hx\Access;

interface AccessInterface {
	/**
	 * Check user weather have rights to access or not
	 * @param 	string $accessName	accsss name
	 * @param 	string $userId		user id, format depends on system design
	 */
	public function isAuthorize($accessName, $userId);
	
	/**
	 * Get all access list based on targeted user ID
	 * @param 	string 	$userId
	 * @param	array	list of access list with format <string, boolean>
	 */
	public function getAccessList($userId);
}
?>