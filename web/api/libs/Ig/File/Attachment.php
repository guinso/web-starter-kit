<?php 
namespace Ig\File;

/**
 * Attachment helper to handle database's attachment record
 * @author chingchetsiang
 *
 */
class Attachment {

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
		$pdo = \Ig\Db::getPDO();
	
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
		$db = \Ig\Db::getDb();
		
		$row = $db->attachment[$id];
		
		if(empty($row['id']))
			return array();
		else
			return self::_getFormat($row);
	}
	
	public static function getByGuid($guid) {
		$db = \Ig\Db::getDb();
	
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
			\Ig\Web::sendErrorResponse(-1,
					'Submit upload file error: ' . $_FILES["file"]["error"]);
	
		//check directory exist of not
		if(!is_dir(self::$directory))
			\Ig\Web::sendErrorResponse(-1,
					'Directory not found! please set properly.');
	
		if(!is_writable(self::$directory))
			\Ig\Web::sendErrorResponse(-1,
					'Targeted directory is not writtable for server.');
	
		$guid = uniqid();
		$uniqueFile = $guid . '-' . $_FILES["file"]["name"];
		$var = move_uploaded_file(
				$_FILES["file"]["tmp_name"],
				self::$directory .DIRECTORY_SEPARATOR. $uniqueFile);
	
		if($var == false)
			\Ig\Web::sendErrorResponse(-1,
					'Internal move file failed. Please check directory permission.');
	
		$db = \Ig\Db::getDb();
		$id = \Ig\Db::getNextRunningNumber('attachment');
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
		$db = \Ig\Db::getDb();
	
		$attachment = $db->attachment->where('guid', $fileGuid)->fetch();
	
		if(empty($attachment['id']))
			\Ig\Web::sendResponse(404, "File $fileGuid not found in server");
	
		//TODO add user access control features
	
		\Ig\File::getFile(
			self::$directory . DIRECTORY_SEPARATOR  . $attachment['filepath'],
			$attachment['filename']);
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