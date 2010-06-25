<?
class SweetModel extends App {


	var $model;
	var $items;
	
	
	function __construct() {
		$this->lib('Query');
	}
	
	var $_buildOptions = array();
	
	function find() {
		$this->_buildOptions['find'] = func_get_args();
		return $this;
	}
	
	var $_filter;
	
	function filter() {
		$this->_buildOptions['filter'] = func_get_args();
		return $this;
	}
	
	function limit() {
		$this->_buildOptions['limit'] = func_get_args();
		return $this;
	}
	
	function sort() {
		$this->_buildOptions['sort'] = func_get_args();
		return $this;
	}
	
	function pull() {
		D::log(func_get_args(), 'pull args');
		$this->_buildOptions['pull'] = func_get_args();
		return $this;
	}
	
	function offset($a) {
		$this->_buildOptions['limit'][1] = $a;
		return $this;
	}
	
	function update() {
		$this->_buildOptions['update'] = func_get_args();
		return $this;
	}
	
	function _build() {
		$select = array();
		foreach(array_keys($this->fields) as $field) {
			$select[] = $this->tableName . '.' . $field;
		}
		$join = array();
		foreach($this->_buildOptions['pull'] as $pull) {
			if(is_array($pull)) {
				D::log($pull, 'pull array');
				foreach($pull as $k => $v) {
					if(is_array($v)) {
						//multi values 'col' => array('col1', 'col2')
						/*pull array: Array(
						    [tags] => Array(tag, user )
						)*/
					} else {
						//single
						//array([tags] => tag)
						$pullRel = $this->relationships[$k];
						//$model = 								
						if(is_string($fKey = f_first(array_keys($pullRel)))) {
							$model = $this->model(f_first($pullRel[$fKey]));
						} else {
							$model = $this->model(f_first($pullRel));
						}
						$test = $model->_buildPull($pull, $pullRel, $this->tableName);
						D::log($test, 'test m2m');
						//$builtPull = 
					}
				}
				
	//			if( is_string($fKey = f_first(array_keys($pull))) ) {
				//	$builtPull = $this->_buildPull($fKey, $this->relationships[$fKey], $this->tableName);
	//			}
				
			} else {
				//$builtPull = $this->_buildPull($pull, $this->relationships[$pull], $this->tableName);
				
				$pullRel = $this->relationships[$pull];
				
				if(is_string($fKey = f_first(array_keys($pullRel)))) {
					$model = $this->model(f_first($pullRel[$fKey]));
				} else {
					$model = $this->model(f_first($pullRel));
				}
				
				$builtPull = $model->_buildPull($pull, $pullRel, $this->tableName);
				
				//$pull, $this->tableName, 
				/*
				$pullRel = $this->relationships[$pull];
				$fKey = f_first(array_keys($pullRel));
				
				if(is_string($fKey)) {
					//$thisModelsField = $fKey;
					
					
					//$join[$this->tableName . '.' . $thisModelsField] = f_first(f_first($pullRel ));
					$model = $this->model(f_first($pullRel[$fKey]));
					
					//$pullRel[$fKey][1];
					
					
					$join[$model->tableName . ' AS ' . $pull] = array(
						$this->tableName . '.' . $fKey => $pull . '.' . f_last($pullRel)
					);
					
				} else {
					//$thisModelsField = $pull;
					$model = $this->model(f_first($pullRel));
					
					$join[$model->tableName . ' AS ' . $pull] = array(
						$this->tableName . '.' . $pull => $pull . '.' . f_last($pullRel)
					);
				}
				D::log($join, 'join');
				
				//$model->fields, $join
				
				foreach(array_keys($model->fields) as $field) {
					$select[$pull . '.' . $field] = $pull . '.' . $field ;
				}
				*/
			}
			$join += (array)$builtPull['join'];
			$select += (array)$builtPull['select'];
			
			
			//$select[]	
		}
		
		//$this->relationships
		
		
	
//		return $this->libs->Query->select('*')->join($join)->from($this->tableName)->where()->go()->getDriver();
		return $this->libs->Query->select($select)->join($join)->from($this->tableName)->where()->results();
	}
	
	
	
	
	function _buildPull($pull, $pullRel, $tableName) {
		$select = $join = array();
/*
		if(is_string($fKey = f_first(array_keys($pullRel)))) {
		//	$model = $this->model(f_first($pullRel[$fKey]));
			$join[$tableName . ' AS ' . $pull] = array(
				$this->tableName . '.' . $fKey => $pull . '.' . f_last($pullRel)
			);
		} else {
		//	$model = $this->model(f_first($pullRel));
			
		}
*/
		$join[$this->tableName . ' AS ' . $pull] = array(
			$tableName . '.' . $pull => $pull . '.' . f_last($pullRel)
		);
		foreach(array_keys($this->fields) as $field) {
			$select[$pull . '.' . $field] = $pull . '.' . $field;
		}
		return array(
			'join' => $join,
			'select' => $select
		);
	}
	
	
	
	
	
	function save() {
		
	}
	
	function create() {
		//execute some sql
	}
	
	function delete() {
	
	}
	
	function all() {
		return $this->_build();
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