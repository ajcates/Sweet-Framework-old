<?
class Users extends SweetModel {

	var $tableName = 'Users';
	var $pk = null;
	var $fields = array(
		'page' => array('int', 11),
		'tag' => array('int', 11),
		'user' => array('int', 11)
	);
	var $relationships = array(
		'page' => array('Pages', 'id'),
		'tag' => array('Tags', 'id'),
		'user' => array('Users', 'id')
	);
	
	
	
	

	function __construct() {
		$this->lib(array('databases/Query'));
	}
	
	function get($id) {
		//description
		if(is_numeric($id)) {
			$this->libs->Query->where(array('id' => $id));
		} else if(is_string($id)) {
			$this->libs->Query->where(array('username' => $id));
		} else if(is_array($id)) {
			$this->libs->Query->where($id);
			//$this->libs->Query->select('*')->from('projects')->limit($offset, $count)->results();
		}
		return $this;
	}
	
	function limit($count=10, $offset=0) {
		$this->libs->Query->limit($count, $offset);
		return $this;
	}
	
	function all() {
		return $this->libs->Query->select('*')->from($this->tableName)->results();
	}
	
	function one() {
		return f_first($this->libs->Query->select('*')->limit(0, 1)->from($this->tableName)->results());
	}
}