<?php 
namespace Hx\Database\Sql;

class Select implements SelectInterface {

	private $param, $db;
	
	private $select, $where, $group, $join, $order, $table, $pgIndex, $pgSize;
	
	public function __construct(\Hx\Database\DbInterface $db)
	{
		$this->db = $db;
		
		$this->reset(self::RESET_PARAM | self::RESET_SQL);
	}
	
	public function execute(array $param)
	{
		return $this->db->runSql(
			$this->generateSql(), 
			empty($param)? 
				$this->param : $param);
	}
	
	public function generateSql()
	{
		$sql = '';
	
		//select
		if (count($this->select) > 0) {
			$tmp = '';
			for ($i=0; $i< count($this->select); $i++) {
				$select = $this->select[$i];
	
				$tmp .= ($i==0? ' ': ' ,') . $select['col'] .
				(empty($select['alias'])? ' ' : ' AS ' . $select['alias']);
			}
				
			$sql .= ' SELECT ' . $tmp;
		} else {
			$sql .= ' SELECT ' . empty($this->alias)? '*' : $this->alias . '.*';
		}
	
		//from
		$sql .= ' FROM ' . $this->table . ' ';
	
		//join
		foreach ($this->join as $join) {
			$sql .= ' ' .
				$join['mode'] . ' ' . $join['table'] . ' ' .
				$join['alias'] . ' ON ' . $join['condition'];
		}
	
		//where
		if (count($this->where) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->where); $i++) {
				$whr = $this->where[$i];
				$tmp .= ($i==0? ' ': ' AND ') . $whr;
			}
				
			$sql .= ' WHERE ' . $tmp;
		}
	
		//group
		if (count($this->group) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->group); $i++) {
				$grp = $this->group[$i];
				$tmp .= ($i==0? ' ' : ' ,') . $grp;
			}
	
			$sql .= ' GROUP BY ' . $tmp;
		}
	
		//order
		if (count($this->oder) > 0) {
			$tmp = '';
			for ($i=0; $i<count($this->oder); $i++) {
				$odr = $this->oder[$i];
				$tmp .= ($i==0? ' ' : ' ,') . $odr['col'] . ' ' . $odr['mode'];
			}
				
			$sql .= ' ORDER BY ' . $tmp;
		}
	
		//pagination
		if ($this->pgIndex != 0 && $this->pgSize != 0) {
			$pgSize = $this->pgSize;
			$offset = $this->pgIndex * $this->pgSize;
			$sql .= " LIMIT $pgSize OFFSET $offset";
		}
	
		return $sql;
	}
	
	public function reset($options)
	{
		if (($options & self::RESET_PARAM) > 0)
			$this->param = array();
		
		if (($options & self::RESET_SQL) > 0)
		{
			$this->table = '';
			
			$this->select = array();
			
			$this->where = array();
			
			$this->group = array();
			
			$this->join = array();
			
			$this->order = array();
			
			$this->pgIndex = 0;
			
			$this->pgSize = 0;
		}
		
		return $this;
	}
	
	public function select($column)
	{
		$this->select[] = $column;
		
		return $this;
	}
	
	public function table($tableName)
	{
		$this->table = $tableName;
		
		return $this;
	}
	
	public function where($clause)
	{
		$this->where[] = $clause;
		
		return $this;
	}
	
	public function order($column)
	{
		$this->order[] = $column;
		
		return $this;
	}
	
	public function group($column)
	{
		$this->group[] = $column;
		
		return $this;
	}
	
	public function join($mode, $table, $clause)
	{
		$this->join[] = array(
			'mode' => $mode,
			'table' => $table,
			'condition' => $clause
		);
		
		return $this;
	}
	
	public function paginate($pageIndex, $pageSize)
	{
		$this->pgIndex = $pageIndex;
		
		$this->pgSize = $pageSize;
		
		return $this;
	}
	
	public function param($paramName, $value)
	{
		$this->param[$paramName] = $value;
		
		return $this;
	}
}
?>