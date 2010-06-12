<?
class M {
	//short cuts for model access
	public static function __callStatic($name, $arguments) {
		return SweetFramework::getClass('model', $name);
	}
}
class T {
	//Just a simple global struct to keep T varibles
	static $url;
	static $loc;
	public static function get($name, $arguments=array()) {
		return SweetFramework::getClass('lib', 'Template')->get($name);
	}
	public static function __callStatic($name, $arguments=array()) {
		return SweetFramework::getClass('lib', 'Template')->$name;
	}
}
class V {
	//view?
	static function get($reallyHopeNoOneNamesThereVaribleThis, $values=array()) {
		extract($values);
		ob_start();
		include(T::$loc . '/views/' . $reallyHopeNoOneNamesThereVaribleThis . '.php' );
		return ob_get_clean();
	}
	public static function __callStatic($varName, $values=array()) {
		return SweetFramework::getClass('lib', 'Template')->$varName;
		//f_call(array(, 'get'), $args)
	}
}
class B {
	//get ya blocks!
	public static function __callStatic($reallyHopeNoOneNamesThereVaribleThis, $values=array()) {
		extract((array) f_first($values));
		ob_start();
		include(LOC . '/sweet-framework/blocks/' . $reallyHopeNoOneNamesThereVaribleThis . '.php' );
		/*
		f_first

		if(is_array($values[0])) {
			$attributes 
		}


		'<' . $tagName . '>'
		*/

		return ob_get_clean();
	}
}

class Theme extends App {
	
	var $themeUrl;
	
	function __construct() {
		//$this->lib('Config')->get('Theme');
		
		if(!$this->set($this->lib('Config')->get('site', 'theme'))) {
			D::error('Theme could not be found. Debug: $Config->getSetting(\'Site\', \'defaultTheme\') = ' . $this->config->get('Site', 'defaultTheme'));
		}
	}

	function set($name) {
		//@todo rename this to just set
		$newPlace = 'app/themes/' . $name;
		D::log(LOC . '/' . $newPlace, 'new Place');
		if(is_dir(LOC . '/' . $newPlace)) {
			if(substr(URL, -1) == '?') {
				T::$url = $this->themeUrl = substr(URL, 0, -1) . $newPlace;
			} else {
				T::$url = $this->themeUrl = URL . $newPlace;
			}
			T::$loc = LOC . '/' . $newPlace;
			//$this->libs->Config->set('site', 'theme', $newPlace);
			return true;
		} else {
			D::error('Theme doesn\'t exist');
		}
	}
	
	function loadSnippets($names) {
		foreach((array)$names as $name) {
			require_once(T::$loc . 'snippets/' . $name . '.php' );
		}
	}
}
