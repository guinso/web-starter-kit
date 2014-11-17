<?php
/**
 * Authenticate Utility
 * Only allow per user login at a point of time
 * Will force logout other existed matched active user if new login is made
 * @author chingchetsiang
 *
 */
class LoginUtil {
	private static $anonymousId = 'A0000000001';
	private static $maxLife = 1800; // half an hour, in seconds
	
	public static function getLog() {
		$db = Util::getDb();
		$pgIndex = 0;
		$pgSize = 15;
		
		if(!empty($_GET['pgIndex']))
			$pgIndex = intval($_GET['pgIndex']);
		if(!empty($_GET['pgSize']))
			$pgSize = intval($_GET['pgSize']);
		
		$raw = $db->login()->order('login DESC');
		$raw = $raw->limit($pgSize, $pgIndex * $pgSize);
		
		$result = array();
		foreach($raw as $row) {
			$result[] = self::_getFormat($row);
		}
	
		return $result;
	}
	
	public static function getLogCount() {
		$db = Util::getDb();

		$cnt = $db->login()->count('*');
	
		return array('count' => $cnt);
	}
	/*
	public static function configure($maxLoginTime) {
		self::$maxLife = intval($maxLoginTime);
	}
	*/
	
	/**
	 * Ping curent login user to keep active in login records
	 */
	public static function ping() {
		$login = self::getCurrentUser();
		
		if($login['userId'] != self::$anonymousId) {
			$db = Util::getDb();
			
			//TODO update last access time to keep alive
			$x = $db->login[$login['id']];
			$x->update(array('last_access' => Util::getDatetime()));
		}
	}
	
	/**
	 * Check all login records, force logout if timeout from allowed threshold
	 */
	public static function checkLogin() {
		$now = strtotime(Util::getDatetime());
		$db = Util::getDb();
		
		$raw = $db->login->where('logout IS NULL');
		$db->transaction = 'BEGIN';
		foreach($raw as $row) {
			$time = strtotime($row['last_access']);
			$diff = $now - $time;
			
			if($diff > self::$maxLife) {
				$row->update(array(
					'logout' => Util::getDatetime(), 
					'remarks' => 'timeout'));
			}
		}
		$db->transaction = 'COMMIT';
	}
	
	public static function loginUser() {
		$sessionId = session_id();
		$db = Util::getDb();
	
		$data = Util::getInputData();
		$username = $data['username'];
		$password = $data['pwd'];
	
		$user = $db->account()
			->where('status', 1) //active
			->where('username = ?', $username) //matched username
			->fetch();
	
		if(empty($user['id'])) {
			Util::sendErrorResponse(-1,
				"Login fail, please check username or password",
				null, 203);
		}
	
		if($password == $user['password']) {
			//check current login status
			$writeLog = false;
			$x = self::getCurrentUser();
			
			//logout other login records if username matched
			self::logoutUser($user['id'], 
				'force logout due to new client login with this username');
				
			if($x['userId'] == self::$anonymousId) {
				//anonymous user
				$writeLog = true;
			} else if($x['username'] != $username){
				//authenticated login user, logout if login already
				self::logoutUser($x['userId'],
						'Force logout due to user log with other username');
				$writeLog = true;
			}
				
			//register login
			if($writeLog) {
				$idd = Util::getNextRunningNumber('login');
				$time = Util::getDatetime();
				$tmp = array(
					'id' => $idd,
					'user_id' => $user['id'],
					'session_id' => $sessionId,
					'login' => $time,
					'last_access' => $time
				);
				$db->login()->insert($tmp);
			}

			return self::getCurrentUser();
		} else {
			Util::sendErrorResponse(-1,
			"Login fail, please check username or password",
			null, 203);
		}
	}

	public static function logoutUser($userId = null, $remarks = null) {
		$db = Util::getDb();
		$x = null;
		
		if(empty($userId)) {
			$sessionId = session_id();
			
			//logout based on session ID
			$x = $db->login()
				->where('session_id', $sessionId)
				->where('logout IS NULL')
				->order('login DESC');
			
		} else if($userId != self::$anonymousId) {
			//logout based on userId
			$x = $db->login()
				->where('user_id', $userId)
				->where('logout IS NULL')
				->order('login DESC');
		}
		
		//logout record
		foreach($x as $xx) {
			$tmp = array(
				'logout' => Util::getDatetime(),
				'remarks' => $remarks
			);
			
			$xx->update($tmp);
		}
	}
	
	public static function getCurrentUser() { //!!! get current user by id
		$sessionId = session_id();
		$db = Util::getDb();
	
		$row = $db->login()
			->where('session_id', $sessionId)
			->where('logout IS NULL')
			->order('login DESC')
			->fetch();
	
		if(!empty($row['id'])) {
			//still active and login
			return self::_getFormat($row);
		} else {
			//not log into server or logout already
			return self::_getFormat(null);
		}
	}
	
	private static function _getFormat($row) {
		$db = Util::getDb();
		$userId = empty($row['id'])? self::$anonymousId : $row['user_id'];
		
		$account = $db->account[$userId];
		$role = $db->role[$account['role_id']];
		
		return array(
			'id' => $row['id'],
			'userId' => $account['id'],
			'username' => $account['username'],
			'name' => $account['name'],
			'roleId' => $role['id'],
			'role' => $role['name'],
			'loginTime' => $row['login'],
			'logoutTime' => $row['logout'],
			'lastAccess' => $row['last_access'],
			'remarks' => $row['remarks'],
			'login' => empty($row['logout']) && $userId != self::$anonymousId
		);
	}
}
?>