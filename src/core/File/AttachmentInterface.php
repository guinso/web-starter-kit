<?php 
namespace Hx\File;

interface AttachmentInterface {
	public function addAttachment($fileName, $filePath);
	
	public function removeAttachment($id);
	
	public function getById($id);
	
	public function getByGuid($guid);
	
	private function _getFormat($row);
}
?>