<?
require_once('App.php');
/*
@todo
	- Make the fileLoading functions support multiple folder levels
	- Get the framework to load a controller
	- Make sure the app/framework split is working correctly
	- 
*/
class SweetFramework extends App {

	var $site;
	var $viewPrefix;
	var $appShortcut;

	static $urlPattern;

	function __construct($configFile) {
		//crap we need for the framework
		$GLOBALS['app'] = $this; //stop this.
		$this->helper('functional'); //makes my life oh so much easier :)
		$this->lib(array('D', 'Config'));
		D::initialize($this->libs->Config->get('Debug'));
		D::time('App', 'SweetFramework - ' . date("F j, Y, g:i a"));
		
		$appInfo = $this->libs->Config->get('SweetFramework', 'app');
		
		foreach($appInfo['paths'] as $k => $v) {
			if(!is_array(self::$paths[$k])) {
				self::$paths[$k] = array();
			}
			//@todo A/B test these two.
			self::$paths[$k][] = '/' . $appInfo['folder'] . '/' . $v .'/';
			//self::$paths[$k][] = join('/', array(LOC, $appInfo['folder'], $v)) .'/';
		}

		/*
		$db = $this->config->get('Site', 'database');
		Databases::newDatabase($db);
		Databases::setCurrentDb($db);
		*/
		
		//Handy stuff that is always used
//		$this->includeLibrary('Session.php');
//		$this->includeLibrary('SweetModel.php');
		$this->lib(array('Uri', 'Theme'));
		

		$this->libs->Uri->callRoute();
		
		//Check for theme and go!
		/*
		if(!$this->lib->Theme->setTheme($this->config->get('Site', 'defaultTheme'))) {
			D::error('Theme could not be found. Debug: $Config->getSetting(\'Site\', \'defaultTheme\') = ' . $this->config->get('Site', 'defaultTheme'));
		}
		*/
		
		
		//$this->loadController('Main.php'); //this is where it starts to get interesting…
		self::end();
		//after we are done.
		//Session::writeData();
		
	}
	/////
	
	static protected $paths = array(
		'lib' => array('/sweet-framework/libs/'),
		'model' => array(),
		'helper' => array('/sweet-framework/helpers/'),
		'controller' => array(),
		'config' => array('/sweet-framework/settings/')
	);
	
	static $classes = array();
	
	public static function className($file) {
		if(substr($file, -4) == '.php') {
			return substr(strrchr('/' . $file, '/'), 1, -4);
		}
		return substr(strrchr('/' . $file, '/'), 1);
	}
	
	public static function loadFile($path, $fileName) {
		if(file_exists(LOC . $path . $fileName)) {
			require_once(LOC . $path . $fileName);
			return true;
		}
		return false;
	}
	
	public static function loadFileType($type, $name) {
		/* @todo need to loop for the correct path here */
		foreach(self::$paths[$type] as $path) {
			if(self::loadFile($path, $name. '.php')) {
				return true;
			}
		}
		D::warn('Could not load file with type ' . $type . ' and name ' . $name);
		return false;
	}
	
	public static function loadClass($type, $name) {
		$cName = self::className($name);
		if(!array_key_exists($type . $cName, self::$classes)) {
			self::loadFileType($type, $name);
			self::$classes[$type . $cName] = new $name;
		}
		$return =& self::$classes[$type . $cName];
		return $return;
	}
	
	
	///////////////////
		
	static protected $sweetLibs = array();
	
	static function end() {
		if(isset(self::$sweetLibs['Session'] )) {
			//@todo make this more module and not so HARDcoded. :)
			self::$sweetLibs['Session']->save();
		}
		D::time('App', 'End');
		D::close();
		exit;
	}
		
	function loadController($fileName, $part=0) {
		D::log($fileName, 'Loading Controller…');
		
		//$fileName = Events::callEvent('loadController', $fileName);

		require(LOC . 'Controllers/' . $fileName);
		
		//print_r($this);
		static $partCount = 0;

		$class = substr(strrchr('/' . $fileName, '/'), 1, -4);

		$page = $this->lib->Uri->loadUrl($class::$urlPattern, $part);
		
		if(is_array(f_last($page))) {
			if(is_array( f_first(f_last($page)) )) {
				$this->loadController(f_first(f_first(f_last($page))), $part+1);
				return true;
			}
			$page[$part] = f_first(f_last($page));
			//D::log($page[$part], 'page o parts');
		}


		D::log($page, 'Loading Controller…');
		$this->controller = new $class();
		
		$this->controller->getLibrary('Databases/Query.php');
		
		/*@todo make "shortcuts" more dynamic */
		$this->controller->template =& $this->controller->lib->Template;
		
		if(empty($page[$part])) {
			echo $this->controller->index();
		} else {
			if(method_exists($class, $page[$part])) {
				echo f_call(array(
					$this->controller,
					$page[$part]
				));
				return true;
			} else {
				return f_function(function() {
					header("HTTP/1.0 404 Not Found");
					echo '<h1>404 error</h1>'; //todo check for some sort of custom 404…
				});
			}
		}
		D::log($page, 'controller method array');
	}
	
	
	
	
	
	
/*
Notes:
	File load types:
		- Libs:
			- App classes
			- Regular Codeigniter Library
			- Basic includes
		- Blocks:
		- Settings:
		- Models:
		- Themes:
	
	folders have to work.
	.php is optional
	
	
	there are differnt file "types" kept in a list.
	
	SweetFramework is an App factory.
	
	- get "ClassName" function
	
	"ClassNames" are valid "FileNames".
	
	FileNames are consider busted until caled but the LoadApp file function?
	
	
	
	- LoadFileType file function 		<- "types" abstraction switch happens here
		: load app takes a "FileName" 					- Which uses a isFileReal function?
		
	- LoadFileType in theroy could use a CodeIgniter style loading function for some things
		- Which then in theroy could use a basic include function
			- Which uses a isFileReal function?








 	 */
	
	
	
	
	
	
	
	
	
}