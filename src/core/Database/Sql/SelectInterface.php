<?php 
namespace Hx\Database\Sql;

interface SelectInterface {
	const RESET_SQL = 1;
	
	const RESET_PARAM = 2;
	
	public function execute($param = null);
	
	public function generateSql();
	
	public function reset($options);
	
	public function select($column);
	
	public function table($tableName);
	
	public function where($clause);
	
	public function order($column);
	
	public function group($column);
	
	public function join($mode, $table, $clause);
	
	public function paginate($pageIndex, $pageSize);
	
	public function param($paramName, $value);
}
?>