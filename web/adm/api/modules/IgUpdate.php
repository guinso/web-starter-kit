<?php 
interface IgUpdate {
	//Get update description, return in array string
	public static function getMessage();

	//Execute update script, throw exception if encounter abnormality
	public static function runScript();
}

class IgModUpdate {
	
	//Discover all revision found in source code
	public static function getRevision() {
		$updateDir = ADM_API_DIR . DIRECTORY_SEPARATOR . 'update';
		
		$result = array();
		$arrs = glob(
			$updateDir . DIRECTORY_SEPARATOR . 
			'revision'. DIRECTORY_SEPARATOR .'*'); // --> root-path/update/revision/*
		
		foreach($arrs as $arr) {
			if(is_dir($arr)) {
				$raw = explode('/', $arr);
				$revision = $raw[count($raw) - 1];
				if(preg_match('/^[1-9][0-9]*$/', $revision)) {
					$revision = intval($revision);
					$className = 'IgUpdate' . $revision;
					
					if(class_exists($className))
						throw new Exception("IgUpdate discover: Class $className already existed.", -1);
					
					$path =  $arr . DIRECTORY_SEPARATOR . $className . '.php';
					include_once $path;

					$result[$revision] = array(
						'className' => $className,
						'revision' => $revision,
						'msg' => $className::getMessage()
					);
				}
			}
		}
		
		ksort($result);
		
		return $result;
	}

	public static function getAvailableUpdate() {
		$version = \Ig\Db::getKeyValue('update-ver', 0);
		$revisions = self::getRevision();
		
		$result = array();
		foreach($revisions as $rev => $value) {
			if($version < $rev) {
				$result[] = $value;
			}
		}
		
		return $result;
	}
	
	/**
	 * Run update
	 * @param number $maxRev
	 */
	public static function executeUpdate($maxRev = 0) {
		$version = \Ig\Db::getKeyValue('update-ver', 0);
		$updates = self::getAvailableUpdate();
		
		foreach($updates as $update) {
			if($maxRev == 0 || $update['revision'] <= $maxRev) {
				$className = $update['className'];
				
				try {
					$className::runScript();
					\Ig\Db::setKeyValue('update-ver', $update['revision']);
				} catch(Exception $ex) {
					\Ig\Web::sendErrorResponse(-3, "Update process fail: " . $ex->getMessage());
					break;
				}
			}
		}
		
		return array('version' => \Ig\Db::getKeyValue('update-ver', 0));
	}
 }
?>