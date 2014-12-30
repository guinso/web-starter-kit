<?php 
class AdmLogin {
	public static function login() {
		$guid = IgConfig::getGuid();
		
		$data = Util::getInputData();
		
		$username = $data['username'];
		$password = $data['pwd'];
		
		if(IgConfig::isLoginMatch($username, $password)) {
			$_SESSION[$guid . '-log'] = true;
		} else {
			$_SESSION[$guid.'-log'] = false;
			Util::sendErrorResponse(-1, "Login fail, username or password not match.");
		}
	}
	
	public static function logout() {
		$guid = IgConfig::getGuid();
		
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
}
?>