<?php 
/**
 * Admin authorization REST interface
 * @author chingchetsiang
 *
 */
class AdmLoginREST {
	public static function get() {
		
		try {
			return AdmLogin::getStatus();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postLogin() {
		try {
			$data = Util::getInputData();
			$result = AdmLogin::login($data['username'], $data['pwd']);
			
			if(!$result)
				Util::sendErrorResponse(-1, "Login fail, username or password not match.");
			else 
				return AdmLogin::getStatus();
			
		} catch (Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function getLogout() {
		try {
			AdmLogin::logout();

			return AdmLogin::getStatus();
		} catch (Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postAccount() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update admin account.");
		
		try {
			$data = Util::getInputData();
			AdmLogin::updateAccount($data['username'], $data['newPassword']);
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}

class AdmLogin {
	public static function login($username, $password) {
		$guid = IgConfig::getGuid();
		
		$_SESSION[$guid . '-log'] = IgConfig::isLoginMatch($username, $password);
		
		try {
			//add login session into database
			\Ig\Login::forceLoginAdmin();
		} catch(Exception $ex) {
			//do nothing to bypass PHP error exception
		}
		
		return $_SESSION[$guid . '-log'];
	}
	
	public static function logout() {
		$guid = IgConfig::getGuid();
		
		try {
			//add logout session into database
			\Ig\Login::logoutUser();
		} catch(Exception $ex) {
			//do nothing to bypass PHP error exception
		}
		
		if(isset($_SESSION[$guid . '-log']))
			unset($_SESSION[$guid . '-log']);
	}
	
	public static function isLogin() {
		$guid = IgConfig::getGuid();
		
		return isset($_SESSION[$guid . '-log']) && $_SESSION[$guid . '-log'];
	}
	
	public static function getStatus() {
		$guid = IgConfig::getGuid();
		
		$isLogin = $_SESSION[$guid . '-log'];
		
		if($isLogin) {
			return array(
				'name' => IgConfig::getUsr(),
				'username' => IgConfig::getUsr(),
				'login' => true
			);
		} else {
			return array(
				'name' => 'anonymous',
				'username' => 'anonymous',
				'login' => false
			);
		}
	}
	
	public static function updateAccount($username, $newPassword) {
		IgConfig::setLogin($username, $newPassword);
		IgConfigLoader::updateSetting();
	}
}
?>