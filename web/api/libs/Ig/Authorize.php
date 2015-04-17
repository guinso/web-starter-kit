<?php 
namespace Ig;

/**
 * Authorization Utility
 * @author chingchetsiang
 *
 */
class Authorize {
		
	/**
	 * Determine current login user is authorize to targeted access(s)
	 * @param array $functioNames
	 * @return boolean
	 */
	public static function isAuthorize2($functionNames) 
	{
		$user = \Ig\Login::getCurrentUser();
		
		return self::_isAuthorizeByUser($user['userId'], $functionNames);
	}
	
	public static function isAuthorize() 
	{
		$functionNames = func_get_args();
		$user = \Ig\Login::getCurrentUser();
		
		return self::_isAuthorizeByUser($user['userId'], $functionNames);
	}
	
	public static function isAuthorizeByUser($userId, $functionNames) 
	{
		return self::_isAuthorizeByUser($userId, $functionNames);
	}
	
	private static function _isAuthorizeByUser($userId, $functionNames) 
	{
		$db = \Ig\Db::getDb();
		$pdo = \Ig\Db::getPDO();
		$result = false;

		$params = array();
		$cnt = 0;
		$x = array();

		foreach ($functionNames as $fn) {
			$x[] = ':f' . $cnt;
			$params[':f' . $cnt] = $fn;
			$cnt++;
		}

		if ($cnt > 0) {
			$user = $db->account[$userId];
			$role = $db->role[$user['role_id']];
	
			$params[':r'] = $role['id'];
			$inQuery = implode(',', $x);
	
			$sql = "SELECT a.* FROM access a
					JOIN function b ON a.function_id = b.id ".
						"WHERE 	 b.name IN (".$inQuery.") AND " .
						"a.role_id = :r AND " .
						"a.authorize = 1";
	
			$stmt = $pdo->prepare($sql);
			$stmt->execute($params);
	
			$result = $stmt->rowCount() > 0;
		}
	
		return $result;
	}
	
	public static function getAuthorizeUser2($functionNames) 
	{
		return self::_getAuthorizeUser($functionNames);
	}
	
	/**
	 * Get list of user(s) who eligible to such access title(s)
	 * @param	functionNames	list of function names
	 */
	public static function getAuthorizeUser() 
	{
		$functionNames = func_get_args();
		
		return self::_getAuthorizeUser($functionNames);
	}
	
	private static function _getAuthorizeUser($functionNames) 
	{
		$pdo = \Ig\Db::getPDO();
		
		$params = array();
		$cnt = 0;
		$x = array();
		
		foreach ($functionNames as $fn) {
			$x[] = ':f' . $cnt;
			$params[':f' . $cnt] = $fn;
			$cnt++;
		}
		
		$inQuery = implode(',', $x);
		$sql = "SELECT b.* FROM access a
			JOIN account b ON a.role_id = b.role_id
			JOIN function c ON a.function_id = c.id
			WHERE 	c.name IN (".$inQuery.") AND
					a.authorize = 1
			GROUP BY b.id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);
		
		$result = array();
		foreach ($stmt as $row) {
			$result[] = self::_getUserFormat($row);
		}
		
		return $result;
	}
	
	public static function getAuthorizeRole2($functionNames)
	{
		return self::_getAuthorizeRole($functionNames);
	}
	
	public static function getAuthorizeRole()
	{
		$functionNames = func_get_args();
	
		return self::_getAuthorizeRole($functionNames);
	}
	
	private static function _getAuthorizeRole($functionNames)
	{
		$pdo = \Ig\Db::getPDO();
	
		$params = array();
		$cnt = 0;
		$x = array();
	
		foreach ($functionNames as $fn) {
			$x[] = ':f' . $cnt;
			$params[':f' . $cnt] = $fn;
			$cnt++;
		}
	
		$inQuery = implode(',', $x);
		$sql = "SELECT a.* FROM access a
			JOIN function c ON a.function_id = c.id
			WHERE 	c.name IN (".$inQuery.") AND
					a.authorize = 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);
	
		$result = array();
		foreach ($stmt as $row) {
			$result[] = $row['role_id'];
		}
	
		return $result;
	}
	
	private static function _getUserFormat($row) 
	{
		return array(
			'id' => $row['id'],
			'name' => $row['name'],
			'email' => $row['email'],
			'roleId' => $row['roleId']
		);
	}
}
?>