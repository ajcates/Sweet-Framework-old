<?php

Class Main extends App {

	static $urlPattern = array();

	function __construct() {
		//$this->lib(array('Template', 'databases/Query'));
		
		
		
		
		
		/*
		Databases::f('connect', array(array(
			'driver' => 'My_SQL',
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'databaseName' => 'holsterapi'
		)));	
		 */
		 
		//Load a library.
		$this->lib('test/Test');
	}

	function index() {
	
		//Write to the log.
		D::log('Hello World');
		
		
		$this->model('Projects');
		
		
		//Show somethign to the screen.
		D::show($this->models->Projects->get(), 'Some Projects');
		
		//$this->template->render('default.php');
	}
	
	function addProject() {
		//$this->lib('databases/Query');
		
		/*
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(256) DEFAULT NULL,
		`dateCreated` int(11) DEFAULT NULL,
		`createdBy` int(11) DEFAULT NULL,
		`description` text,
		 */
		
		/*
		$this->libs->Query->insert(array(
			'name' => 'Other Project',
			'dateCreated' => time(),
			'createdBy' => 1,
			'description' => 'just another project'
		))->into('projects')->go();
		*/
		
	}
}