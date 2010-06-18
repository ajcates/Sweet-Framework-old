<?
class Pages extends App {

	var $tableName = 'pages';
	var $pk = 'id';
	var $fields = array(
		'id' => array('int', 11),
		'user' => array('int', 11),
		'slug' => array('varchar', 11),
		'title' => array('varchar', 256),
		'description' => array('varchar', 256),
		'content' => array('text'),
		'dateCreated' => array('int', 11)
	);
	var $relationships = array(
		'user' => array('users', 'id')
	);
	
	function __construct() {
		$this->lib(array('databases/Query', 'Session'));
	}
	
	function get($id) {
		//description
		if(is_numeric($id)) {
			$this->libs->Query->where(array('id' => $id));
		} else if(is_string($id)) {
			$this->libs->Query->where(array('slug' => $id));
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
		return f_first($this->libs->Query->select('*')->limit(1, 0)->from($this->tableName)->results());
	}
	
	function add($page) {
		if(empty($page['title']) || empty($page['slug'])) {
			return false;
		}
		return $this->libs->Query->insert(array_merge(
			$page,
			array('user' => $this->model('User')->getCurrentUser()->id, 'dateCreated' => time())
		))->into($this->tableName)->results('bool');
	}
}