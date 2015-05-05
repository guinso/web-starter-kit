<?php 
namespace Hx\Database\Sql;

interface InsertInterface extends Hx\Database\SqlBuilderInterface {
	public function execute($param);
	
	public function generateSql();
	
	public function table($tableName);
	
	public function column($name, $value);
}
?>