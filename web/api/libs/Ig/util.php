<?php
class Util {
	private static $uploadPath;
	private static $tempPath;
	private static $tplPath;
	
public static function configure(
		$uploadPath, $tempPath, $tplPath) {

	//check directory exists and accessable
	self::$uploadPath = $uploadPath;
	self::$tempPath = $tempPath;
	self::$tplPath = $tplPath;
	
	if(!file_exists(self::$uploadPath))
		mkdir(self::$uploadPath, 0775, true);
	
	if(!file_exists(self::$tempPath))
		mkdir(self::$tempPath, 0775, true);
	
	if(!file_exists(self::$tplPath))
		mkdir(self::$tplPath, 0775, true);
}
	
/**
 * Send whole HTTP response to client and exit script execution
 * @param Integer $status	HTTP return status code. e.g. 200, 404, 500
 * @param String $body	HTTP body content
 * @param String $content_type	HTTP return content type
 */	
public static function sendResponse($status = 200, $body = '', $content_type = 'text/html') {

	$status_header = 'HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status);
	// set the status
	header($status_header);
	// set the content type
	header('Content-type: ' . $content_type . '; charset=utf-8');

	// pages with body are easy
	if($body != '')
	{
		// send the body
		echo $body;
		exit;
	}
	// we need to create the body if none is passed
	else
	{
		// create some body messages
		$message = '';

		// this is purely optional, but makes the pages a little nicer to read
		// for your users.  Since you won't likely send a lot of different status codes,
		// this also shouldn't be too ponderous to maintain
		switch($status)
		{
			case 401:
				$message = 'You must be authorized to view this page.';
				break;
			case 404:
				$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
				break;
			case 500:
				$message = 'The server encountered an error processing your request.';
				break;
			case 501:
				$message = 'The requested method is not implemented.';
				break;
		}

		// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
		$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

		// this should be templatized in a real-world solution
		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>' . $status . ' ' . self::getStatusCodeMessage($status) . '</title>
		</head>
		<body>
		<h1>' . self::getStatusCodeMessage($status) . '</h1>
		<p>' . $message . '</p>
		<hr />
		<address>' . $signature . '</address>
		</body>
		</html>';

		echo $body;
		exit;
	}
}

public static function sendErrorResponse(
	$internalErrorCode, $errorMessage, $data = null, $statusCode = 406) {
	
	$err = array(
		'code' => $internalErrorCode,
		'msg' => $errorMessage,
		'attachment' => $data
	);
	
	self::sendResponse($statusCode, json_encode($err));
}

public static function getStatusCodeMessage($status) {
	// these could be stored in a .ini file and loaded
	// via parse_ini_file()... however, this will suffice
	// for an example
	$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
	);

	return (isset($codes[$status])) ? $codes[$status] : '';
}

/**
 * Get JSON object from input data body, into array data
 * @return Array	associate array
 */
public static function getInputData() {
	$rawJsonString = file_get_contents('php://input');
	$entityBody = json_decode($rawJsonString, true);
	
	if(is_array($entityBody))
		self::recurArrayTrimString($entityBody);
	
	return $entityBody;
}

private static function recurArrayTrimString(array &$arr) {
	$cnt = count($arr);
	foreach($arr as $k => $v) {
		if(is_string($v))
			$arr[$k] = trim($v);
		else if(is_array($v)) {
			self::recurArrayTrimString($arr[$k]);
		}
	}
}

/**
 * Author: Ricky Siow
 * Upload file to server
 * @param String $directory
 * @return Array
 */
public static function uploadFile($directory = NULL) {
	if(empty($directory)) {
		$directory = self::$uploadPath;
	}

	if ($_FILES["file"]["error"] > 0) {
		$var = array(
			'code' => -1,
			'msg' =>'Return Code:'. $_FILES["file"]["error"]
		);

		util::sendResponse(406,json_encode($var));
	}
	
	//check directory exist of not
	if(!is_dir($directory)) {
		$err = array(
			'code' => -1,
			'msg' => "Directory $directory not found in server."
		);
			
		Util::sendResponse(403, json_encode($err));
	}
	
	$uniqueFile = uniqid() . '-' . $_FILES["file"]["name"];
	$var = move_uploaded_file($_FILES["file"]["tmp_name"], 
		$directory .'/'. $uniqueFile);

	if($var == false) {
		$err = array(
			'code' => -1,
			'msg' => 'Internal move file failed.'
		);

		util::sendResponse(406,json_encode($err));
	}

	return array(
		'fileName'=> $_FILES["file"]["name"],
		'filePath'=> $directory .'/'. $uniqueFile
	);
}

public static function getServerUUID() {
	return self::getKeyValue('server_id', uniqid());
}

/**
 * Recursive a directory and excecute anonymous function once file name matched regular expression
 * @param String $baseDir	start point of directory user wish to travase
 * @param Closure $closure	anonymous function provided by user, function($filePath)...
 * @param String $filePattern	PEARL style regular expression expression
 * @param Array $ignoreDir	directory which wanna ignore from the search
 */
public static function recursiveDir(
		$baseDir,
		$closure,
		$filePattern = '/.+/',
		$ignoreDir = array('.svn', '.', '..')) {

	$dirHandle = opendir($baseDir);
	$keepLoop = true;

	while($keepLoop) {

		$file = readdir($dirHandle);

		$isIgnoreDir = false;
		foreach($ignoreDir as $igr) {
			if($file == $igr)
				$isIgnoreDir = true;
		}

		if(!$isIgnoreDir && $file) {
			$tmpDir = $baseDir . '/' . $file;

			if(is_dir($tmpDir . '/')) {
				self::recursiveDir($tmpDir, $closure, $filePattern, $ignoreDir);
			} else {
				//check file path's pattern via regular expression
				if(preg_match($filePattern, $file) == 1) {
					$closure($tmpDir);
				}
			}
		}

		$keepLoop = !empty($file);
	}
}

/**
 * Get current Date
 */
public static function getDate() {
	return date('Y-m-d');
}

/**
 * Get current Date and Time
 */
public static function getDatetime() {
	return date('Y-m-d H:i:s');
}

/**
 * Send file to client for downloading purposes
 * @param String $filepath	absolute file path
 * @param String $filename	return file name
 * @param Boolean $compress	compress file before download flag
 */
public static function downloadFile($filepath, $filename, $compress = false) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$finfo_mime = finfo_file($finfo, $filepath);

	header('Content-Type: '. $finfo_mime);
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Content-Length: ' . filesize($filepath));
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

	//TODO support resumable features

	//TODO support compress file before sending download

	readfile($filepath);
}

/**
 * Get date based on week span, which will always be on Monday
 * @param integer $weekSpan
 */
public static function getDateFromWeekSpan($weekSpan) {
	$w = $weekSpan % 52;
	if($w == 0)
		$w = 52;

	$y = floor($weekSpan / 52);

	$date = new DateTime();
	$date->setISODate($y, $w);
	return $date->format('Y-m-d');
}

/**
 * Calculate weekspan based on ISO-8601
 * @param String $date
 */
public static function getWeekSpan($date) {
	$timestamp = strtotime($date);

	$w = date('W', $timestamp);
	$y = date('o', $timestamp);

	return $w + $y * 52;
}

/**
 * backward date to Monday
 * @param String $date
 */
public static function offsetToMonday($date, $dateformat = 'Y-m-d') {
	$datetime = new DateTime($date);
	$diff = date('N', strtotime($date)) - 1;

	$dateInterval = new DateInterval("P".$diff."D");
	$datetime->sub($dateInterval);

	return $datetime->format($dateformat);
}

public static function offsetDate($date, $diff, $dateformat = 'Y-m-d') {
	$datetime = new DateTime($date);

	if($diff < 0) {
		$diff *= -1;
		$dateInterval = new DateInterval("P".$diff."D");
		$datetime->sub($dateInterval);
	} else {
		$dateInterval = new DateInterval("P".$diff."D");
		$datetime->add($dateInterval);
	}

	return $datetime->format($dateformat);
}

public static function getLastDayOfMonth($date) {
	return date('Y-m-t', strtotime($date));
}

public static function getDateRange($month, $year) {
	$fromDate = date('Y-m-d', strtotime($year . '-' . $month . '-1'));
	$toDate = self::getLastDayOfMonth($year . '-' . $month . '-1');
	$carryFwdDate = self::offsetDate($fromDate, -1);
	
	return array(
		'fromDate' => $fromDate,
		'toDate' => $toDate,
		'carryFwdDate' => $carryFwdDate
	);
}

/**
 * Get predefined temporary directory
 * NOTE: make sure the directory is readable and writable to Apache web-server (www-data)
 */
public static function getTemporaryDirectory() {
	return self::$tempPath;
}

/**
 * Get predefined template directory
 * NOTE: make sure the directory is readable to Apache web-server (www-data)
 */
public static function getTemplateDirectory() {
	return self::$tplPath;
}

/**
 * Get server host name with support reverse proxy request as well
 * @return string
 */
public static function getServerHostname() {
	$hostname = '';
	if(isset($_SERVER['HTTP_X_FORWARDED_HOST']))
		$hostname = $_SERVER['HTTP_X_FORWARDED_HOST'];
	else if(isset($_SERVER['SERVER_HOST']))
		$hostname = $_SERVER['SERVER_HOST'];
	else
		$hostname = $_SERVER['HTTP_HOST'];
	
	$protocal = $_SERVER['REQUEST_SCHEME'] . '://';
	
	return $protocal . $hostname;
}

/**
 * Get server full URL with support reverse proxy request as well
 * @return string
 */
public static function getServerUrl() {
	$uri = dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT'])));

	return self::getServerHostname() . $uri;
}

/**
 * Check is associative array or not
 * @param unknown $array
 * @return boolean
 */
function isAssociatoveArray($array) {
	//skip this step to fastern process but resource wise, is expensive
	$array = array_keys($array); 
	
	return ($array !== array_keys($array));
}

}
?>