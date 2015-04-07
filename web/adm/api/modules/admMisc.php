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
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to view system admin setting.");
		
		try {
			return AdmMisc::get();
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update system admin setting.");
		
		try {
			$data = \Ig\Web::getInputData();
			AdmMisc::set($data);
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
/*	
	public static function postMaintenance() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update system admin setting");
		
		try {
			$data = \Ig\Web::getInputData();
			AdmMisc::setMaintenance($data['maintenance']);
			
			return AdmMisc::get();
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postDeploy() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update system admin setting");
	
		try {
			$data = \Ig\Web::getInputData();
			AdmMisc::setDeploy($data['deploy']);
				
			return AdmMisc::get();
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
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
		$keys = \Ig\Config::getConfigKeys();
		$result = array();
		
		foreach($keys as $key) {
			$result[$key] = \Ig\Config::getConfig($key);
		}
		
		return $result;
	}
	
	public static function set($input) {
		foreach($input as $k => $v) {
			\Ig\Config::setConfig($k, $v);
		}

		\Ig\Config::setConfig('deploy', $input['deploy']);
		
		if(\Ig\Config::getConfig('deploy'))
			JsCompact::minimizeJs();
		
		\Ig\Config\Loader::updateSetting();
	}
/*	
	public static function setMaintenance($isMaintenance) {
		\Ig\Config::setConfig('maintenance', $isMaintenance);
		\Ig\Config\Loader::updateSetting();
	}
	
	public static function setDeploy($isDeploy) {
		if($isDeploy)
			JsCompact::minimizeJs();
		
		\Ig\Config::setConfig('deploy', $isDeploy);
		\Ig\Config\Loader::updateSetting();
	}
*/
}
?>