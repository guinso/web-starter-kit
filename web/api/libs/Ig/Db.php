<?php 
namespace Ig;

class Db {
	private static $db;
	private static $pdo;
	
	private static $dsm, $dbUsr, $dbPwd;
	private static $dbIdInitial, $dbIdLen;
	
	public static function configure(
			$dsm, $dbUsr, $dbPwd,
			$dbIdInitial, $dbIdLen) {
	
		//TODO check database is accessible
		self::$dsm = $dsm;
		self::$dbUsr = $dbUsr;
		self::$dbPwd = $dbPwd;
	
		self::$dbIdInitial = $dbIdInitial;
		self::$dbIdLen = $dbIdLen;
	
	}
	
	/**
	 * Get PDO handler
	 * @return \PDO
	 */
	public static function getPDO() {
		if(empty(self::$pdo)) {
			self::$pdo = new \PDO(self::$dsm, self::$dbUsr, self::$dbPwd);
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}
	
		return self::$pdo;
	}
	
	/**
	 * Get defaut database handler
	 * @return NotORM not orm handler
	 */
	public static function getDb() {
		if(empty(self::$db)) {
			$pdo = self::getPDO();
	
			self::$db = new \NotORM($pdo);
		}
	
		return self::$db;
	}
	
	/**
	 * Prepare PDO handler
	 * @param string $dsm
	 * @param string $username
	 * @param string $password
	 * @return \PDO
	 */
	public static function preparePDO($dsm, $username, $password) {
		$pdo = new \PDO($dsm, $username, $password);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
	
		return $pdo;
	}
	
	/**
	 * Prepare NotORM handler
	 * @param \PDO $pdo
	 * @return \NotORM
	 */
	public static function prepareDb($pdo) {
		return new NotORM($pdo);
	}

	/**
	 * Simple Job ID generator
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $initial
	 * @param \NotORM $db
	 * @return string
	 */
	public static function getNextJobId($tableName, $columnName, $initial, $db = null) {
		if(empty($db))
			$db = self::getDb();
	
		$date = date('Ym');
	
		$item = $db->$tableName
			->where("$columnName REGEXP ?", '^' . $initial . $date . '-[0-9]{3}$')
			->order("$columnName desc")
			->fetch();
	
		$runningNo = 1;
		if(!empty($item)) {
			$tmp = $item[$columnName];
			$len = strlen($tmp);
			$subNo = intval(substr($tmp, $len - 3, 3));
	
			$runningNo = $subNo + 1;
		}
	
		return $initial . $date . '-' . str_pad($runningNo, 3, '0', STR_PAD_LEFT);
	}
	
	/**
	 * Get next running number from data table based on string pattern
	 * @param String $dbTableName	targeted data table name
	 * @param String $pattern	regular expression
	 */
	public static function getNextRunningNumber($dbTableName, $initial = NULL, $length = NULL,
			$db = null) {
	
		if(empty($initial)) {
			$initial = self::$dbIdInitial;
		}
	
		if(empty($length)) {
			$length = self::$dbIdLen;
		}
	
		if(empty($db))
			$db = self::getDb();
	
		$id = '';
	
		$item = $db->$dbTableName
			->where('id REGEXP ?', '^' . $initial . '[0-9]{'.$length.'}')
			->order('id desc')
			->fetch();
	
		$id = $item['id'];
	
		if(empty($id)) {
			$number = str_pad(1, $length, '0', STR_PAD_LEFT);
		} else {
			$n = intval(substr($id, 1)) + 1;
			$number = str_pad($n, $length, '0', STR_PAD_LEFT);
		}
	
		return $initial . $number;
	}

	public static function getDbLen() {
		return self::$dbIdLen;
	}
	
	public static function getDbInitial() {
		return self::$dbIdInitial;
	}
	

	/**
	 * Execute sql file into targeted database
	 * @param string $sqlFilePath	targeted sql script file path
	 * @param PDO $pdo				targeted database gateway
	 */
	public static function runSqlScript($sqlFilePath, $pdo = null) {
		$sql = file_get_contents($sqlFilePath);
	
		if(empty($pdo))
			$pdo = self::getPDO();
	
		$pdo->exec($sql);
	}
	
	
	/***************** Key - Value ************************/

	/**
	 * Set key value
	 * @param string $key
	 * @param mixed $value
	 */
	public static function setKeyValue($key, $value){
		$db = self::getDb();
	
		$row = $db->key_value[$key];
	
		$serialize = serialize($value);
	
		if(empty($row))
		{
			$item = array(
				'id' => $key,
				'value' => $serialize,
			);
	
			$db->key_value->insert($item);
		}
		else
		{
			$item = array(
				'value' => $serialize,
			);
			$row->update($item);
		}
	}
	
	/**
	 * Get Key value
	 * @param string $key
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public static function getKeyValue($key, $defaultValue = NULL){
		$db = self::getDb();
	
		$row = $db->key_value[$key];
	
		if(empty($row))
		{
			self::setKeyValue($key, $defaultValue);
	
			return $defaultValue;
		}
		else
		{
			$unserialize = unserialize($row['value']);
			return $unserialize;
		}
	}
	
}
?>