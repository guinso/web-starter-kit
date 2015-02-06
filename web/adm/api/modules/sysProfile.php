<?php 
class SysProfileRest {
	public static function get() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to view system profile.");
		
		try {
			return SysProfile::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update system profile.");
		
		try {
			$data = Util::getInputData();
			SysProfile::update($data);
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}

class SysProfile {
	public static function get() {
		$keys = IgConfig::getProfileKeys();
		$result = array();
		
		foreach($keys as $key) {
			$result[$key] = IgConfig::getProfile($key)->get();
		}
		
		return array('items' => $result);
	}
	
	public static function update($input) {
		foreach($input['items'] as $k => $v) {
			$recipe = new IgConfigRecipe(
				$v['dbName'], $v['dbHost'], $v['dbUsr'], $v['dbPwd'], 
				$v['dbLen'], $v['dbInitial'], 
				$v['uploadPath'], $v['templatePath'], $v['temporaryPath'], 
				$v['timeZone'], 
				$v['smtpHost'], $v['smtpUsr'], $v['smtpPwd'], 
				$v['smtpEmail'], $v['smtpName'], 
				$v['smtpSecure'], $v['smtpPort']);
			
			IgConfig::set($k, $recipe);
		}
		
		IgConfigLoader::updateSetting();
	}
}
?>