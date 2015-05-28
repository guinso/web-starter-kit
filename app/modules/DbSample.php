<?php 
class DbSample {

	function getAllTable() {
		$pdo = \Ig\Db::preparePDO('mysql:host=localhost;dbname=information_schema', 'root', '1q2w3e');
	
		$stmt = $pdo->prepare("SELECT TABLE_NAME FROM tables WHERE TABLE_SCHEMA = 'erp' AND TABLE_TYPE = 'BASE TABLE'");
		$stmt->execute();
	
		echo "<br/>";
		foreach($stmt as $row)
			echo "ALTER TABLE `{$row['TABLE_NAME']}` CONVERT TO CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';<br/>";
	}
	
	function optimizeDb() {
		$pdo = \Ig\Db::preparePDO('mysql:host=localhost;dbname=information_schema', 'root', '1q2w3e');
	
		$stmt = $pdo->prepare("SELECT TABLE_NAME FROM tables WHERE TABLE_SCHEMA = 'erp' AND TABLE_TYPE = 'BASE TABLE'");
		$stmt->execute();
	
		echo "<br/>";
		foreach($stmt as $row)
		{
			echo "REPAIR TABLE {$row['TABLE_NAME']};<br/>";
			echo "OPTIMIZE TABLE {$row['TABLE_NAME']};<br/>";
		}
	}
	
}
?>