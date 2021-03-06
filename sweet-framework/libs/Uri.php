<?php
class Uri extends App {
	var $uriArray;
	var $queryString;
	var $match;
	var $defaultPart;
	var $controller;
	
	//new:
	var $domain;
	var $request;

	function __construct() {
		/*
		@todo:
			# Make this library work with the new config set up.
			- Simplify out the functions
				- Add in a callRoute() function that essenitally calls up the correct controller
				- Maybe rewrite the loadUrl function to make it more modular
			- Make it clearer to as what is happening in this code
		*/
		//[0] => helldsdfs34&what=4
		$this->request = f_first($_SERVER['argv']);
		//[HTTP_HOST] => localhost
		$this->domain = $_SERVER['HTTP_HOST'];
		//http or https?
		$this->protocol = strtolower(strstr($_SERVER['SERVER_PROTOCOL'], '/', true));;
		D::log($_SERVER, 'servar ');
		//$_SERVER['REQUEST_URI']
		//
		
		D::log($this->request, 'request');
		define('URL', $this->protocol . '://' . $this->domain . strstr($_SERVER['REQUEST_URI'] .'?', '?', true) );
		/*
if(!empty($this->request)) {
			
		} else {
			define('URL', $this->protocol . '://' . $this->domain . $_SERVER['REQUEST_URI']);	
		}
*/
		
		D::log(URL, 'URL');
		
		
		if($this->lib('Config')->get('site', 'prettyUrls')) {
			define('SITE_URL', URL);
		} else {
			define('SITE_URL', URL . '?');
		}
	}
	
	function callRoute($request=null) {
		if(!isset($request)) {
			$request = $this->getRequest();
		}
		//D::log($this->loadController(), 'controller funcj');
		f_call($this->loadController());
	}
	
	
	var $contorllerFile = 'Main.php';
	var $count = 0;
	var $contorller;
	
	function loadController($controller=null) {
		if(isset($controller)) {
			$this->contorllerFile = $controller;
		}
		$class = SweetFramework::className($this->contorllerFile);
		
		if(!SweetFramework::loadFileType('controller', $class)) {
			D::error('No Controller Found');
		}

		$page = $this->loadUrl($class::$urlPattern, $this->count);
		
		if(is_array(f_last($page))) {
			if(is_array( f_first(f_last($page)) )) {
				return $this->loadController(f_first(f_first(f_last($page))), $this->count+=1);
			}
			$page[$this->count] = f_first(f_last($page));
			//D::log($page[$part], 'page o parts');
		}
		D::log($page, 'Initing Controller…');
		
		$this->controller = new $class();
		
		//$this->controller->getLibrary('Databases/Query.php');
		
		/*@todo make "shortcuts" more dynamic */
		//$this->controller->template =& $this->controller->lib->Template;
		
		if(empty($page[$this->count])) {
			return f_callable(array($this->controller, 'index'));
		} else {
			if(method_exists($class, $page[$this->count])) {
				return f_callable(array(
					$this->controller,
					$page[$this->count]
				));
			}
		}
		if(method_exists($class, '__DudeWheresMyCar')) {
			return f_callable(array(
				$this->controller,
				'__DudeWheresMyCar'
			));
		}
		return function() {
			header("HTTP/1.0 404 Not Found");
			echo '<h1>404 error</h1>'; //todo check for some sort of custom 404…
			return false;
		};
	}
	
	
	
	function getRequest() {
		return $this->request;
	}
	
	function loadUrl($regexs=array(), $controllerPart=0) {
		$this->uriArray = null;
		if(!empty($regexs)) {
			$this->uriArray = $this->regexArray($regexs);
			$pop = true;
			D::log('Captin we found something!');
		}
		if(empty($this->uriArray)) {
			$this->uriArray = $this->regularUrl();
			
		}
		
		D::log($this->uriArray, 'uri array');
		return $this->uriArray;
	}
	
	
	function regexArray($regexs) {
		$matches = array();
		D::log($_SERVER['QUERY_STRING']);
		foreach($regexs as $regex => $func) {
			preg_match_all($regex, $_SERVER['QUERY_STRING'], $matches);
			if(f_first($matches)) {
				return f_push(
					array($func),
					f_map(
						'f_first',
						f_rest($matches)
					)
				);
			}
		}
		return false;
	}
	
	function regularUrl() {
		if($this->libs->Config->get('site', 'prettyUrls')) {
			return $this->getNiceUrl();
		} else {
			return $this->getUglyUrl();
		}
	}

	function getNiceUrl() {
		return explode(
			'/',
			str_replace(
				'index.php&',
				'',
				$_SERVER['QUERY_STRING']
			)
		);
	}
	
	function getUglyUrl() {
		$queryString = $_SERVER['QUERY_STRING'];
		if(@substr_count($queryString, '/', 0, 1) == 1) {
			$queryString = substr($queryString, 1, strlen($queryString) - 1);
		}
		return explode('/', $queryString);
	}
	
	function niceornot() {
		if($this->config->get('SweetFramework', 'niceUrls')) {
			$this->niceUrl();
		} else {
			$this->uglyUrl();
		}
		if(isset($this->uriArray[$this->defaultPart])) {
			$this->controller = str_replace('-', '_', $this->uriArray[$this->defaultPart]);
		} else {
			$this->controller = null;
		}
	}
	
	function niceUrl() {
		$this->queryString = str_replace('index.php&', '', $_SERVER['QUERY_STRING']);
		$this->uriArray = explode('/', $this->queryString);
	}
	
	function uglyUrl() {
		$this->queryString = $_SERVER['QUERY_STRING'];
		if(@substr_count($this->queryString, '/', 0, 1) == 1) {
			$this->queryString = substr($this->queryString, 1, strlen($this->queryString) - 1);
		}
		$this->uriArray = explode('/', $this->queryString);
	}
	
	function getPart($index) {
		if(!isset($this->uriArray[$index])) {
			//@todo make the E::warn() work to warn people when they could be coding something bad.
			return null;
		}
		return $this->uriArray[$index];
	}
	
	function get($index) {
		return f_first((array)$this->getPart($index));
	}
	
	function getArray() {
		return $this->uriArray;
	}
}
?>
