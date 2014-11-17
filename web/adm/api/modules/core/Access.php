<?php

class Access {	
	public static function getAccessRight() {
		$name = $_GET['name'];
		$result = array(
			'access' => AuthorizeUtil::isAuthorize($name)
		);
	
		return $result;
	}
	
	public static function getMatrix() {
		if(!AuthorizeUtil::isAuthorize('manage user', 'view user')) {
			Util::sendErrorResponse(-1, 'You are not authorized to view authorization.', null, 401);
		}
		
		$db = Util::getDb();
		
		$raw = $db->function_group->order('name asc');
		
		$result = array();
		foreach($raw as $tmp) {
			$functions = self::getMatrixByGroupId($tmp['id']);
			
			$result[] = array(
				'id' => $tmp['id'],
				'name' => $tmp['name'],
				'functions' => $functions		
			);
		}
		
		return $result;
	}
	
	private static function getMatrixByGroupId($groupId) {
		$db = Util::getDb();
	
		$result = array();
	
		$functions = $db->function->where('group_id = ?', $groupId);
		foreach($functions as $function) {
			$accesses = array();
			$raw = $db->access->where('function_id = ?', $function['id'])->order('role_id asc');
			foreach($raw as $tmp) {
				$role = $db->role[$tmp['role_id']];
				$accesses[] = array(
						'id' => $tmp['id'],
						'roleId' => $tmp['role_id'],
						'role' => $role['name'],
						'isAuthorize' => ($tmp['authorize'] == 1)
				);
			}
				
			$result[] = array(
					'function' => $function['name'],
					'functionId' => $function['id'],
					'accesses' => $accesses,
			);
		}
	
		return $result;
	}

	public static function updateMatrixGroup($groupId) {
		if(!AuthorizeUtil::isAuthorize('manage user')) {
			Util::sendErrorResponse(-1, 'You are not authorized.', null, 401);
		}
		
		$db = Util::getDb();
		$pdo = Util::getPdo();
		
		$data = Util::getInputData();
		
		$functions = $data['functions'];
		
		$pdo->beginTransaction();
		foreach($functions as $function) {
			foreach($function['accesses'] as $access) {
				$a = $db->access[$access['id']];
				$item = array(
					'authorize' => $access['isAuthorize']? 1 : 0	
				);
				$a->update($item);
			}
		}
		$pdo->commit();
	}
	
	public static function rebuildAccessMatrix() {
		$pdo = Util::getPdo();

		$result = self::_constructAccessMatrix();

		return $result;
	}
	
	public static function _constructAccessMatrix() {
		$db = Util::getDb();
		
		
		$functions = $db->function();
		$roles = $db->role();
		
		$cnt = -1;
		
		foreach($functions as $function) {
			foreach($roles as $role) {
				$cnt = $db->access
					->where('role_id = ?', $role['id'])
					->where('function_id = ?', $function['id'])
					->count();
				
				if($cnt == 0) {
					//create access
					$id = Util::getNextRunningNumber('access');
					$item = array(
						'id' => $id,
						'function_id' => $function['id'],
						'role_id' => $role['id'],
						'authorize' => false	
					);
					
					$db->access->insert($item);
				}
			}
		}

		return $cnt;
	}
}
?>