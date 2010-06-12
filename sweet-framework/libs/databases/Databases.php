<?
/*
new idea you have Databases object which holds differnt database objects, this allows for multiple connections to manged very easily.
	the config file is now called databases uses the config['database']['param'] syntxt
@todo
	- Fork this bitch and try and make it a singleton object and see if that speeds shit up.

*/
//Note that this class doesn't seem to do all that much, it does.
class Databases {
	
	protected static $databases;
	protected static $currentDatabase;
	
	function __construct() {
		Config::loadSettings('Databases.php');
	}
	
	static function f($funcName, $args=array()) {
		D::log(self::getCurrentDb(), 'current db');
		return self::callFuncOnDb(self::getCurrentDb(), $funcName, $args);
	}
	
	static function newDatabase($name) {
		if(self::$databases === NULL) {
			self::$databases = array();
		}
		$database = Config::getSetting('Databases', $name);
		
		
		//Sweetframework::getClass('lib', 'databases/drivers/' . $database['driver'],  );
		
		//App::includeLibrary('Databases/Drivers/' . $database['driver'] . '.php');
		self::$databases[$name] = new $database['driver']($database);
	}
	
	static function callFuncOnDb($dbname, $funcName, $args=array()) {
		//print_r(self::$databases[$dbname]);
		return call_user_func_array(array(self::$databases[$dbname], $funcName), $args);
	}
	
	static function setCurrentDb($dbname) {
		self::$currentDatabase = $dbname;
	}
	
	static function getCurrentDb() {
		return self::$currentDatabase;
	}
	
	static function getDbVar($dbname, $varName) {
		return self::$databases[$name]->$varName;
	}
}