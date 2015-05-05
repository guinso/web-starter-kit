<?php 
namespace Hx\Login;

interface LoginInterface {
	public function login($username, $password);
	
	public function logout();
	
	public function forceLogout($username);
	
	public function isLogin();
	
	public function getUserProfile();
}
?>