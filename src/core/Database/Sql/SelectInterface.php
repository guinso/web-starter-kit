<?php 
namespace Hx\Database\Sql;

interface SelectInterface extends Hx\Database\SqlBuilderInterface {
	public function execute($param);
	
	public function generateSql();
	
	public function reset($options);
	
	public function select($column);
	
	public function table($tableName);
	
	public function where($clause);
	
	public function order($column);
	
	public function group($column);
	
	public function join($mode, $table, $clause);
}
?>