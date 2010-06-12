<?php

Class Main extends App {

	static $urlPattern = array();

	function __construct() {
		//$this->lib(array('Template', 'databases/Query'));
	}

	function index() {
	
		//Write to the log.
		D::log('Hello World');
		
		//Load a library.
		$this->lib('test/Test');
		
		
		//Show somethign to the screen.
		D::show($this->libs->Test->tests, 'Tests');
		
		//$this->template->render('default.php');
	}
}