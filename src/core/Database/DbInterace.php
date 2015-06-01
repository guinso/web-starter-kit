<?php 
namespace Hx\Database;

Interface DbInterface {
	
	/**
	 * Get PDO instance
	 */
	public function getPdo();

	/**
	 * Run SQL script from string
	 * @param string $sql
	 */
	public function runSql($sql, array $param);
	
	/**
	 * Run SQL script from file
	 * @param string $sqlFilePath	<p>file path of the SQL script</p>
	 */
	public function runSqlFile($sqlFilePath);
}
?>