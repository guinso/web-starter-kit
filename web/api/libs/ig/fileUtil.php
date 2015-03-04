<?php 
class FileUtil {
	
private static $directory;
	
public static function configure($directory) {
	self::$directory = $directory;
	
	if(!file_exists(self::$directory))
		mkdir(self::$directory, 0775, true);
}

public static function getDirectory() {
	return self::$directory;
}

/**
 * Check targeted GUID is fall within targeted datatable record
 * @param string $dbTableName	targeted data table name
 * @param string $guid			targeted attachment GUID
 * @param string $columnName	targeted data column name
 */
public static function isWithinRecord($dbTableName, $guid, $columnName = 'attachment_id') {
	$pdo = Util::getPDO();
	
	$sql = "SELECT a.* FROM $dbTableName a 
			JOIN attachment b ON a.$columnName = b.id 
			WHERE b.guid = :guid";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':guid', $guid);
	
	$stmt->execute();
	$cnt = $stmt->rowCount();
	
	return $cnt > 0;
}

public static function getById($id) {
	$db = Util::getDb();
	
	$row = $db->attachment[$id];
	
	if(empty($row['id']))
		return array();
	else
		return self::_getFormat($row);
}

public static function getByGuid($guid) {
	$db = Util::getDb();

	$row = $db->attachment->where('guid', $guid)->fetch();

	if(empty($row['id']))
		return null;
	else
		return self::_getFormat($row);
}

/**
 * Author: Ricky Siow
 * Upload file to server
 * @param String $directory
 * @return Array
 */
public static function uploadFile() {
	if ($_FILES["file"]["error"] > 0)
		Util::sendErrorResponse(-1, 
			'Submit upload file error: ' . $_FILES["file"]["error"]);

	//check directory exist of not
	if(!is_dir(self::$directory))
		Util::sendErrorResponse(-1, 
			'Directory not found! please set properly.');
	
	if(!is_writable(self::$directory))
		Util::sendErrorResponse(-1, 
			'Targeted directory is not writtable for server.');

	$guid = uniqid();
	$uniqueFile = $guid . '-' . $_FILES["file"]["name"];
	$var = move_uploaded_file(
		$_FILES["file"]["tmp_name"],
		self::$directory .DIRECTORY_SEPARATOR. $uniqueFile);

	if($var == false)
		Util::sendErrorResponse(-1, 
			'Internal move file failed. Please check directory permission.');

	$db = Util::getDb();
	$id = Util::getNextRunningNumber('attachment');
	$db->attachment->insert(array(
		'id' => $id,
		'filename' => $_FILES["file"]["name"],
		'filepath' => $uniqueFile,
		'guid' => $guid
	));
	
	return self::getById($id);
}

/**
 * Download File based on file GUID
 * @param string $fileGuid
 */
public static function downloadFile($fileGuid) {
	$db = Util::getDb();
	
	$attachment = $db->attachment->where('guid', $fileGuid)->fetch();
	
	if(empty($attachment['id']))
		Util::sendResponse(404, "File $fileGuid not found in server");
	
	//TODO add user access control features
	
	self::getFile(
		self::$directory . DIRECTORY_SEPARATOR  . $attachment['filepath'], 
		$attachment['filename']);
}
	
/**
 * Send file to client for downloading purposes
 * @param String $filepath	absolute file path
 * @param String $filename	return file name
 * @param Boolean $compress	compress file before download flag
 */
public static function getFile($filepath, $filename, $compress = false) {
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
 * Remove file or directory recursively
 * @param string $dir	directory path OR file path
 */
public static function removeFile($dir) {
	if(is_file($dir))
		unlink($dir);
	else if(is_dir($dir)) {
		foreach(glob($dir . '/*') as $file)
			self::removeFile($file);
			
		rmdir($dir);
	}
}

private static function _getFormat($row) {
	return array(
		'id' => $row['id'],
		'filename' => $row['filename'],
		'filepath' => $row['filepath'],
		'guid' => $row['guid'],
		'checksum' => $row['checksum'],
		'mime' => $row['mime']
	);
}

}
?>