<?php
class Func {
	public static function get() {
		$db = Util::getDb();

		$raw = $db->function();
		
		$result = array();
		foreach($raw as $row)
			$result[] = self::_getFormat($row);
			
		return $result;
	}
	
	public static function getCount() {
		$db = Util::getDb();

		$raw = $db->function();
		
		$cnt = $raw->count('*');
		
		return array('count' => $cnt);
	}
	
	public static function getById($id) {
		$db = Util::getDb();
		
		$raw = $db->function[$id];
		
		return self::_getFormat($raw);
	}

	public static function post() {
		$db = Util::getDb();
		$pdo = Util::getPdo();
		
		$data = Util::getInputData();
		$idd = Util::getNextRunningNumber('function');
		
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
		$db = Util::getDb();
		$pdo = Util::getPdo();
		
		$data = Util::getInputData();
		$function = $db->function[$id];

		if(empty($function))
			Util::sendErrorResponse(-1, "Function $id not found in record.");

		//define columns
		$item = array(
			'name' => $data['name']
		);

		$db->transaction = 'BEGIN';
		$function->update($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	/*
	public static function cancel($id) {
		$db = Util::getDb();
		
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
		$db = Util::getDb();
		
		//define columns
		return array(
			'id' => $row['id'],
			'name' => $row['name']
		);
	}
} 
?>