<?php 
namespace Ig\File;

/**
 * Attachment helper to handle database's attachment record
 * @author chingchetsiang
 *
 */
class Attachment {

	private static $directory;
	
	public static function configure($directory) 
	{
		self::$directory = $directory;
	
		if (!file_exists(self::$directory))
			mkdir(self::$directory, 0775, true);
	}
	
	public static function getDirectory() 
	{
		return self::$directory;
	}
	
	/**
	 * Check targeted GUID is fall within targeted datatable record
	 * @param string $dbTableName	targeted data table name
	 * @param string $guid			targeted attachment GUID
	 * @param string $columnName	targeted data column name
	 */
	public static function isWithinRecord($dbTableName, $guid, $columnName = 'attachment_id') 
	{
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
	
	public static function getById($id) 
	{
		$db = \Ig\Db::getDb();
		
		$row = $db->attachment[$id];
		
		if (empty($row['id'])) {
			return array();
		} else {
			return self::_getFormat($row);
		}
	}
	
	public static function getByGuid($guid) 
	{
		$db = \Ig\Db::getDb();
	
		$row = $db->attachment->where('guid', $guid)->fetch();
	
		if (empty($row['id'])) {
			return null;
		} else {
			return self::_getFormat($row);
		}
	}
	
	/**
	 * Author: Ricky Siow
	 * Upload file to server
	 * @param String $directory
	 * @return Array
	 */
	public static function uploadFile() 
	{
		try {
			$attachment = self::_handleUplaodFile($_FILES['file']);
		
			return $attachment;
			
		} catch (\Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, 'Upload Exception:- ' . $ex->getMessage());
		}
	}
	
	/**
	 * Handle multiple upload files from HTTP post
	 * Not support array of files yet
	 */
	public static function multipleUploadFile() {
		$result = array();
		
		try {
			foreach($_FILES as $key => $f) {
				$attachment = self::_handleUplaodFile($f);
				
				$result[$key] = $attachment;
			}
			
			return $result;
		} catch (\Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, 'Multiple upload Exception:- ' . $ex->getMessage());
		}
		
	}
	
	public static function registerAttachment($keyName) {
		return self::_handleUplaodFile($_FILES[$keyName]);
	}
	
	/**
	 * Register uplaod file into attachment record
	 * @return attachment record or an array of attachment records
	 */
	private static function _handleUplaodFile($file)
	{
		$result = null;
		
		//check input is array fashion or single fashion
		if(is_array($file['name'])) {
			$result = array();
			
			$cnt = COUNT($file['name']);
			for($i = 0; $i < $cnt; $i++) {
				$result[] = self::_registerAttachment(
					$file['error'][$i],
					$file['name'][$i],
					$file['tmp_name'][$i]
				);
			}
		} else {
			$result = self::_registerAttachment(
				$file['error'],
				$file['name'],
				$file['tmp_name']
			);
		}
		
		return $result;
	}
	
	private static function _registerAttachment($error, $name, $tmpName) {
		if ($error > 0) {
			Throw new \Exception('Submit upload file error: ' . $error);
		}
		
		//check directory exist of not
		if(!is_dir(self::$directory)) {
			Throw new \Exception('Targeted directory not found, please contact system admin.');
		}
		
		if(!is_writable(self::$directory)) {
			Throw new \Exception('Targeted directory is not writtable, please contact system admin.');
		}
		
		$guid = uniqid();
		$uniqueFile = $guid . '-' . $name;
		$var = move_uploaded_file(
			$tmpName,
			self::$directory .DIRECTORY_SEPARATOR. $uniqueFile);
		
		if ($var == false) {
			Throw new \Exception('Internal move file failed. Please check directory permission.');
		}
		
		$db = \Ig\Db::getDb();
		$id = \Ig\Db::getNextRunningNumber('attachment');
		$db->attachment->insert(array(
			'id' => $id,
			'filename' => $name,
			'filepath' => $uniqueFile,
			'guid' => $guid
		));
		
		return self::getById($id);
	}
	
	/**
	 * Download File based on file GUID
	 * @param string $fileGuid
	 */
	public static function downloadFile($fileGuid) 
	{
		$db = \Ig\Db::getDb();
	
		$attachment = $db->attachment->where('guid', $fileGuid)->fetch();
	
		if(empty($attachment['id'])) {
			\Ig\Web::sendResponse(404, "File $fileGuid not found in server");
		}
	
		//TODO add user access control features
	
		\Ig\File::getFile(
			self::$directory . DIRECTORY_SEPARATOR  . $attachment['filepath'],
			$attachment['filename']);
	}
	
	private static function _getFormat($row) 
	{
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