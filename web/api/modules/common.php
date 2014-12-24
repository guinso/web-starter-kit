<?php 
class Common {
	public static function checkAndDownloadFile(
			$guid, $dbtable,
			$dbColumn = 'attachment_id', $functionNames = array()) {
	
		$fileWithIn = FileUtil::isWithinRecord($dbtable, $guid, $dbColumn);
		$authorize = AuthorizeUtil::isAuthorize2($functionNames);
	
		if($fileWithIn && $authorize)
			FileUtil::downloadFile($guid);
		else
			Util::sendErrorResponse(-1,
					"Request rejected, you have no rights to download file", null, 401);
	}
}
?>