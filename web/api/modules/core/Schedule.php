<?php 
class Schedule {
	public static function get() {
		if(!AuthorizeUtil::isAuthorize('view schedule')) {
			Util::sendErrorResponse(-1,
					'You are not authorized to view schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();

		$raw = $db->schedule();
		
		$result = array();
		foreach($raw as $row)
			$result[] = self::_getFormat($row);
			
		return $result;
	}
	
	public static function getById($id) {
		if(!AuthorizeUtil::isAuthorize('view schedule')) {
			Util::sendErrorResponse(-1,
					'You are not authorized to view schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();
		
		$raw = $db->schedule[$id];
		
		return self::_getFormat($raw);
	}
	
	public static function updateBulk() {
		if(!AuthorizeUtil::isAuthorize('update schedule')) {
			Util::sendErrorResponse(-1,
					'You are not authorized to update schedule.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		$ids = array();
		$db->transaction = 'BEGIN';
		foreach($data as $item) {
			$tmp = array(
				'description' => $item['description'],
				'class_name' => $item['className'],
				'function_name' => $item['functionName'],
				'status' => intval($item['status']),
				'weekday' => $item['weekday'],
				'month' => $item['month'],
				'day' => $item['day'],
				'hour' => $item['hour'],
				'minute' => $item['minute'],
				'record_opt' => intval($item['recordOpt'])
			);
			
			if(empty($item['id'])) {
				$tmp['id'] = \Ig\Db::getNextRunningNumber('schedule');
				$db->schedule->insert($tmp);
				
				$ids[] = $tmp['id'];
			} else {
				$sch = $db->schedule[$item['id']];
				$sch->update($tmp);
				
				$ids[] = $item['id'];
			}
		}
		
		$db->schedule->where('id NOT', $ids)->delete();
		$db->transaction = 'COMMIT';
	}
	
	public static function run() {
		ScheduleUtil::run();	
	}
	
	public static function runById($id) {
		ScheduleUtil::execute($id);
	}
	
/*
	public static function post() {
		$db = \Ig\Db::getDb();
		$pdo = Util::getPdo();
		
		$data = Util::getInputData();
		$idd = \Ig\Db::getNextRunningNumber('schedule');
		
		//TODO define columns
		$item = array(
			'id' => $idd,
			'description' => $data['description'],
			'class_name' => $data['class_name'],
			'function_name' => $data['function_name'],
			'status' => 1,
			'weekday' => $data['weekday'],
			'month' => $data['month'],
			'day' => $data['day'],
			'hour' => $data['hour'],
			'minute' => $data['minute']
		);

		$db->transaction = 'BEGIN';
		$db->schedule->insert($item);
		$db->transaction = 'COMMIT';

		return self::getById($idd);
	}
	
	public static function put($id) {
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		$schedule = $db->schedule[$id];
		if(empty($schedule)) {
			Util::sendErrorResponse(-1, 'Update request rejected. " .
				"There is no such record found.');
		}
		
		$item = array(
			'description' => $data['description'],
			'class_name' => $data['class_name'],
			'function_name' => $data['function_name'],
			'status' => intval($data['status']),
			'weekday' => $data['weekday'],
			'month' => $data['month'],
			'day' => $data['day'],
			'hour' => $data['hour'],
			'minute' => $data['minute']
		);
		
		$db->transaction = 'BEGIN';
		$schedule->update($item);
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
*/
	/*
	public static function cancel($id) {
		$db = \Ig\Db::getDb();
		
		$schedule = $db->schedule[$id];
		
		//TODO set cancel attribute
		$item = array();
		
		$db->transaction = 'BEGIN';
		$schedule->update($item);
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
	*/
	private static function _getFormat($row) {
		return array(
			'id' => $row['id'],
			'description' => $row['description'],
			'className' => $row['class_name'],
			'functionName' => $row['function_name'],
			'status' => intVal($row['status']),
			'weekday' => $row['weekday'],
			'month' => $row['month'],
			'day' => $row['day'],
			'hour' => $row['hour'],
			'minute' => $row['minute'],
			'recordOpt' => intval($row['record_opt'])
		);
	}
}
?>