<?php 
/**
 * Admin miscellaneous setting REST interface
 * @author chingchetsiang
 *
 */
class AdmMiscREST {
	
	/**
	 * Get admin micealanous setting for GET
	 */
	public static function get() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to view system admin setting.");
		
		try {
			return AdmMisc::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update system admin setting.");
		
		try {
			$data = Util::getInputData();
			AdmMisc::set($data);
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
/*	
	public static function postMaintenance() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update system admin setting");
		
		try {
			$data = Util::getInputData();
			AdmMisc::setMaintenance($data['maintenance']);
			
			return AdmMisc::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postDeploy() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update system admin setting");
	
		try {
			$data = Util::getInputData();
			AdmMisc::setDeploy($data['deploy']);
				
			return AdmMisc::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
*/
}
		
/**
 * Admin miscellaneous setting
 * @author chingchetsiang
 *
 */
class AdmMisc {
	
	/**
	 * Get admin micealanous setting
	 * @param unknown $data
	 */
	public static function get() {
		$keys = IgConfig::getConfigKeys();
		$result = array();
		
		foreach($keys as $key) {
			$result[$key] = IgConfig::getConfig($key);
		}
		
		return $result;
	}
	
	public static function set($input) {
		foreach($input as $k => $v) {
			IgConfig::setConfig($k, $v);
		}

		IgConfig::setConfig('deploy', $input['deploy']);
		
		if(IgConfig::getConfig('deploy'))
			JsCompact::minimizeJs();
		
		IgConfigLoader::updateSetting();
	}
/*	
	public static function setMaintenance($isMaintenance) {
		IgConfig::setConfig('maintenance', $isMaintenance);
		IgConfigLoader::updateSetting();
	}
	
	public static function setDeploy($isDeploy) {
		if($isDeploy)
			JsCompact::minimizeJs();
		
		IgConfig::setConfig('deploy', $isDeploy);
		IgConfigLoader::updateSetting();
	}
*/
}
?>