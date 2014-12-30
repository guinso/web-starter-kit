<?php

class Access {	
	public static function getAccessRight() {
		$name = $_GET['name'];
		$result = array(
			'access' => AdmLogin::isLogin()
		);
	
		return $result;
	}
	
	public static function getMatrix() {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized to view authorization.', null, 401);
		}
		
		$db = Util::getDb();
		
		$raw = $db->function_group->order('weight asc');
		
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
		$pdo = Util::getPDO();
	
		$result = array();
	
		$functions = $db->function->where('group_id = ?', $groupId)->order('weight asc');
		foreach($functions as $function) {
			$accesses = array();
			$raw = $db->access->where('function_id = ?', $function['id']);
			
			$sql = "SELECT a.*, b.name FROM access a 
				JOIN role b ON a.role_id = b.id
				WHERE function_id = :fId
				ORDER BY b.weight";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':fId' => $function['id']));
			foreach($stmt as $tmp) {
				
				$accesses[] = array(
						'id' => $tmp['id'],
						'roleId' => $tmp['role_id'],
						'role' => $tmp['name'],
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
		if(!AdmLogin::isLogin()) {
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