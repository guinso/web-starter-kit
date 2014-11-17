<?php
class IgConfigRecipe {

	//For public web
	private $dsm = '';
	private $dbUsr = '';
	private $dbPwd = '';
	private $dbLen = 10;
	private $dbInitial = '';
	
	private $uploadPath = '';
	private $templatePath = '';
	private $temporaryPath = '';
	
	private $timeZone = '';
	
	private $smtpHost = '';
	private $smtpUsr = '';
	private $smtpPwd = '';
	private $smtpEmail = '';
	private $smtpName = '';
	private $smtpPort = null;
	private $smtpSecure = '';
	
	public function __construct(
		$dsm, $dbUsr, $dbPwd, 
		$dbLen, $dbInitial, 
		$uploadPath, $templatePath, $temporaryPath,
		$timeZone,
		$smtpHost, $smtpUsr, $smtpPwd, $smtpEmail, $smtpName, 
		$smtpSecure, $smtpPort) {
		
		$this->dsm = $dsm;
		$this->dbUsr = $dbUsr;
		$this->dbPwd = $dbPwd;
		
		$this->dbLen = $dbLen;
		$this->dbInitial = $dbInitial;
		
		$this->uploadPath = $uploadPath;
		$this->templatePath = $templatePath;
		$this->temporaryPath = $temporaryPath;
		
		$this->timeZone = $timeZone;
		
		$this->smtpHost = $smtpHost;
		$this->smtpUsr = $smtpUsr;
		$this->smtpPwd = $smtpPwd;
		$this->smtpEmail = $smtpEmail;
		$this->smtpName = $smtpName;
		$this->smtpPort = $smtpPort;
		$this->smtpSecure = $smtpSecure;
	}
	
	public function get() {
		return array(
			'dsm' => $this->dsm,
			'dbUsr' => $this->dbUsr,
			'dbPwd' => $this->dbPwd,
			'dbLen' => intval($this->dbLen),
			'dbInitial' => $this->dbInitial,
			'uploadPath' => $this->uploadPath,
			'temporaryPath' => $this->temporaryPath,
			'templatePath' => $this->templatePath,
			'timeZone' => $this->timeZone,
			'smtpHost' => $this->smtpHost,
			'smtpUsr' => $this->smtpUsr,
			'smtpPwd' => $this->smtpPwd,
			'smtpEmail' => $this->smtpEmail,
			'smtpName' => $this->smtpName,
			'smtpPort' => $this->smtpPort,
			'smtpSecure' => $this->smtpSecure
		);
	}
}

class IgConfig {
	private static $recipes = array();
	
	public static function get($name) {
		return self::$recipes[$name]->get();
	}
	
	public static function set($name, IgConfigRecipe $value) {
		self::$recipes[$name] = $value;
	}
}
?>