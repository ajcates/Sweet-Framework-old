<?

class SweetModel extends App {


	$model;
	$items;
	
	
	function __construct() {
		$this->lib('Query');
	}
	
	var $_buildOptions = array();
	
	function find() {
		$this->_buildOptions['find'] = func_get_args();
	}
	
	var $_filter;
	
	function filter() {
		$this->_buildOptions['filter'] = func_get_args();
	}
	
	function limit() {
		$this->_buildOptions['limit'] = func_get_args();
	}
	
	function sort() {
		$this->_buildOptions['sort'] = func_get_args();
	}
	
	function pull() {
		$this->_buildOptions['pull'] = f_flatten(func_get_args());
	}
	
	function offset() {
		$this->_buildOptions['limit'] = array_reverse(func_get_args());
	}
	
	function update() {
		$this->_buildOptions['update'] = func_get_args();
	}
	
	function _build() {
		$select = array_keys($this->fields);
		$join = array();
		foreach($this->_buildOptions['pull'] as $pull) {
			$fKey = f_first(array_keys($this->realtionShips[$pull]));
			
			if(is_string($fKey)) {
				$thisModelsField = $fKey;
				$join[$this->tableName . '.' . $thisModelsField] => f_first(f_first($this->realtionShips[$pull]));
			} else {
				$thisModelsField = $pull;
				$join[$this->tableName . '.' . $thisModelsField] => f_first($this->realtionShips[$pull]);
			}
			
			$model = $this->model();
			foreach($model->fields as $field) {
				$select[] = $pull '.' $field;
			}
			
			//$select[]	
		}
		
		$this->relationships
		
		
	
		$this->libs->Query->select()->from($this->tableName)->where()->go()->getDriver()
	}
	
	function save() {
		
	}
	
	function create() {
		//execute some sql
	}
	
	function delete() {
	
	}
	
	function all() {
	
	}
	
	function one() {
	
	}
	
	
}




/*



Maybe build out a cached structure of the joins attached to the model for the sweetrows?


find is basicaly where but trys to detect an extra couple of types

try and have a static array of functions to check datatypes

the build joins needs to be felixble to allow for differnt model types being joined together.




Pages Table SQL:
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `content` text,
  `dateCreated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

Holstr Joins:
'va_items_categories' => array(
	'va_items.item_id' => 'va_items_categories.item_id'
), 
'va_categories' => array(
	'va_items_categories.category_id' => 'va_categories.category_id',
	'va_categories.category_name' => array_map('Query::nullEscape', $filter['brand'])
),


build out the query and then return an array of sweet rows

sweet rows are chainable and can dynamicly create new sweetrows for relation ships

when you call a propertiy on a sweet row it checks to see if its model has that relation ship, if so it returns back a new sweet row
	

PagesModel.pull(array('user', 'group'))
	//should join the pages table to the user table which is joined with the group.
	- The pages model has a relationship for user defined in it. whatever model that points to will be used when finding the realtion ship for group




in theroy models only need to store 1 -> 1 relation ship info becuase more advance data would be kept in other models.
	?although you can describe more complex relationships to use in the model

are realtion ships just premade joins?
	member how i had it you only could define the column name the join func? could i somewhat recreate this with my ORM?

HolsterModel.relationShips = array(
	'guns' => array('id' => array('GunHolsters', 'holster', '')),
	'user' => array('users', 'id')
)

pull('guns')

pull(

=========


->create($keyValue) create a new object from key/value pairs
	//great for realtion ships becuase when they try and read the ID from the object it automaticly gets inserted and returned

->all() is a specical method that returns all of an items objects as a key/value array
	//maybe I need to come up with a new name for this…
	
->find() if you pass it a:
	number: you get that a key/value pair of that item as based on it's id
	array: basicy key/value pairs of the and statment
		if the value is an Array forms an IN statment
		if the value is a model it uses it's primary key(s)
	an array of numbers: Those id's.
	mutiple args, basicly an OR statment
	

->pull($cols) used to pull other types of objects instead of being lazy and doing it later.

->filter() Key value pairs of things you don't want

->limit(max) the amount of items you want to limit it too

->offset(amount, limit) the amount you would like to offset the items, by default limit is infinty unless used in combo with the limit function

->update($keyVal) Key value pairs of the things you would like to set. If you don't call the get when useing this method it sets it executes for all rows.

->save() saves the current objects to the db.

->fix($array) An array of name of the items you would like to fix

->delete() Deletes everything from the current object if no get() has been ran it deletes everything

->sort($keyVal) How you would like to sort these objects from the db if you pass if just a string it will sort by that string DESC
















*/