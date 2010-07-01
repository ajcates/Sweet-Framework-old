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
		$join = $select = array();
		foreach(array_keys($this->fields) as $field) {
			$select[] = $this->tableName . '.' . $field;
		}
		
		foreach($this->_buildPulls($this->_buildOptions['pull'], $this->tableName) as $build) {
			$join += (array)$build['join'];
			$select += (array)$build['select'];
		}
		
		// -- CUTTERS
		
		//$this->relationships
		
		
	
//		return $this->libs->Query->select('*')->join($join)->from($this->tableName)->where()->go()->getDriver();
		return $this->libs->Query->select($select)->join($join)->from($this->tableName)->where()->results();
	}
	
	function _buildPulls($pulls, $on=null, $with=array()) {
		$builtPulls = array();
		
	//	D::log($pulls, 'pulls');
		foreach($pulls as $k => $pull) {
			
		
			if(is_string($k)) {
				//sub join?
				//pretend $pull is an array
				
				
				D::log($k, '$k');
				
				$pullRel = $this->relationships[$k];
				
				if(is_string($fKey = f_first(array_keys($pullRel)) )) {
					$flName = $fKey;
					$model = $this->model(f_first($pullRel[$fKey]));
				} else {
					$flName = $pull;
					$model = $this->model(f_first($pullRel));
				}
				
				if(is_array($rfName = f_last($pullRel))) {
					$rfName = f_last(f_last($pullRel));
				}
				D::log($pull, 'subjoin $pulls');
				$builtPulls[] = D::log($model->_buildPull($k, $pullRel, $on, $flName, $rfName), 'subjoin' );
				
				
				
				
				$builtPulls = array_merge($builtPulls, D::log( $model->_buildPulls((array)$pull, $k, f_push($k, (array)$with) ), 'subjoins') );
				
			} else {
			
				if(is_array($pull)) {
					$builtPulls = array_merge($builtPulls, $this->_buildPulls($pull, $on, $with));
					continue;
				}
				//regular join
				
				
				
				
				///
				$pullRel = $this->relationships[$pull];
				
				if(is_string($fKey = f_first(array_keys($pullRel)) )) {
					$flName = $fKey;
					$model = $this->model(f_first($pullRel[$fKey]));
				} else {
					$flName = $pull;
					$model = $this->model(f_first($pullRel));
				}
				
				if(is_array($rfName = f_last($pullRel))) {
					$rfName = f_last(f_last($pullRel));
				}
				/*
				$model
				$pullRel
				$flName
				$rfName
				*/
				/////////
			//	D::log($pullRel, 'pullRel');
			//	D::log($with, '$with not_array single build');
			//	D::log($pull, '$pull not_array single build');
				
				
				
				
				$builtPulls[] = $model->_buildPull(join('_', f_push($pull, $with)), $pullRel, $on, $flName, $rfName);
			}
		
		
		
			/*
				Any time there is a key:
			- there is a sub join needed 
				- Sub joins need:
					- left field name
						- is the key in the parents relation ship structure
							/%
							*'user'* => array(
								'User',
								'id'
							),
							%/
					- right field name
						- is the last element in the parents relation ship structure
							/%
							'user' => array(
								'User',
								*'id'*
							),
							%/
					- table alias
					- right table name = table alias
					- left table name
						- Is the parents alias 
					
					
			- this sub join is based on the realtionShip structure of the key in the current model
			*/
		
			//
			/*
			if(!is_string($k)) {
				if(is_array($pull)) {
					D::log($pull, 'not_string is_array $pull');
					$builtPulls = array_merge($builtPulls,  D::log($this->_buildPulls($pull, $on, $with), 'not_string is_array builder') );
					continue;
				} else {
					//not string
					$pullRel = $this->relationships[$pull];
					
					
					if(is_string($fKey = f_first(array_keys($pullRel)) )) {
						$flName = $fKey;
						$model = $this->model(f_first($pullRel[$fKey]));
					} else {
						$flName = $pull;
						$model = $this->model(f_first($pullRel));
					}
					
					if(is_array($rfName = f_last($pullRel))) {
						$rfName = f_last(f_last($pullRel));
					}
					
				}
			} else {
				//is string
				
				$pullRel = $this->relationships[$k];
				
			}
			
			if(is_string($fKey = f_first(array_keys($pullRel)) )) {
				$flName = $fKey;
				$model = $this->model(f_first($pullRel[$fKey]));
			} else {
				$flName = $pull;
				$model = $this->model(f_first($pullRel));
			}
			
			if(is_array($rfName = f_last($pullRel))) {
				$rfName = f_last(f_last($pullRel));
			}
			
			if(is_array($pull)) {
				// $k is getting skipped!
				D::log($k, 'is_string is_array $k');
				//D::log(f_construct($k, (array) $on), 'f_construct $k');
				
				D::log($pull, 'is_string is_array $pull');
				//
				
				$builtPulls[] = $model->_buildPull($k, $pullRel, $k, $flName, $rfName);
				
				$builtPulls = array_merge($builtPulls, D::log($model->_buildPulls($pull, $on, f_construct($k, $with)), 'is_string is_array builder'));
			} else {
				
				D::log($pullRel, 'pullRel');
				D::log($with, '$with not_array single build');
				D::log($pull, '$pull not_array single build');
				$builtPulls[] = $model->_buildPull($pull, $pullRel, $on, $flName, $rfName);
			}
			*/
			
			
			
			/*
			foreach((array)$pull as $p) {
				$builtPulls[] = $model->_buildPull($p, $pullRel, $on, $flName, $rfName);
			}
			*/
			
			
			
			
			
			/*
			if(is_array($pull)) {
				//$builtPulls += (array) D::log($this->_buildPulls(array_keys($pull)), 'builder');
				D::log($pull, 'pull is_array');
				
				$builtPulls = array_merge($builtPulls,  D::log($this->_buildPulls(array_keys($pull), $pull, $on), 'builder') );
				
			} else {
				if(is_string($k)) {
					$pullRel = $this->relationships[$k];
				} else {
					$pullRel = $this->relationships[$pull];
				}
				
				
				if(is_string($fKey = f_first(array_keys($pullRel)) )) {
					$flName = $fKey;
					$model = $this->model(f_first($pullRel[$fKey]));
				} else {
					$flName = $pull;
					$model = $this->model(f_first($pullRel));
				}
				
				if(is_array($rfName = f_last($pullRel))) {
					$rfName = f_last(f_last($pullRel));
				}
				D::log($pullRel, 'pullRel');
				
				
				//D::log(, 'extra puls');
				$builtPulls[] = $model->_buildPull($pull, $pullRel, $on, $flName, $rfName);
				if(isset($mExtraPuls[$pull] )) {
					D::log($mExtraPuls[$pull], 'mExtraPlus');
					//i need to pass the $pull key im useing to the building…
					$builtPulls = array_merge($builtPulls, $model->_buildPulls((array) $mExtraPuls[$pull], array(), $pull ) );
				}
			}
			*/
		}
		//D::log($model->_buildPulls($mExtraPuls), 'extra pulls');
		
		
		return D::log($builtPulls, 'builtPulls');
	}
	
	
	function _buildPull($pull, $pullRel, $tableName, $lfName=null, $rfName=null) {
		$select = $join = array();
		/*
Current:
 LEFT JOIN Users AS user
	ON Pages.user = user.id 
 LEFT JOIN PageTags AS tags
	ON Pages.id = tags.page 
 LEFT JOIN Tags AS tag
	ON tags.tag = tag.id
	
Want:
 LEFT JOIN Users AS user
	ON Pages.user = user.id 
 LEFT JOIN PageTags AS tags
	ON Pages.id = tags.page
 LEFT JOIN Tags AS tags_tag
	ON tags.tag = tags_tag.id
 LEFT JOIN Users AS tags_user
	ON tags.user = tags_user.id	

*/
		
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
		//JOIN CODE:
		D::log($tableName, 'tName');
		
		D::log($pull, '_buildPull $pull');
		
		if(is_array($tableName)) {
			$tableName = join('_', ($tableName));
		}
		
		$join[$this->tableName . ' AS ' . $pull] = array(
			$tableName . '.' . $lfName => $pull . '.' . $rfName
		);
		D::log($join, 'built join');
		
		
		//SELECT CODE:
		foreach(array_keys($this->fields) as $field) {
			$select[$pull . '.' . $field] = str_replace('_', '.', $pull) . '.' . $field;
		}
	//	D::log($select, 'built select');
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