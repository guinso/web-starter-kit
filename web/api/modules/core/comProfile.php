<?php 
class ComProfileRest {
	public static function get() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to view company profile.");
		
		try {
			return ComProfile::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			Util::sendErrorResponse(-1, "You are not authorize to update company profile.");

		try {
			$data = Util::getInputData();
			ComProfile::update($data);
			
			return ComProfile::get();
		} catch(Exception $ex) {
			Util::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}

class ComProfile {
	public static function get() {
		$result = Util::getKeyValue('com-profile', array(
			'name' => '', //display name on system
			'comName' => '', //company name which register on SSM
			'addr' => '',
			'tel' => '',
			'fax' => '',
			'regNo' => '',
			'gstNo' => '',
			'email' => '',
			'website' => '',
			'logoGuid' => '' //company logo store in <attachment> by using guid to track
		));
		
		//x $result['logo'] = FileUtil::getByGuid($result['logoGuid']);
		
		return $result;
	}
	
	public static function update($param) {
		Util::setKeyValue('com-profile', array(
			'name' => $param['name'], 
			'comName' => $param['comName'], 
			'addr' => $param['addr'],
			'tel' => $param['tel'],
			'fax' => $param['fax'],
			'regNo' => $param['regNo'],
			'gstNo' => $param['gstNo'],
			'email' => $param['email'],
			'website' => $param['website'],
			'logoGuid' => $param['logoGuid']
		));
	}
}
?>