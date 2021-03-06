<?php 
class ScheduleLog {
	public static function get() {
		if(!AdmLogin::isLogin()) {
			\Ig\Web::sendErrorResponse(-1, 
				'You are not authorized to view schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$pgIndex = 0;
		$pgSize = 15;
		
		if(!empty($_GET['pgIndex']))
			$pgIndex = intval($_GET['pgIndex']);
			
		if(!empty($_GET['pgSize']))
			$pgSize = intval($_GET['pgSize']);
			
		$raw = $db->log_schedule()->order('id DESC');
		$raw->limit($pgSize, $pgSize * $pgIndex);
		
		$result = array();
		foreach($raw as $row)
			$result[] = self::_getFormat($row);
			
		return $result;
	}
	
	public static function getCount() {
		if(!AdmLogin::isLogin()) {
			\Ig\Web::sendErrorResponse(-1,
					'You are not authorized to view schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();

		$raw = $db->log_schedule();
		
		$cnt = $raw->count('*');
		
		return array('count' => $cnt);
	}
	
	public static function getById($id) {
		if(!AdmLogin::isLogin()) {
			\Ig\Web::sendErrorResponse(-1,
					'You are not authorized to view schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();
		
		$raw = $db->log_schedule[$id];
		
		return self::_getFormat($raw);
	}
/*
	public static function post() {
		$db = \Ig\Db::getDb();
		$pdo = Util::getPdo();
		
		$data = \Ig\Web::getInputData();
		$idd = \Ig\Db::getNextRunningNumber('log_schedule');
		
		//TODO define columns
		$item = array(
			'id' => $idd
		);

		$db->transaction = 'BEGIN';
		$db->log_schedule->insert($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	
	public static function put($id) {
		$db = \Ig\Db::getDb();
		$pdo = Util::getPdo();
		
		$data = \Ig\Web::getInputData();
		$schLog = $db->log_schedule[$id];

		if(empty($schLog))
			\Ig\Web::sendErrorResponse(-1, "Schedule Log $id not found in record.");

		//TODO define columns
		$item = array(
		
		);

		$db->transaction = 'BEGIN';
		$schLog->update($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	
	public static function cancel($id) {
		$db = \Ig\Db::getDb();
		
		$schLog = $db->log_schedule[$id];
		
		//TODO set cancel attribute
		$item = array();
		
		$db->transaction = 'BEGIN';
		$schLog->update($item);
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
*/
	private static function _getFormat($row) {
		$db = \Ig\Db::getDb();
		
		//define columns
		return array(
			'id' => $row['id'],
			'func' => $row['func'],
			'start' => $row['start'],
			'end' => $row['end'],
			'status' => intval($row['status']),
			'failMsg' => $row['fail_msg']
		);
	}
}
?>