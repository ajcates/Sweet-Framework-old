<?
/*
abstract class dbDriver {
	abstract function getField($id);
	abstract function getNumFields();
	abstract function gettingRows();
	abstract function getFieldCount();
	abstract function getFieldTable($id);
	
	abstract function query($sql);
	abstract function getErrors();
	abstract function escape($value);
}
*/

class Query extends App {
	
	private $_mode = 'select';
	private $_whereData = array();
	private $_selectData;
	private $_joinData;
	private $_setData;
	private $_joins;
	private $_joinOns;
	static public $_fromValue;
	private $_whereValue;
	private $_limit;
	private $_orderBy;
	private $_updateValue;
	private	$_insert;
	private $_selectFunction;
	
	static protected $_driver;
	static $last = '';
		
	public function __construct() {
		//$this->getLibrary('Config');
		$this->_driver = $this->lib('databases/Databases')->getCurrentDb();
		
	}
	
	public function connect($config=null) {
		/*
		if(!isset($config)) {
			$config = $this->lib->Config->get('Site', 'database');
		}
		if(is_string($config)) {
			$config = $this->lib->Config->get('Databases', $config);
		}
		//@todo enable support for multiple drivers at one time
		self::$_driver = $this->getLibrary('Databases/Drivers/' . $config['driver'] . '.php');
		self::$_driver->connect($config);
		*/
	}
	
	function insert($values) {
		$this->_insert = $values;
		//D::show($this->_insert, 'Incert');
		return $this;
	}
	
	function into($tableName) {
		$this->_mode='insert';
		return $this->from($tableName);
	}
	
	public function reset() {
		$this->_mode = 'select';
		self::$_fromValue = null;
		$this->_whereData = array();
		$this->_selectData = null;
		$this->_joinData = null;
		$this->_setData = null;
		$this->_joins = null;
		$this->_joinOns = null;
		$this->_whereValue = null;
		$this->_limit = null;
		$this->_orderBy = null;
		$this->_updateValue = null;
		$this->_insert = null;
		$this->_selectFunction = null;
	}

	public function select($cols='*') {
		if(is_string($cols)) {
			$this->_selectData = func_get_args();
		} else {
			$this->_selectData = (array)$cols;
		}
		return $this;
	}
	
	function from($val) {
/*
		join(', ',
			f_keyMap(
				function($v, $k) {
					if(is_string($k)) {
						return $v . ' AS ' $v;
					}
					return $v;
				},
				$val
			)
		);
*/
			
		if(is_array($val)) {
			Query::$_fromValue = f_keyMap(
				function($v, $k) {
					if(is_string($k)) {
						return $v . ' AS ' . $k;
					}
					return $v;
				},
				$val
			);
		} else {
			Query::$_fromValue = $val;
		}
		
		//Query::$_fromValue = f_flatten(func_get_args());
		
		
		
		return $this;
	}
	public function where() {
		$this->_whereValue = func_get_args();
//		D::log($this->_whereValue, 'where val');
		return $this;
	}

	public function update($value) {
		$this->_mode = 'update';
		Query::$_fromValue = f_flatten(func_get_args());
		return $this;
	}
	function delete() {
		$this->_mode = 'delete';
		return $this;
	}

	function set($value) {
		$this->_setValue = $value;
		return $this;
		
	}
	
	function count() {
		$this->_selectFunction = 'count';
		return $this;
	}
	
	function join($values) {
//		D::log($values, 'Join Values');
		if(!array_key_exists(0, $values)) {
			$values = array($values);
		}
		$this->_joins = $values;
		return $this;
	}
	
	public function orderBy($value=null) {
		$this->_orderBy = $value;
		return $this;
	}

	public function limit() {
		$this->_limit = array_reverse(f_flatten(func_get_args()));
		return $this;
	}
	
	function _buildSelect() {
		if(isset($this->_selectFunction)) {
			return $this->_selectFunction .'(*)';
		}
		return join(', ', f_keyMap(
			function($v, $k) {
				if(is_string($k)) {
					if(is_array($v)) {
						return $k . '(' . join(',', $v) . ')';
					}
					return $k . ' AS \'' . $v . '\'';
				}
				return $v;
			},
			$this->_selectData
		));
	}
	
	static function _buildWhere($group, $groupOperator='AND', $escape=true) {
		//"Bitch I'll pick the world up and I'ma drop it on your f*ckin' head" - Lil Wayne.
		$keys = array_keys($group);
		if(is_int(f_last($keys)) && is_string(f_last($group))) {
			$operator = f_last($group);
			$group = f_chop($group);
		} else {
			$operator = '=';
		}
		if(is_int(f_first($keys)) && is_string(f_first($group))) {
			$groupOperator = f_first($group);
			$group = f_rest($group);
		}
		$builtArray = f_keyMap(
			function($value, $key) use($groupOperator, $operator, $escape) {
				if(is_int($key) && is_array($value)) {
					//Group? @todo double check to make sure OR is working
					$bWhere = Query::_buildWhere($value, $groupOperator, $escape);
					if(!empty($bWhere)) {
						return '(' . "\n" . $bWhere . ')';
					} else {
						return null;
					}
				}
				if(is_string($key)) {
					static $escapeFunc = 'Query::nullEscape';
					if(!$escape) {
						$escapeFunc = 'nothing';
					}
					//column
					if(is_array($value)) {
						//IN or group
						return Query::escape($key) . ' IN (' . join(', ', array_map($escapeFunc, $value)) . ')'; 
					} else {
						$value = call_user_func($escapeFunc, $value);
						if($value === 'null') {
							if($operator == '=') {
								$operator = 'IS';
							} else {
								$operator = 'IS NOT';
							}
							
						}
						return Query::escape($key) . ' ' . $operator . ' ' . $value;
					}
				}
			},
			$group
		);
//		D::log($builtArray, 'built array');
		
		if(!empty($builtArray)) {
			return join(' ' . $groupOperator . ' ', array_filter($builtArray));
		}
	}
	
	function _buildOrderBy() {
		if(isset($this->_orderBy)) {
			return "\n" . ' ORDER BY ' . join(' , ', f_keyMap(
				function($v, $k) {
					return Query::escape($k) . ' ' . Query::escape($v);
				},
				$this->_orderBy
			));
		}
	}
	
	function _buildJoins() {
	 	if(!empty($this->_joins)) {
		 	return join(' ', f_map(
		 		function($join) {
		 	 	 	return join(' ', f_keyMap(
						function($joinSets, $jTable) {
							//$jTableName = f_last(explode(' ', $jTable));
							return "\n" . ' LEFT JOIN ' . $jTable . ' ON ' . Query::_buildWhere($joinSets, 'AND', false);
						},
						$join
					));
				},
				$this->_joins
			));
	 	}
	}
	
	function _buildLimit() {
		if(!empty($this->_limit)) {
			return "\n" . ' LIMIT ' . join(', ', $this->_limit);
		}
	}
	
	function _buildSet($values, $separator='=') {
		return join(
			', ',
			f_keyMap(
				function($v, $k) use ($separator) {
					return Query::escape($k) . ' ' . $separator . Query::nullEscape($v);
				},
				$values
			)
		);
	}
	function _buildWhereString($values) {
		if(empty($values)) {
			return '';
		}
		$whereContent = $this->_buildWhere($values);
		if(!empty($whereContent)) {
//			D::log($whereContent, 'where content');
			return "\n" . ' WHERE ' . $whereContent;
		}
	}
	function _build() {
		//puts all the stuff together in a magic happy fashion.
		$sqlString = '';
		switch ($this->_mode) {
			case 'select':
				//adds in our select values
				D::log('hello');
				$sqlString = 'SELECT ' . $this->_buildSelect() . "\n" . ' FROM ' . join(', ', (array)Query::$_fromValue) . $this->_buildJoins() . "\n" .  $this->_buildWhereString($this->_whereValue) . $this->_buildOrderBy() . $this->_buildLimit();
				break;
			case 'update':
				$sqlString = 'UPDATE ' . f_first(Query::$_fromValue) . "\n" . ' SET ' . $this->_buildSet($this->_setValue) . $this->_buildWhereString($this->_whereValue);
				break;
			case 'insert':
				/*
				f_reduce(
					function($a, $b) {
						return array_merge(array_keys((array)$b), array_keys((array)$a));
					},
					$this->_insert
				);
				*/
				if(!is_array(f_first($this->_insert) )) {
					$this->_insert = array($this->_insert);
				}
				$cols = array_map(function($v) { return Query::nullEscape($v, '`');}, array_keys(array_reduce($this->_insert, 'array_merge_recursive', array())));
				
				
				$sqlString = 'INSERT INTO ' . f_first((array) Query::$_fromValue) . ' (' . join(', ', $cols) . ') VALUES ' . join(', ', f_map(
					function($v) use($cols) {
						return '(' . join(',', f_map(
							function ($i) use ($v) {
								$i = substr($i, 1, -1);
								if(isset($v[$i])) {
									return Query::nullEscape($v[$i]);
								} else {
									return 'null';
								}
							},
							$cols
						)) . ')';
					},
					D::log($this->_insert, 'raw incert')
				));
				break;
			case 'delete':
				$sqlString = 'DELETE FROM ' . join(', ', Query::$_fromValue) . $this->_buildWhereString($this->_whereValue);
				break;
		}
		$this->sql = $sqlString;
		D::log($this->sql, 'SQL Build');
		return $this->sql;
	}
	
	public function go() {
		self::$last = $this->_build();
		$this->reset();
		if(!$this->_driver->query(self::$last)) {
			return false;
		}
		return $this;
	}
	
	public function results($type='object') {
		self::$last = $this->_build();
		$this->reset();
		return $this->_driver->query(self::$last, $type);
	}
	
	public function getDriver() {
		return $this->_driver;
	}
	public static function nullEscape($var, $sep="'") {
		if(!isset($var)) {
			return 'null';
		}
		if(is_bool($var) || is_int($var)) {
			return $var;
		}
		return $sep . self::escape($var) . $sep;
	}
	
	public static function escape($var) {
		//Databases::f('query', array($sql, $type));
		//@todo change this.
		if(is_bool($var) || is_int($var)) {
			return $var;
		}
		return mysql_escape_string($var);
	}
	
	
	/*
	->where()
		$this->select('*')->where(array('item' => 5)) //SELECT * FROM table WHERE item = '5'
		$this->select('*')->where(array('item' => 5, 'thing' => 'what')) //SELECT * FROM table WHERE item = '5' AND thing = 'what'
		$this->select('*')->where(array('OR', 'item' => 5, 'thing' => 'what')) //SELECT * FROM table WHERE item = '5' OR thing = 'what'
		$this->select('*')->where(array('item' => 5, 'thing' => 'what', '!=')) //SELECT * FROM table WHERE item != '5' OR thing != 'what'
		$this->select('*')->where(array('item' => array('what', 'who')) //SELECT * FROM table WHERE item IN ('what', 'who')
	
	->join()
		$this->select('*')->from('Club_RnD.dbo.Posts')->join('Club_RnD.dbo.Comments', array('Club_RnD.dbo.Posts.comments' => 'Club_RnD.dbo.Comments.id'))
		//SELECT * FROM Club_RnD.dbo.Posts LEFT JOIN Club_RnD.dbo.Comments ON Club_RnD.dbo.Posts.comments = Club_RnD.dbo.Comments.id
			//would a binary try be better for table.collumn name?
		//$this->query->select('*')->from('Posts')->join('Club_RnD.dbo.Comments', array('Club_RnD.dbo.Posts.comments' => 'Club_RnD.dbo.Comments.id'));
	
	->select()
		$this->select('*') //SELECT *
		$this->select('colName', 'otherCol') //SELECT colName, otherCol
		$this->select(array('colName', 'otherCol')) //SELECT colName, otherCol
	
	->delete()
		$this->delete() //DELETE FROM tableName
	
	->set()
		$this->set(array('key' => 'value') //SET key = 'value'
	*/
}