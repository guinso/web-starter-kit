<?php 
namespace Hx\Database;

class SimpleDb implements DbInterface {
	
	private $pdo;
	
	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function getPdo()
	{
		return $pdo;
	}
	
	public function runSql($sql, array $parameter)
	{
		$stmt = $this->pdo->prepare($sql);
		
		if (empty($parameter))
			return $stmt->execute();
		else 
			return $stmt->execute($parameter);
	}
	
	public function runSqlFile($sqlFilePath)
	{
		if (!file_exists($sqlFilePath))
			Throw new DbException("File $sqlFilePath not found.");
		
		if (!is_readable($sqlFilePath))
			Throw new DbException("File $sqlFilePath is not accessible.");
				
		return $this->runSql(
			file_get_contents($sqlFilePath), 
			array()
		);
	}
}
?>