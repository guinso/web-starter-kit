<?php 
namespace Hx\Http;

interface OutputInterface {
	
	/**
	 * Send Http response to requestor
	 * @param unknown $data
	 * @param int $statusCode
	 */
	public function generateOuput(Array $data, $statusCode);
}
?>