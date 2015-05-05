<?php
namespace Ig;
/**
 * In-house cron jon to perform greater control such as log record and concurrent task
 * @author hx
 *
 */ 
class Scheduler {
	private static $timeoutBuffer = 3600; // 1 hour
	
	public static function configure($maxTimeout) 
	{
		self::$timeoutBuffer = $maxTimeout;	
	}
	
	/**
	 * Cron run to get list of matching items
	 */
	public static function run() 
	{
		//mark fail to timeout tasks
		self::_checkTimeout();
		
		$db = \Ig\Db::getDb();
		$raw = $db->schedule()->where('status', 1);
		
		$x = array();
		foreach ($raw as $row) {
			$x[] = self::execute($row['id']);
		}
		
		return $x;
	}
	
	/**
	 * Run a task
	 * @param String $id
	 */
	public static function execute($id) 
	{
		$db = \Ig\Db::getDb();
		$pdo = \Ig\Db::getPDO();
		
		$month = intval(date('m'));
		$weekday = intval(date('w'));
		$day = intval(date('j')); //no leading zero
		$hour = intval(date('G'));
		$minute = intval(date('i'));
		
		$msg = '';
		
		$sch = $db->schedule[$id];
		if (empty($sch['id'])) {
			return;
		}
		
		/*
		\Ig\Web::sendErrorResponse(1, 'Dump evaluate', array(
			'month' => self::_dumpEvaluate($month, $sch['month']),
			'weekday' => self::_dumpEvaluate($weekday, $sch['weekday']),
				'day' => self::_dumpEvaluate($day, $sch['day']),
				'hour' => self::_dumpEvaluate($hour, $sch['hour']),
				'minute' => self::_dumpEvaluate($minute, $sch['miinute']),
		));
		*/
		
		$run = self::_evaluate($month, $sch['month']) && 
			self::_evaluate($weekday, $sch['weekday']) &&
			self::_evaluate($day, $sch['day']) &&
			self::_evaluate($hour, $sch['hour']) &&
			self::_evaluate($minute, $sch['minute']);

		if ($run) {
			$func = (empty($sch['class_name']) ? 
				$sch['function_name'] : 
				$sch['class_name'] . '::' . $sch['function_name']) . '();';
			$recordOpt = intval($sch['record_opt']);
			
			if ($recordOpt == 1 || $recordOpt == 2) {
				$idd = \Ig\Db::getNextRunningNumber('log_schedule');
				$item = array(
						'id' => $idd,
						'func' => $func,
						'start' =>  \Ig\Date::getDatetime(),
						'status' => 1
				);
				$db->log_schedule()->insert($item);
			}
			
			//TODO run task in worker thread or fork into child process OR
			//TODO Put task into global worker queue
			$success = true;
			
			try {
				eval($func);
				$success = true;
			} catch (Exception $ex) {
				$success = false;
				$msg = $ex->getMessage();
				
				if($pdo->inTransaction())
					$db->transaction = 'ROLLBACK';
			}

			//Update run record
			if ($recordOpt == 1 || $recordOpt == 2) {
				if ($success && $recordOpt == 1) {
					//delete record if record option is 'log fail only'
					$db->log_schedule[$idd]->delete();
				} else {
					$x = array(
						'end' =>  \Ig\Date::getDatetime(),
						'status' => $success? 2 : 3,
						'fail_msg' => $msg
					);
					
					$z = $db->log_schedule[$idd];
					$z->update($x);
				}
			}
		}	

		return $msg;
	}
	
	private static function _checkTimeout() 
	{
		$db = \Ig\Db::getDb();
		
		$raw = $db->log_schedule()->where('status', 1);
		foreach ($raw as $row) {
			$now = time();
			$startTime = strtotime($row['start']);
			
			if ($now - $startTime > self::$timeoutBuffer) {
				$row->update(array(
					'end' =>  \Ig\Date::getDatetime(),
					'status' => 3,
					'fail_msg' => 'Consider failed due to timeout.'
				));
			}
		}
	}
	
	private static function _evaluate($value, $expression) 
	{
		$hasWildCard = strpos($expression, '*') !== false;
		$x = str_replace('*', $value, $expression);
		$y = eval('return doubleval(' . $x . ');');
		
		if (empty($y)) {
			$y = 0;
		}
		
		return $value == $y || ($hasWildCard && ($y * 1000 % 1000) == 0);
	}
	
	private static function _dumpEvaluate($value, $expression) 
	{
		$x = str_replace('*', $value, $expression);
		$y = eval('return doubleval(' . $x . ');');
		if(empty($y))
			$y = 0;
	
		return array($value, $y ,$y * 1000 % 1000);
	}
}
?>