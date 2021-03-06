<?php 
class IgUpdate1 implements IgUpdate {
	//Get update description, return in array string
	public static function getMessage() {
		return array(
			'message 1',
			'message 2'
		);
	}
	
	//Execute update script, throw exception if encounter abnormality
	public static function runScript() {
		//get current directory path
		$dir = dirname(__FILE__);
		
		//get web delpoy setting 
		$db = \Ig\Db::getDb();
		$pdo = \Ig\Db::getPDO();
		
		//sample running multiple files using web deploy databse gateway
		\Ig\Db::runSqlScript($dir . DIRECTORY_SEPARATOR . 'sample.sql', $pdo);
	}
}
?>