<?php 
namespace Hx\Persistent;

/**
 * Interface to access persistent medium such as Mysql, Xml, INI, etc.
 * @author chingchetsiang
 *
 */
interface GatewayInterface {
	public function read(Array $param);
	
	public function create(Array $record);
	
	public function update(Array $record);
	
	public function delete(Array $record);
	
	public function isAccessible();
}
?>