<?php 
namespace Hs\Database\Sql;

interface UpdateInterface extends Hx\Database\SqlBuilderInterface {
	public function execute($param);
	
	public function generateSql();
	
	public function table($tableName);
	
	public function column($name, $value);
	
	public function where($clause);
}
?>