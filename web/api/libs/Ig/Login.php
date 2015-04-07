<?php
namespace Ig;
/**
 * Authenticate Utility
 * Only allow per user login at a point of time
 * Will force logout other existed matched active user if new login is made
 * @author chingchetsiang
 *
 */
class Login {
	private static $maxLife = 1800; // half an hour, in seconds
	
	public static function getLog() 
	{
		$db = \Ig\Db::getDb();
		$pgIndex = 0;
		$pgSize = 15;
		
		if (!empty($_GET['pgIndex']))
			$pgIndex = intval($_GET['pgIndex']);
		if (!empty($_GET['pgSize']))
			$pgSize = intval($_GET['pgSize']);
		
		$raw = $db->login()->order('login DESC');
		$raw = $raw->limit($pgSize, $pgIndex * $pgSize);
		
		$result = array();
		foreach ($raw as $row) {
			$result[] = self::_getFormat($row);
		}
	
		return $result;
	}
	
	public static function getLogCount() 
	{
		$db = \Ig\Db::getDb();

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
	public static function ping() 
	{
		$login = self::getCurrentUser();
		
		if ($login['userId'] != self::_getAnonymousId()) {
			$db = \Ig\Db::getDb();
			
			//update last access time to keep alive
			$x = $db->login[$login['id']];
			$x->update(array('last_access' => \Ig\Date::getDatetime()));
		}
	}
	
	/**
	 * Check all login records, force logout if timeout from allowed threshold
	 */
	public static function checkLogin() 
	{
		$now = strtotime(\Ig\Date::getDatetime());
		$db = \Ig\Db::getDb();
		
		$raw = $db->login->where('logout IS NULL');
		$db->transaction = 'BEGIN';
		
		foreach ($raw as $row) {
			$time = strtotime($row['last_access']);
			$diff = $now - $time;
			
			if($diff > self::$maxLife) {
				$row->update(array(
					'logout' => \Ig\Date::getDatetime(), 
					'remarks' => 'timeout'));
			}
		}
		
		$db->transaction = 'COMMIT';
	}
	
	public static function loginUser() 
	{
		$db = \Ig\Db::getDb();
	 
		$data = \Ig\Web::getInputData();
		$username = $data['username'];
		$password = $data['pwd'];
		$rememberMe = $data['rememberMe'];
	
		$user = $db->account()
			->where('status', 1) //active
			->where('username = ?', $username) //matched username
			->fetch();
	
		if (empty($user['id'])) {
			\Ig\Web::sendErrorResponse(-1,
				"Login fail, please check username or password",
				null, 406);
		}
		
		if ($password != $user['password']) {
			\Ig\Web::sendErrorResponse(-1,
				"Login fail, please check username or password",
				null, 406);
		}
	
		self::_writeLogin($user['id'], $rememberMe);

		return self::getCurrentUser();
	}
	
	public static function forceLoginAdmin() 
	{
		self::_writeLogin(self::_getAdminId());
	
		return self::getCurrentUser();
	}

	private static function _writeLogin($userId, $rememberMe = false) 
	{
		$db = \Ig\Db::getDb();
		
		//check current login status
		$writeLog = false;
		$x = self::getCurrentUser();
		
		//logout other login records if username matched
		self::logoutUser($user['id'],
				'force logout due to new client login with this username');
			
		if ($x['userId'] == self::_getAnonymousId()) {
			//anonymous user
			$writeLog = true;
		} else if($x['userId'] != $userId) {
			//authenticated login user, logout if login already
			self::logoutUser($x['userId'],
					'Force logout due to user log with other username');
			$writeLog = true;
		}
			
		//register login
		if ($writeLog) {
			$token = self::_createToken($userId);
			
			$idd = \Ig\Db::getNextRunningNumber('login');
			$time = \Ig\Date::getDatetime();
			$tmp = array(
					'id' => $idd,
					'user_id' => $userId,
					'session_id' => $token['tokenValue'],
					'login' => $time,
					'last_access' => $time,
					'remember_me' => $rememberMe? 1: 0
			);
			$db->login()->insert($tmp);
		}
	}
	
	public static function logoutUser($userId = null, $remarks = null) 
	{
		$db = \Ig\Db::getDb();
		$x = null;
		
		if (empty($userId)) {
			$sessionId = self::_getTokenValue();
			
			//logout based on session ID
			$x = $db->login()
				->where('session_id', $sessionId)
				->where('logout IS NULL')
				->order('login DESC');
			
		} elseif ($userId != self::_getAnonymousId()) {
			//logout based on userId
			$x = $db->login()
				->where('user_id', $userId)
				->where('logout IS NULL')
				->order('login DESC');
		}
		
		//logout record
		foreach ($x as $xx) {
			$tmp = array(
				'logout' => \Ig\Date::getDatetime(),
				'remarks' => $remarks
			);
			
			$xx->update($tmp);
		}
		
		//unregister token value
		self::_deleteToken();
	}
	
	public static function getCurrentUser() 
	{
		$sessionId = self::_getTokenValue();
		$db = \Ig\Db::getDb();
	
		$row = $db->login()
			->where('session_id', $sessionId)
			->where('logout IS NULL')
			->order('login DESC')
			->fetch();
	
		if (!empty($row['id'])) {
			//still active and login
			return self::_getFormat($row);
		} else {
			//not log into server or logout already
			return self::_getFormat(null);
		}
	}
	
	private static function _getFormat($row) 
	{
		$db = \Ig\Db::getDb();
		$userId = empty($row['id'])? self::_getAnonymousId() : $row['user_id'];
		
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
			'login' => empty($row['logout']) && $userId != self::_getAnonymousId(),
			'rememberMe' => intval($row['remember_me']) == 1
		);
	}
	
	private static function _getAnonymousId() 
	{
		$profile = \Ig\Config::getProfile();
		
		$len = '';
		for($i =0; $i < $profile->dbLen - 1; $i++)
			$len .= '0';
		$id = $profile->dbInitial . $len . '1';
		
		return $id;
	}
	
	private static function _getAdminId() 
	{
		$profile = \Ig\Config::getProfile();
	
		$len = '';
		for($i =0; $i < $profile->dbLen - 1; $i++)
			$len .= '0';
			$id = $profile->dbInitial . $len . '2';
	
			return $id;
	}
	
	/**
	 * Get token key based on server GUID
	 */
	private static function _getTokenKey() 
	{
		return 'ig-token-' . \Ig\Config::getGuid();
	}
	
	/**
	 * Dynamic create token value using MD5 hashing
	 */
	private static function _createToken($username) 
	{
		$tokenKey = self::_getTokenKey();
		
		$raw = $username . \Ig\Config::getGuid() . session_id() . time();
		
		$hash = md5($raw);
		
		$oneYear = 365 * 24 * 3600;
		
		//cookie will expired after one year starting current server time
		setcookie($tokenKey, $hash, time() + $oneYear);
		
		return array(
			'tokenKey' => $tokenKey,
			'tokenValue' => $hash
		);
	}
	
	/**
	 * Delete current login user's token
	 */
	private static function _deleteToken() 
	{
		$tokenKey = self::_getTokenKey();
		
		setcookie($tokenKey, '', time() - 3600); // 1 hour ealier
	}
	
	/**
	 * Retrieve client token value from cookie
	 */
	private static function _getTokenValue() 
	{
		$tokenKey = self::_getTokenKey();
		
		return $_COOKIE[$tokenKey];
	}
}
?>