<?php 
namespace Ig\Config;

class Recipe {

	//For public web
	public $dsm = '';
	public $dbName = '';
	public $dbHost = '';
	public $dbUsr = '';
	public $dbPwd = '';
	public $dbLen = 10;
	public $dbInitial = '';

	public $uploadPath = '';
	public $templatePath = '';
	public $temporaryPath = '';

	public $timeZone = '';

	public $smtpHost = '';
	public $smtpUsr = '';
	public $smtpPwd = '';
	public $smtpEmail = '';
	public $smtpName = '';
	public $smtpPort = null;
	public $smtpSecure = '';

	public $absUploadPath = '';
	public $absTemplatePath = '';
	public $absTemporaryPath = '';

	private $rootPath = '';
	
	public function __construct(
			$dbName, $dbHost, $dbUsr, $dbPwd,
			$dbLen, $dbInitial,
			$uploadPath, $templatePath, $temporaryPath,
			$timeZone,
			$smtpHost, $smtpUsr, $smtpPwd, $smtpEmail, $smtpName,
			$smtpSecure, $smtpPort
	) {
		$this->dbName = $dbName;
		$this->dbHost = $dbHost;

		$this->dsm = "mysql:dbname=$dbName;host=$dbHost;charset=utf8mb4";
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
		
		$this->setRootPath('');
	}

	public function get() 
	{
		return array(
			'dsm' => $this->dsm,
			'dbName' => $this->dbName,
			'dbHost' => $this->dbHost,
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
			'smtpSecure' => $this->smtpSecure,

			'absUploadPath' => $this->absUploadPath,
			'absTemporaryPath' => $this->absTemporaryPath,
			'absTemplatePath' => $this->absTemplatePath
		);
	}
	
	public function setRootPath($rootPath) {
		$this->rootPath = $rootPath;
		
		$this->absUploadPath = $this->_getAbsPath($this->uploadPath);
		$this->absTemplatePath = $this->_getAbsPath($this->templatePath);
		$this->absTemporaryPath = $this->_getAbsPath($this->temporaryPath);
	}

	private function _getAbsPath($relativePath) 
	{
		$abs = '';
		$x = $relativePath[0];
		if ($x == '@') {
			$abs = $this->rootPath . DIRECTORY_SEPARATOR . substr($relativePath, 1);
		} else {
			$sbs = $relativePath;
		}

		return $abs;
	}
}
?>