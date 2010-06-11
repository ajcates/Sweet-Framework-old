<?php

class JsonData {
	
	const JSON_DATA_FOLDER = 'data/';
	
	private $data;
	private $file;
	
	private $_fihttp://www.USAHolsters.com/Duty-Tactical-HolstersndArgs = array();
	//private $_getArgs;
	
	public function save() {
		return file_put_contents(self::JSON_DATA_FOLDER . $this->file, json_encode($this->data));
	}
	
	public function load() {
		$this->data = json_decode(file_get_contents(self::JSON_DATA_FOLDER . $this->file));
	}

	/*
	
	$this->get(array(
		'name' => 'twitter'
	))
	
	*/
	
	public function find() {
		$this->_findArgs += func_get_args();
		return $this;
	}
	
	public function get() {
		return $this->getData($this->_findArgs + func_get_args());
	}
	
	public function remove() {
		$this->removeData($this->_findArgs + func_get_args());
		return $this;
	}
	
	public function update($values=array()) {
		/*
		$this->updateData($this->_findArgs);
		return $this;
		*/
	}
	public function insert($data) {
		$data->id = count($this->data) + 1;
		$this->data[] = $data;
	}
	
	private function removeData() {
		$firstArg = array_slice(func_get_args(), 0, 1);
		$returnA = array();
		if(is_int($firstArg)) {
			foreach($this->data as $k => $obj) {
				if($obj->id == $firstArg) {
					unset($this->data[$k]);
				}
			}
		} else if(is_string($firstArg)) {
			$secondArg = array_slice(func_get_args(), 1, 1);
			if(is_scalar($secondArg)) {
				foreach($this->data as $k => $obj) {
					if($obj->$firstArg == $secondArg) {
						unset($this->data[$k]);
					}
				}
				return $returnA;
			} else if(is_array($secondArg)) {
				foreach($this->data as $k => $obj) {
					if(in_array($obj->$firstArg, $secondArg)) {
						unset($this->data[$k]);
					}
				}
				return $returnA;
			}
		} else if(is_array($firstArg)) {
			foreach($firstArg as $key => $arg) {
				if(is_string($key)) {
					$this->removeData($key, $arg);
				} else {
					$this->removeData($arg);
				}
			}
		}
		$this->removeData(array_slice(func_get_args(), 1));
	}
	
	private function getData() {
		$firstArg = array_shift(func_get_args());
		$returnA = array();
		if(is_int($firstArg)) {
			foreach($this->data as $obj) {
				if($obj->id == $firstArg) {
					$returnA[] = $obj;
				}
			}
		} else if(is_string($firstArg)) {
			$secondArg = array_slice(func_get_args(), 1, 1);
			if(is_scalar($secondArg)) {
				foreach($this->data as $obj) {
					if($obj->$firstArg == $secondArg) {
						$returnA[] = $obj;
					}
				}
				return $returnA;
			} else if(is_array($secondArg)) {
				foreach($this->data as $obj) {
					if(in_array($obj->$firstArg, $secondArg)) {
						$returnA[] = $obj;
					}
				}
				return $returnA;
			}	
		} else if(is_array($firstArg)) {	
			foreach($firstArg as $key => $arg) {
				if(is_string($key)) {
					$returnA = array_merge(
						$returnA,
						(array)$this->getData($key, $arg)
					);
				} else {
					$returnA = array_merge(
						$returnA,
						(array)$this->getData($arg)
					);
				}
			}
			
		}
		if(count(func_get_args()) > 1) {
			
			$returnA += (array)$this->getData(array_slice(func_get_args(), 1));
		}
		
		return $returnA;
	}
}



class Test extends JsonData {
	private $file = 'test.json';
	
	function __construct() {
		$this->load();
	}

}

$myTest = new Test();
$myTest->insert((object) array(
	'name' => 'A.J. Cates',
	'email' => 'aj@ajcates.com'
));
$myTest->insert((object) array(
	'name' => 'Tom Morris',
	'email' => 'tom@eggheadventures.com'
));
$myTest->insert((object) array(
	'name' => 'Brian Pond',
	'email' => 'brian@eggheadventures.com'
));

print_r($myTest->find(array(1, 2))->get());
?>