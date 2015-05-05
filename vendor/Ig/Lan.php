<?php 
namespace Ig;

class Lan {	
	/**
	 * Get server's web language code
	 */
	public static function getLanCode() 
	{
		return \Ig\Db::getKeyValue('lan_code', 'en');
	}
	
	/**
	 * Set server's web language code
	 * @param String $lanCode
	 */
	public static function setLanCode($lanCode = 'en') 
	{
		//check database exist or not
		$db = \Ig\Db::getDb();
		$cnt = $db->lan_code()->where('code = ?', $lanCode)->count('*');
		if ($cnt == 0) {
			\Ig\Web::sendErrorResponse(406, -1, "There is no such language code in database <" . $lanCode . ">");
		} else {
			\Ig\Db::setKeyValue('lan_code', $lanCode);
		}
	}
	
	public static function get() 
	{
		$lanCode = self::getLanCode();
		
		return array(
			'code' => $lanCode		
		);
	}
	
	public static function post() 
	{
		$data = \Ig\Web::getInputData();
		$lanCode = $data['lanCode'];
		
		self::setLanCode($lanCode);
	}
}
?>