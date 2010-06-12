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
		//$this->lib('test/Test');
		$this->lib(array('Template', 'Uri'));
	}

	function index() {
	
		//Write to the log.
		//D::show();
		
		$this->model('Projects');
		
		//D::show('hello world');
		//Show somethign to the screen.
		//D::show(, 'Some Projects');
		
		
		
		$this->libs->Template->set(array(
			'projects' => $this->models->Projects->all()
		));

		
		$this->libs->Template->render('dashboard.php');
		//
	}
	
	function project() {
		
		$this->libs->Uri->getPart(2);
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