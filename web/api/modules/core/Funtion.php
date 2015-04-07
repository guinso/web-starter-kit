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