<?php 
namespace Ig\Db;

/**
 * Database log utility class
 * @author chingchetsiang
 *
 */
class Log {
	/**
	 * General Write xxx_log record from xxx datatable record
	 * @param String $id			targeted <xxx.id>
	 * @param String $dbTable		targeted datatable name, xxx
	 * @param String $crud			action description, 'c', 'r', 'u', or 'd'
	 * @param String $log			remarks
	 * @param datetime $datetime	time of record being created
	 * @param String $userId		targeted user who created the record, <account.id>
	 */
	public static function writeLog(
		$id, $dbTable, $crud,
		$log = '', $datetime = NULL, $userId = NULL,
		$db = null, $pdo = null, $dbLen = null, $dbInitial = null
	) {
		if (empty($pdo)) {
			$pdo = \Ig\Db::getPDO();
		}
	
		if (empty($db)) {
			$db = \Ig\Db::getDb();
		}
	
		if (empty($dbLen)) {
			$dbLen = \Ig\Db::getDbLen();
		}
	
		if (empty($dbInitial)) {
			$dbInitial = \Ig\Db::getDbInitial();
		}
	
		$dbTableLog = $dbTable . '_log';
	
		$data = $db->{$dbTable}[$id];
		if (empty($data))
			Throw new \Exception("IgDbLog:- $dbTable $id not found in database.");
	
		if (empty($userId)) {
			$user = \Ig\Login::getCurrentUser();
			$userId = $user['userId'];
		}
	
		if (empty($datetime))
			$datetime = \Ig\Date::getDatetime();
	
		/*
			//skip if no changes
			$cols = self::checkDifference($dbTable, $id);
			if(count($cols) < 1)
				return;
		*/
	
		//prepare common log info
		$idd = \Ig\Db::getNextRunningNumber($dbTableLog, $dbInitial, $dbLen, $db);
		$item = array(
			'id' => $idd,
			'created' => $datetime,
			'author' => $userId,
			'referred' => $id,
			'crud' => $crud,
			'log' => $log,
		);
	
		//get datatable metadata
		//This method only works in Mysql only!
		//Assume xxx_log have similiar fields to its volatile datatable, xxx
		$sql = str_replace("'", "", "DESCRIBE $dbTable");
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		foreach ($stmt as $meta) {
			$field = $meta['Field'];
			$type = $meta['Type'];
			//skip 'id' as it is prmary key for volatile datatable , not for log table in stead
			if ($meta['Field'] != 'id') {
				$item[$field] = self::getDataValue($data[$field], $type);
			}
		}
	
		$db->$dbTableLog->insert($item);
	}
	
	/**
	 * Get last log record for specific record
	 * @param 	string $tableName	data-table name
	 * @param 	string $id		primary key value, cloumn name must be 'id'
	 * @return	NOTORM-record
	 */
	public static function getlastLog($tableName, $id, $db = null) 
	{
		if (empty($db)) {
			$db = \Ig\Db::getDb();
		}
	
		$logTableName = $tableName . '_log';
	
		$lastRecord = $db->{$logTableName};
		$lastRecord = $lastRecord->where('referred', $id)->order('created DESC')->fetch();
	
		if(empty($lastRecord['id']))
			return null;
		else
			return $lastRecord;
	}
	
	/**
	 * Check current record with log table record to compare have difference or not
	 * @param string $tableName	data-table name
	 * @param string $id		primary key value, cloumn name must be 'id'
	 * @return array if record are valid, otherwise, will return false
	 */
	public static function checkDifference($tableName, $id, $db = null, $pdo = null) 
	{
		if (empty($pdo)) {
			$pdo = \Ig\Db::getPDO();
		}
	
		if (empty($db)) {
			$db = \Ig\Db::getDb();
		}
	
		$logTableName = $tableName . '_log';
		$diffCols = array();
	
		$newRecord = $db->{$tableName}[$id];
		//check record exist or not
		if (empty($newRecord['id'])) {
			return false;
		}
	
		$lastRecord = $db->{$logTableName};
		$lastRecord = $lastRecord->where('referred', $id)->order('created DESC')->fetch();
		$getAll = empty($lastRecord['id']);
	
		$sql = str_replace("'", "", "DESCRIBE $tableName");
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		foreach ($stmt as $meta) {
			$field = $meta['Field'];
			$type = $meta['Type'];
			$oldValue = self::getDataValue($lastRecord[$field], $type);
			$newValue = self::getDataValue($newRecord[$field], $type);
	
			//check and get column which is diff value
			if($getAll || $lastRecord[$field] != $newRecord[$field]) {
				$diffCols[$field] = array(
						'oldValue' => $oldValue,
						'newValue' => $newValue
				);
			}
		}
	
		return $diffCols;
	}
	
	private static function getDataValue($value, $type) 
	{
		$result = null;
	
		if (preg_match('/^int/', $type) == 1) {
			//for int
			$result = intVal($value);
		} elseif (preg_match('/^float$/', $type) == 1) {
			//for float
			$result = doubleVal($value);
		} elseif (preg_match('/^double$/', $type) == 1) {
			//for double
			$result = doubleVal($value);
		} else {
			//for general
			$result = $value;
		}
	
		return $result;
	}
}
?>