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
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postLogin() {
		try {
			$data = \Ig\Web::getInputData();
			$result = AdmLogin::login($data['username'], $data['pwd']);
			
			if(!$result)
				\Ig\Web::sendErrorResponse(-1, "Login fail, username or password not match.");
			else 
				return AdmLogin::getStatus();
			
		} catch (Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function getLogout() {
		try {
			AdmLogin::logout();

			return AdmLogin::getStatus();
		} catch (Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function postAccount() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update admin account.");
		
		try {
			$data = \Ig\Web::getInputData();
			AdmLogin::updateAccount($data['username'], $data['newPassword']);
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}

class AdmLogin {
	public static function login($username, $password) {
		$guid = \Ig\Config::getGuid();
		
		$_SESSION[$guid . '-log'] = \Ig\Config::isLoginMatch($username, $password);
		
		try {
			//add login session into database
			\Ig\Login::forceLoginAdmin();
		} catch(Exception $ex) {
			//do nothing to bypass PHP error exception
		}
		
		return $_SESSION[$guid . '-log'];
	}
	
	public static function logout() {
		$guid = \Ig\Config::getGuid();
		
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
		$guid = \Ig\Config::getGuid();
		
		return isset($_SESSION[$guid . '-log']) && $_SESSION[$guid . '-log'];
	}
	
	public static function getStatus() {
		$guid = \Ig\Config::getGuid();
		
		$isLogin = $_SESSION[$guid . '-log'];
		
		if($isLogin) {
			return array(
				'name' => \Ig\Config::getUsr(),
				'username' => \Ig\Config::getUsr(),
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
		\Ig\Config::setLogin($username, $newPassword);
		\Ig\Config\Loader::updateSetting();
	}
}
?>