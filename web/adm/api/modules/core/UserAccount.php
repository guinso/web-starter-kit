<?php 

class UserAccount {
	public static function get() {
		$db = \Ig\Db::getDb();
		
		$users = $db->account();
		$pgIndex = 0;
		$pgSize = 15;
		
		if(!AdmLogin::isLogin()) {
			$usr = LoginUtil::getCurrentUser();
			$users = $users->where('id = ?', $usr['userId']);
		}
		
		if(!empty($_GET['pgIndex']))
			$pgIndex = intval($_GET['pgIndex']);
		if(!empty($_GET['pgSize']))
			$pgSize = intval($_GET['pgSize']);
		
		$users = $users->limit($pgSize, $pgSize * $pgIndex);
				
		$result = array();
		foreach($users as $user) {
			$result[] = self::_getFormat($user);
		}
		
		return $result;
	}
	
	public static function getCount() {
		$db = \Ig\Db::getDb();
	
		$users = $db->account();

		if(!AdmLogin::isLogin())
			$cnt = 1;
		else
			$cnt = $users->count('*');
		
		return array('count' => $cnt);
	}
	
	public static function getActivityLog() {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized to view login activity.');
		}
		
		return LoginUtil::getLog();
	}
	
	public static function getActivityLogCount() {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized to view login activity.');
		}
		
		return LoginUtil::getLogCount();
	}
	
	public static function getById($id) {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$user = $db->account[$id];
		
		return self::_getFormat($user);
	}
	
	//Log record
	public static function getLog() {
		$db = \Ig\Db::getDb();
	
		$users = $db->account_log()->order('created DESC');
		$pgIndex = 0;
		$pgSize = 15;
	
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorize to view account log.');
		}
	
		if(!empty($_GET['pgIndex']))
			$pgIndex = intval($_GET['pgIndex']);
		if(!empty($_GET['pgSize']))
			$pgSize = intval($_GET['pgSize']);
	
		$users = $users->limit($pgSize, $pgSize * $pgIndex);
	
		$result = array();
		foreach($users as $user) {
			$x = self::_getFormat($user);
			$x['author'] = self::getById($user['author']);
			$x['created'] = $user['created'];
			$x['crud'] = $user['crud'];
			$x['log'] = $user['log'];
			$result[] = $x;
		}
	
		return $result;
	}
	
	public static function getLogCount() {
		$db = \Ig\Db::getDb();
	
		$users = $db->account_log();
	
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorize to view account log.');
		}
		
		$cnt = $users->count('*');
	
		return array('count' => $cnt);
	}

	public static function downloadFile($guid) {
		$db = \Ig\Db::getDb();
	
		$usr = LoginUtil::getCurrentUser();
		$userId = $usr['userId'];
	
		//get account record
		$x = \Ig\File\Attachment::getByGuid($guid);
	
		if(empty($x['id']))
			Util::sendErrorResponse(-1, "targeted GUID is not a valid ID.");
	
		$attachmentId = $x['id'];
		$m = $db->account->where('attachment_id', $attachmentId)->fetch();
		if(empty($m['id']))
			Util::sendErrorResponse(-1, "targeted GUID not found in account record");
	
		$authorize = AuthorizeUtil::isAuthorize('view user') || $userId == $m['id'];
	
		if($authorize) {
			\Ig\File\Attachment::downloadFile($guid);
		} else {
			Util::sendErrorResponse(-1, "You have no rights to download file");
		}
	}
	
	public static function getCurrentAccount() {
		$usr = LoginUtil::getCurrentUser();
		$userId = $usr['userId'];
	
		return self::getById($userId);
	}
	
	public static function post() {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		//check username uniqueness
		$username = $data['username'];
		$cnt = $db->account()->where('username', $username)->count('*');
		if($cnt > 0)
			Util::sendErrorResponse(-2, "Selected Username " . 
				"$username already used by other user.");
		
		//check name uniqueness
		$name = $data['name'];
		$cnt = $db->account()->where('name', $username)->count('*');
		if($cnt > 0)
			Util::sendErrorResponse(-2, "Selected name " .
					"$name already used by other user.");
		
		$id = \Ig\Db::getNextRunningNumber('account');
		
		$item = array(
			'id' => $id,
			'name' => $data['name'],
			'status' => intval($data['status']),
			'username' => $data['username'],
			'role_id' => $data['roleId'],
			'password' => $data['pwd'],
			'attachment_id' => $data['attachmentId'],
		);
		
		$db->transaction = 'BEGIN';
		$result = $db->account()->insert($item);
		\Ig\Db\Log::writeLog($id, 'account', 'c');
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
	
	public static function changePwd() {
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		$userId = $data['userId'];
		$pwd = $data['pwd'];
		
		$user = LoginUtil::getCurrentUser();

		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, "You are not authorized to change password.");
		}
		
		
		$account = $db->account[$userId];
		
		if(empty($account)) {
			Util::sendErrorResponse('Request rejected. You are passing invalid user ID: ' . $userId);
		}
		
		$db->transaction = 'BEGIN';
		$item = array('password' => $pwd);
		$account->update($item);
		\Ig\Db\Log::writeLog($userId, 'account', 'u', 'change pwd');
		
		$db->transaction = 'COMMIT';
		
		return self::getById($userId);
	}
	
	public static function put($id) {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		//check username uniqueness
		$username = $data['username'];
		$cnt = $db->account()
			->where('username', $username)
			->where('id <> ?', $id)
			->count('*');
		if($cnt > 0)
			Util::sendErrorResponse(-2, "Selected Username " .
					"$username already used by other user.");
		
		//check name uniqueness
		$name = $data['name'];
		$cnt = $db->account()
			->where('name', $username)
			->where('id <> ?', $id)
			->count('*');
		if($cnt > 0)
			Util::sendErrorResponse(-2, "Selected name " .
					"$name already used by other user.");
					
		$item = array(
			'name' => $data['name'],
			'status' => intval($data['status']),
			'username' => $data['username'],
			'role_id' => $data['roleId'],
			'attachment_id' => $data['attachmentId'],
			'status' => intval($data['status']),
		);
		
		$db->transaction = 'BEGIN';
		$account = $db->account[$id];
		$account->update($item);
		\Ig\Db\Log::writeLog($id, 'account', 'u');
		$db->transaction = 'COMMIT';
		
		return self::getById($id);
	}
	/*
	public static function delete($id) {
		if(!Login::isAuthorize('Manage User Access')) {
			Util::sendErrorResponse(-1, 'You are not authorized.', 401);
		}
		
		$db = \Ig\Db::getDb();
		
		$account = $db->account[$id];
		
		if(!empty($account) && $account->delete()) {
			return array('code'=> 1, 'msg'=>'ID: ' . $id . ' delete sucessfully.');
		} else {
			$err = array('code'=> -1, 'msg'=> 'ID: ' . $id . ' not found');
			Util::sendResponse(400, json_encode($err));
		}
	}
	*/
	private static function _getFormat($row) {
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		$role = $db->role[$row['role_id']];
		$attachment = \Ig\File\Attachment::getById($row['attachment_id']);
		
		return array(
			'id' => $row['id'],
			'name' => $row['name'],
			'username' => $row['username'],
			'roleId' => $role['id'],
			'role' => $role['name'],
			'status' => intval($row['status']),
			'attachmentId' => $row['attachment_id'],
			'attachment' => $attachment,
			
		);
	}
}
?>