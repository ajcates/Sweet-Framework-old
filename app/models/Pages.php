<?
class Pages extends App {

	var $tableName = 'Pages';
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
		//fk?
		'user' => array('User', 'id'),
		//m2m
		'tags' => array('id' => array('PageTags', 'page', array(
			'tag' => array('Tags', 'id'),
			'user' => array('User', 'id')
		)))
	);
	//
	
	/*
	'guns' => array('id' => array('GunHolsters', 'holster', 'gun' => array('Guns', 'id')),
	'categories' => array('id' => array('PageCategories', 'page', 'category' => array('Categories' => 'id'))
	
	 	 */
	
	
	/*
	So most the time pulls will have just one name becuase they are forigen keys.
		-But when you have a m2m relationship it may make sense to define the relationship all on one line.
			// When you do this you are somewhat hiding the the table inbetween the m2m.
	
	pull('guns', array('categories', 'type'), 'user')
	//guns = predefined m2m join
	//categories, type = predefined m2m join combod up with FK join
	//user = Just a plain ol FK join
	
	
	
	
	Pages->pull('user', 'guns', array('categories', 'category', 'type')
		PageCategories->pull(array('category', 'type'))
			Categories->pull(array('type')
	
	'va_items_categories' => array(
		'va_items.item_id' => 'va_items_categories.item_id'
	), 
	'va_categories' => array(
		'va_items_categories.category_id' => 'va_categories.category_id',
		'va_categories.category_name' => array_map('Query::nullEscape', $filter['brand'])
	),
	 */
	
	
	//model, field, 
	
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