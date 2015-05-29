<?php 
class Common {
	public static function checkAndDownloadFile(
			$guid, $dbtable,
			$dbColumn = 'attachment_id', $functionNames = array()) {
	
		$fileWithIn = \Ig\File\Attachment::isWithinRecord($dbtable, $guid, $dbColumn);
		$authorize = \Ig\Authorize::isAuthorize2($functionNames);
	
		if($fileWithIn && $authorize)
			\Ig\File\Attachment::downloadFile($guid);
		else
			\Ig\Web::sendErrorResponse(-1,
					"Request rejected, you have no rights to download file", null, 401);
	}
}
?>