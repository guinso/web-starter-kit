<?php 
namespace Hx\Database;

Interface DbInterface {
	public function getPdo();

	public function runSql($sql);
	
	public function runSqlFile($sqlFilePath);
}
?>