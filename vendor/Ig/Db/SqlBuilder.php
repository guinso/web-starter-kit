<?php 
namespace Ig\Db;

class SqlBuilder {
	private $pdo;
	private $selects, $table, $alias, $whrs, $odrs, $params, $joins, $grps, $pgIndex, $pgSize;
	
	public function __construct(\PDO $pdo, $table = '', $alias = '') 
	{
		if (empty($pdo))
			throw new \Exception("You must provide valid PDO parameter");
		
		self::clear();
		
		$this->pdo = $pdo;
		$this->table = $table;
		$this->alias = $alias;
	}
	
	public function clear() 
	{
		$this->table = '';
		$this->alias = '';
		$this->selects = array();
		$this->whrs = array();
		$this->odrs = array();
		$this->params = array();
		$this->joins = array();
		$this->grps = array();
		
		$this->pgIndex = -1;
		$this->pgSize = -1;
		
		return $this;
	}
	
	public function table($table, $alias = '') 
	{
		$this->table = $table;
		$this->alias = $alias;
		
		return $this;
	}
	
	public function select($selectCol, $alias = '') 
	{
		$this->selects[] = array(
			'col' => $selectCol,
			'alias' => $alias
		);
		
		return $this;
	}
	
	public function where($condition) 
	{
		$this->whrs[] = $condition;
		
		return $this;
	}
	
	/**
	 * Add order condition
	 * @param string $orderCol
	 * @param string $mode ASC, DESC
	 */
	public function order($orderCol, $mode = '') 
	{
		if(empty($mode)) {
			$mode = 'ASC';
		}
		
		$this->odrs[] = array(
			'col' => $orderCol,
			'mode' => $mode
		);
		
		return $this;
	}
	
	public function group($groupCol) 
	{
		$this->grps[] = $groupCol;
		
		return $this;
	}
	
	/**
	 * Add join statement
	 * @param string $joinMode	e.g. LEFT JOIN, RIGHT JOIN, INNER JOIN, JOIN
	 * @param string $joinTable
	 * @param string $alias
	 * @param string $condition
	 */
	public function join($joinMode, $joinTable, $alias, $condition) 
	{
		$this->joins[] = array(
			'mode' => $joinMode,
			'table' => $joinTable,
			'alias' => $alias,
			'condition' => $condition
		);
		
		return $this;
	}
	
	public function param($alias, $value) 
	{
		$this->params[$alias] = $value;
		
		return $this;
	}
	
	public function paginate($pgIndex, $pgSize) 
	{
		$this->pgIndex = $pgIndex;
		$this->pgSize = $pgSize;
	}
	
	/**
	 * Generate SQL statement
	 * @return string
	 */
	public function sql() 
	{
		$sql = '';
		
		//select
		if (count($this->selects) > 0) {
			$tmp = '';
			for ($i=0; $i< count($this->selects); $i++) {
				$select = $this->selects[$i];
				
				$tmp .= ($i==0? ' ': ' ,') . $select['col'] . 
					(empty($select['alias'])? ' ' : ' AS ' . $select['alias']);
			}
			
			$sql .= ' SELECT ' . $tmp;
		} else {
			$sql .= ' SELECT ' . empty($this->alias)? '*' : $this->alias . '.*';
		}
		
		//from
		$sql .= ' FROM ' . $this->table . ' ' . $this->alias;
		
		//join
		foreach ($this->joins as $join) {
			$sql .= ' ' . 
				$join['mode'] . ' ' . $join['table'] . ' ' . 
				$join['alias'] . ' ON ' . $join['condition'];
		}
		
		//where
		if (count($this->whrs) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->whrs); $i++) {
				$whr = $this->whrs[$i];
				$tmp .= ($i==0? ' ': ' AND ') . $whr;
			}
			
			$sql .= ' WHERE ' . $tmp;
		}
		
		//group
		if (count($this->grps) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->grps); $i++) {
				$grp = $this->grps[$i];
				$tmp .= ($i==0? ' ' : ' ,') . $grp;
			}
				
			$sql .= ' GROUP BY ' . $tmp;
		}
		
		//order
		if (count($this->odrs) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->odrs); $i++) {
				$odr = $this->odrs[$i];
				$tmp .= ($i==0? ' ' : ' ,') . $odr['col'] . ' ' . $odr['mode'];
			}
			
			$sql .= ' ORDER BY ' . $tmp;
		}
		
		//pagination
		if ($this->pgIndex != -1 && $this->pgSize != -1) {
			$pgSize = $this->pgSize;
			$offset = $this->pgIndex * $this->pgSize;
			$sql .= " LIMIT $pgSize OFFSET $offset";
		}
		
		return $sql;
 	}
 	
 	public function execute($sql = null, $params = null) {
 		if (empty($sql))
 			$sql = $this->sql();
 		
 		if (empty($params))
 			$params = $this->params;
 		
 		$stmt = $this->pdo->prepare($sql);
 		$stmt->execute($params);
 		
 		return $stmt;
 	}
}
?>