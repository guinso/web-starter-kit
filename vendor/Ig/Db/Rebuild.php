<?php 
namespace Ig\Db;

/**
 * Rebuild database
 * @author chingchetsiang
 *
 */
class Rebuild {
	
	/**
	 * Rebuild database
	 * @param NotORM $db
	 * @param string $dbInitial
	 * @param integer $dbLen
	 */
	public static function rebuild() 
	{
		self::clearDatabase();
		
		$dir = dirname(__FILE__);
		
		\Ig\Db::runSqlScript($dir . DIRECTORY_SEPARATOR . 'newDb.sql', \Ig\Db::getPDO());
		
		//insert role
		self::insertRole('anonymous', 1, 0);
		self::insertRole('admin', 1, 1);
		self::insertRole('user', 1, 2);
		
		//insert account
		self::insertAccount('Anonymous', 1, 'anonymous', 'anonymous', '');
		self::insertAccount('Admin', 1, 'admin', 'admin', '1q2w3e');
		self::insertAccount('User', 1, 'user', 'user', '123456789');
		
		//insert function group
		self::insertFuncGroup('Admin', 0);
		
		//insert function
		self::insertFunction('view user', 'Admin', 0);
		self::insertFunction('create user', 'Admin', 1);
		self::insertFunction('update user', 'Admin', 2);
		self::insertFunction('view schedule', 'Admin', 3);
		self::insertFunction('update schedule', 'Admin', 4);
		
		//insert schedule
		self::insertSchedule('EmailUtil', 'runQueue', 'send email', '*', '*', '*', '*', '*/5', 2, 1);
		self::insertSchedule('LoginUtil', 'checkogin', 'check user login activitiy', '*', '*', '*', '*', '*', 2, 1);
	}
	
	private static function insertRole($name, $status, $weight) 
	{
		$db = \Ig\Db::getDb();
		
		$id = \Ig\Db::getNextRunningNumber('role');
		$db->role->insert(array(
			'id' => $id,
			'name' => $name,
			'status' => $status,
			'weight' => $weight
		));
	}
	
	private static function insertAccount($name, $status, $role, $username, $password) 
	{
		$db = \Ig\Db::getDb();
		
		$roleId = self::getRoleId($role);
		
		$id = \Ig\Db::getNextRunningNumber('account');
		$db->account->insert(array(
			'id' => $id,
			'name' => $name,
			'status' => $status,
			'role_id' => $roleId,
			'username' => $username,
			'password' => $password
		));
	}
	
	private static function insertFuncGroup($name, $weight) 
	{
		$db = \Ig\Db::getDb();
		
		$id = \Ig\Db::getNextRunningNumber('function_group');
		$db->function_group->insert(array(
			'id' => $id,
			'name' => $name,
			'weight' => $weight
		));
	}
	
	private static function insertFunction($name, $group, $weight) 
	{
		$db = \Ig\Db::getDb();
		
		$groupId = self::getFuncGroup($group);
		
		$id = \Ig\Db::getNextRunningNumber('function');
		$db->function->insert(array(
			'id' => $id,
			'name' => $name,
			'group_id' => $groupId,
			'weight' => $weight
		));
	}
	
	private static function insertSchedule(
			$className, $functionName, 
			$description, 
			$weekDay, $month, $day, $hour, $minute, 
			$status, $opt
	) {
		$db = \Ig\Db::getDb();
		
		$id = \Ig\Db::getNextRunningNumber('schedule');
		$db->schedule->insert(array(
			'id' => $id,
			'class_name' => $className,
			'function_name' => $functionName,
			'description' => $description,
			'weekday' => $weekDay,
			'month' => $month,
			'day' => $day,
			'hour' => $hour,
			'minute' => $minute,
			'status' => intval($status),
			'record_opt' => intval($opt)
		));
	}
	
	private static function getRoleId($name) 
	{
		$db = \Ig\Db::getDb();
		
		$x = $db->role->where('name', $name)->fetch();
		
		if (empty($x['id'])) {
			Throw new \Exception("Ig::Db:- cant found role <$name>");
		} else { 
			return $x['id'];
		}
	}
	
	private static function getFuncGroup($name) 
	{
		$db = \Ig\Db::getDb();
		
		$x = $db->function_group->where('name', $name)->fetch();
		
		if (empty($x['id'])) {
			Throw new \Exception("IgDbRebuild:- cant found function group <$name>");
		} else {
			return $x['id'];
		}
	}
	
	/**
	 * Delete all datatable and dataview from targeted database
	 */
	private static function clearDatabase() 
	{
		$pdo = \Ig\Db::getPDO();
		
		$stmt = $pdo->prepare("SHOW TABLES");
		$stmt->execute();
		$tt = '';
		$i = 0;
		foreach ($stmt as $tb) {
			if ($i > 0) {
				$tt .= ',`' . $tb[0] . '`';
			} else {
				$tt = '`' . $tb[0] . '`';
			}
			
			$i++;
		}
		
		$sql = "SET foreign_key_checks = 0; DROP TABLE " . $tt . ';';
		$pdo->exec($sql);
	}
}
?>