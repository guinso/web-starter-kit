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
		$keys = \Ig\Config::getProfileKeys();
		$result = array();
		
		foreach($keys as $key) {
			$result[$key] = \Ig\Config::getProfile($key)->get();
		}
		
		$defaultKey = \Ig\Config\getDefaultProfileKey();
		
		return array(
			'items' => $result,
			'defaultKey' => $defaultKey
		);
	}
	
	public static function update($input) {
		foreach($input['items'] as $k => $v) {
			$recipe = new \Ig\Config\Recipe(
				$v['dbName'], $v['dbHost'], $v['dbUsr'], $v['dbPwd'], 
				$v['dbLen'], $v['dbInitial'], 
				$v['uploadPath'], $v['templatePath'], $v['temporaryPath'], 
				$v['timeZone'], 
				$v['smtpHost'], $v['smtpUsr'], $v['smtpPwd'], 
				$v['smtpEmail'], $v['smtpName'], 
				$v['smtpSecure'], $v['smtpPort']);
			
			\Ig\Config::set($k, $recipe);
		}
		
		//check profile key exists or not
		$keys = \Ig\Config::getProfileKeys();
		$keyExists = false;
		$k = $input['defaultKey'];
		foreach($keys as $key) {
			if($key == $k)
				$keyExists = true;
		}
		
		if($keyExists) {
			\Ig\Config::setDefaultProfileKey($k);
			\Ig\Config\Loader::updateSetting();
		}
		else
			Throw new Exception("Update fault profile key to fail, " . 
					"such profile '$key' not exists in record.");
	}
}
?>