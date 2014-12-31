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
		return array(
			'maintenance' => IgConfig::getConfig('maintenance'),
			'deploy' => IgConfig::getConfig('deploy')
		);
	}
	
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
}
?>