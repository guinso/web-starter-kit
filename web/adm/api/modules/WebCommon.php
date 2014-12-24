<?php 
class WebCommon {
	private static $db = null;
	private static $pdo = null;
	
	public static function checkAndDownloadFile(
			$guid, $dbtable,
			$dbColumn = 'attachment_id', $functionNames = array()) {
	
		$fileWithIn = FileUtil::isWithinRecord($dbtable, $guid, $dbColumn);
		$authorize = AuthorizeUtil::isAuthorize2($functionNames);
	
		if($fileWithIn && $authorize)
			FileUtil::downloadFile($guid);
		else
			Util::sendErrorResponse(-1,
					"Request rejected, you have no rights to download file", null, 401);
	}
	
	public static function getWebPdo() {
		if(empty(self::$pdo)) {
			$setting = IgConfig::get('web');
			self::$pdo = Util::preparePDO($setting['dsm'], $setting['dbUsr'], $setting['dbPwd']);
		}
		
		return self::$pdo;
	}
	
	public static function getWebDb() {
		if(empty(self::$db))
			self::$db = Util::prepareDb(self::getWebPdo());
		
		return self::$db;
	}
	
	public static function setWebKeyValue($key, $value){
		$db = self::getWebDb();
	
		$row = $db->key_value[$key];
	
		$serialize = serialize($value);
	
		if(empty($row))
		{
			$item = array(
				'id' => $key,
				'value' => $serialize,
			);
	
			$db->key_value->insert($item);
		}
		else
		{
			$item = array(
				'value' => $serialize,
			);
			$row->update($item);
		}
	}
	
	public static function getWebKeyValue($key, $defaultValue = NULL){
		$db = self::getWebDb();
	
		$row = $db->key_value[$key];
	
		if(empty($row))
		{
			self::setWebKeyValue($key, $defaultValue);
	
			return $defaultValue;
		}
		else
		{
			$unserialize = unserialize($row['value']);
			return $unserialize;
		}
	}
}
?>