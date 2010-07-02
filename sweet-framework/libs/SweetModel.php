<?
class SweetModel extends App {


	var $model;
	var $items;
	
	
	function __construct() {
		$this->lib('Query');
	}
	
	var $_buildOptions = array();
	
	function find() {
		$this->_buildOptions['find'] = D::log(func_get_args(), 'find args');
		
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
		
		
		return $this->libs->Query->select($select)->join($join)->from($this->tableName, @$this->_buildOptions['limit'])->where($this->_buildFind(@$this->_buildOptions['find']))->results();
	}
	
	function _buildFind($find=array()) {
		foreach($find as $k => $arg) {
			if(is_int($k) && is_array($arg)) {
				$find = array_merge($find, $this->_buildFind($arg));
			} else if(is_string($k) && array_key_exists($k, $this->fields)) {
				unset($find[$k]);
				$find[$this->tableName . '.' . $k] = $arg;
			} else if(is_numeric($arg)) {
				$find[$this->tableName . '.' . $this->pk][] = $arg;
			}
		}
		return $find;
	}
	
	function _buildPulls($pulls, $on=null, $with=array()) {
		$builtPulls = array();
		foreach($pulls as $k => $pull) {
			if(is_string($k)) {
				//sub join?
				
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
				$builtPulls[] = $model->_buildPull($k, $pullRel, $on, $flName, $rfName);
				
				$builtPulls = array_merge($builtPulls, $model->_buildPulls((array)$pull, $k, f_push($k, (array)$with) ));
				
			} else {
				if(is_array($pull)) {
					$builtPulls = array_merge($builtPulls, $this->_buildPulls($pull, $on, $with));
					continue;
				}
				//regular join
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
				$builtPulls[] = $model->_buildPull(join('_', f_push($pull, $with)), $pullRel, $on, $flName, $rfName);
			}
		}
		
		return $builtPulls;
	}
	
	
	function _buildPull($pull, $pullRel, $tableName, $lfName=null, $rfName=null) {
		$select = $join = array();
		//JOIN CODE:
		
		if(is_array($tableName)) {
			$tableName = join('_', ($tableName));
		}
		
		$join[$this->tableName . ' AS ' . $pull] = array(
			$tableName . '.' . $lfName => $pull . '.' . $rfName
		);
		
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
		//foreach( as )
		$items = $this->_build();
		if(!empty($items)) {
			$returnItems = array();
			$i = 0;
			$last = null;
			foreach($items as $item) {
				
				if($item->{$this->pk} == $last) {
					
					//$returnItems[$i];
					//foreach($pulls as $pull)
					f_call(array($returnItems[$i], 'pass'), array($item));
				} else {
					$i++;
					$returnItems[$i] = new SweetRow($this, $item);
					$last = $item->{$this->pk};
				}
			}
		}
	
		return $items;
	}
	
	function one() {
	
	}
	
	
}

class SweetRow extends App {

		/*
			what if I came up with the concept of sweet data?
			sweetData vs sweetRow
			basicly a data structure for indivual rows that is capable of retriving more rows
			//what abilities would the sweetRow have?
				magic reading methods…
					would first return back a sub row
					that would call the get_field methods correctly
					
				the ability to insert more data on the fly
				
				seprates out normal row data and sub row data;
					
				
				ability to save data back into the db
					do this keep edited data sperate main data
				
				
			*/
	private $_data = array();
	private $_errors = array();
	private $_model;
	
	function __construct($model, $item) {
		$this->_data[] = $item;
		$this->_model = $model;
	}
	
	function pass($item) {
		$this->_data[] = $item; 
	}
	
	function __set($var, $value) {
		/*
		$model = $this->_model;
		if (is_callable(array($this->getLibrary(f_first($model::$objects[$var])), 'set_' . $model::$objects[$var][1]))) {
			$value =  $this->getLibrary(f_first($model::$objects[$var]))->{'set_' . $model::$objects[$var][1]}( $var, f_last($model::$objects[$var]) );
		}
		if(count((array)$value) > 1) {
			D::log($value, "Caught error");
			$this->_errors[$var] = $value;
		} else {
			$this->_data[$var] = f_first((array)$value);
			$this->$var = $this->_data[$var];
		}
		*/
	}
	
	function __get($var) {
		if(array_key_exists($var, $this->realtionShips)) {
			//
		}
		/*
		$model = $this->_model;
		if(method_exists($this->getLibrary(f_first($model::$objects[$var])), 'get_' . $model::$objects[$var][1])) {
    		return $this->getLibrary(f_first($model::$objects[$var]))->{'get_' . $model::$objects[$var][1]}($this->_data[$var], f_last($model::$objects[$var]) );
    	}
    	
		return $this->_data[$var];
		*/
	}
	
	function __call($var, $args=array()) {
		/*
		$model = $this->_model;
		if(array_key_exists($var, $model::$belongsTo)) {
			//->find(array($model::$belongsTo[$var] => $this->_data[$model::$PK] ))->all()
			//@todo add in a limit when im not working with fucktarded mssql
			return $this->getModel($var)->find(array($model::$belongsTo[$var] => $this->_data[$model::$PK] ))->all();
		} else if(array_key_exists($var, $model::$objects) && method_exists($this->getLibrary(f_first($model::$objects[$var])), 'get_' . $model::$objects[$var][1])) {
			return $this->getLibrary(f_first($model::$objects[$var]))->{'get_' . $model::$objects[$var][1]}( $var, f_last($model::$objects[$var]) );
		} else {
			D::warn('wtf are you trying todo?');
		}
		*/
	}
	
	function set($var, $value) {
		/*
		$this->_data[$var] = $value;
		return $this;
		*/
	}
	
	function get($var, $fetch=false) {
		/*
		if(is_array($var) && 1 < count($var)) {
			return $this->{f_first($var)}->get(f_rest($var), $fetch);
		}
		if($fetch) {
			return $this->_data[f_first((array)$var)];
		} else {
			return $this->{f_first((array)$var)};
		}
		*/
	}
	
	function save() {
		/* @todo figure out if this needs to return its self. */
		/*
		if(!empty($this->_errors)) {
			return false;
		}
		$model = $this->_model;
		return $this->getLibrary('Query')->update($model::$tableName)->where(array($model::$PK => $this->_data[$model::$PK]))->set($this->_data)->go();
		*/
	}
	
	public function delete() {
		return $this->_model->find($this->_model->pk)->delete();
	}
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