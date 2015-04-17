<?php
class Func {
	public static function get() {
		$db = \Ig\Db::getDb();

		$raw = $db->function();
		
		$result = array();
		foreach($raw as $row)
			$result[] = self::_getFormat($row);
			
		return $result;
	}
	
	public static function getCount() {
		$db = \Ig\Db::getDb();

		$raw = $db->function();
		
		$cnt = $raw->count('*');
		
		return array('count' => $cnt);
	}
	
	public static function getById($id) {
		$db = \Ig\Db::getDb();
		
		$raw = $db->function[$id];
		
		return self::_getFormat($raw);
	}

	public static function post() {
		$db = \Ig\Db::getDb();
		$pdo = Util::getPdo();
		
		$data = \Ig\Web::getInputData();
		$idd = \Ig\Db::getNextRunningNumber('function');
		
		//TODO define columns
		$item = array(
			'id' => $idd,
			'name' => $data['name']
		);

		$db->transaction = 'BEGIN';
		$db->function->insert($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	
	public static function put($id) {
		$db = \Ig\Db::getDb();
		$pdo = Util::getPdo();
		
		$data = \Ig\Web::getInputData();
		$function = $db->function[$id];

		if(empty($function))
			\Ig\Web::sendErrorResponse(-1, "Function $id not found in record.");

		//define columns
		$item = array(
			'name' => $data['name']
		);

		$db->transaction = 'BEGIN';
		$function->update($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	
	/**
	 * Add function group
	 * @param string $groupName
	 * @param number $weight
	 * @throws \Exception
	 * @return string	system id of fucntino group
	 */
	public static function addFunctionGroup($groupName, $weight = 0) {
		$db = \Ig\Db::getDb();
		$id = \Ig\Db::getNextRunningNumber('function_group');
		
		$cnt = $db->function_group
			->where('name', $groupName)
			->count('*');
		
		if ($cnt > 0)
			Throw new \Exception(
				"function_group already have $groupName record.");
		
		$db->function_group->insert(array(
			'id' => $id,
			'name' => $groupName,
			'weight' => intval($weight)
		));
		
		return $id;
	}
	
	/**
	 * Add fucntion access record
	 * @param string $name
	 * @param string $groupName
	 * @param number $weight
	 * @throws \Exception
	 * @return string
	 */
	public static function addFunction($name, $groupName, $weight = 0) {
		$db = \Ig\Db::getDb();
		
		$cnt = $db->function->where('name', $name)->count('*');
		if ($cnt > 0)
			Throw new \Exception(
				"Datatable <function> already have $name");
		
		$x = $db->function_group
			->where('name', $groupName)
			->fetch();
		
		if (empty($x['id']))
			Throw new \Exception(
				"function_group with <$groupName> not found in record.");
		
		$id = \Ig\Db::getNextRunningNumber('function');
		
		$db->function->insert(array(
			'id' => $id,
			'name' => $name,
			'group_id' => $x['id'],
			'weight' => intval($weight)
		));
		
		return self::getById($id);
	}
	
	/*
	public static function cancel($id) {
		$db = \Ig\Db::getDb();
		
		$function = $db->function[$id];
		
		//TODO set cancel attribute
		$item = array();
		
		$db->transaction = 'BEGIN';
		$function->update($item);
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
	*/
	
	private static function _getFormat($row) {
		$db = \Ig\Db::getDb();
		
		//define columns
		return array(
			'id' => $row['id'],
			'name' => $row['name']
		);
	}
} 
?>