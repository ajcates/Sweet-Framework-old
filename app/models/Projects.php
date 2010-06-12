<?
class Projects extends App {

	var $tableName = 'projects';

	function __construct() {
		$this->lib(array('databases/Query'));
	}
	
	function get($id) {
		//description
		if(is_numeric($count)) {
			$this->libs->Query->where(array('id' => $id));
		} else if(is_string($count)) {
			$this->libs->Query->where(array('name' => $id));
		} else if(is_array($id)) {
			$this->libs->Query->where($id);
			//$this->libs->Query->select('*')->from('projects')->limit($offset, $count)->results();
		}
		return $this;
	}
	
	function limit($offset=0, $count=10) {
		$this->libs->Query->limit($offset, $count);
		return $this;
	}
	
	function all() {
		return $this->libs->Query->select('*')->from($this->tableName)->results();
	}
	
	function one() {
		return $this->libs->Query->select('*')->limit(0, 1)->from($this->tableName)->results();
	}
}