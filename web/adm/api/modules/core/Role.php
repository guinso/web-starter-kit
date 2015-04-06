<?php 
class Role {
	public static function get() {
		$db = \Ig\Db::getDb();

		$raw = $db->role()->order('weight');
		
		if(!empty($_GET['status']))
			$raw = $raw->where('status', intval($_GET['status']));
		
		$result = array();
		foreach($raw as $row)
			$result[] = self::_getFormat($row);
		
		return $result;
	}

	public static function putBulk() {
		if(!AdmLogin::isLogin()) {
			Util::sendErrorResponse(-1, 'You are not authorized to update role.', 401);
		}
		
		$db = \Ig\Db::getDb();
		$data = Util::getInputData();
		
		$db->transaction = 'BEGIN';
		foreach ($data as $item) {
			if(empty($item['id'])) {
				$idd = \Ig\Db::getNextRunningNumber('role');
				$tmp = array(
					'id' => $idd,
					'name' => $item['name'],
					'status' => intval($item['status'])
				);
				
				$db->role->insert($tmp);
			} else {
				$role = $db->role[$item['id']];
				$role->update(array(
						'name' => $item['name'],
						'status' => intval($item['status'])
				));
			}
		}
		Access::rebuildAccessMatrix();
		
		$db->transaction = 'COMMIT';
		
		
	}

	private static function _getFormat($row) {
		$db = \Ig\Db::getDb();
		
		//define columns
		return array(
			'id' => $row['id'],
			'name' => $row['name'],
			'status' => intVal($row['status'])
		);
	}
}
?>