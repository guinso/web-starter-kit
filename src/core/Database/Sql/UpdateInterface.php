<?php 
namespace Hx\Database\Sql;

interface UpdateInterface {
	
	const RESET_SQL = 1;
	
	const RESET_PARAM = 2;
	
	public function execute(array $param = null);
	
	public function generateSql();
	
	public function table($tableName);
	
	public function column($name, $value);
	
	public function where($clause);
	
	public function reset($options);
}
?>