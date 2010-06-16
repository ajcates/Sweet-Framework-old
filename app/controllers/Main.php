<?php

Class Main extends App {

	static $urlPattern = array(
		'/^users.*/' => array('Users.php'),
		'/^admin.*/' => array('Admin.php')
	);

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
		$this->model(array('Projects', 'User'));
	}

	function index() {
	
		//Write to the log.
		//D::show();
		
		
		
		//D::show('hello world');
		//Show somethign to the screen.
		//D::show(, 'Some Projects');
		
		D::log($this->models->User->loggedIn(), 'is logged in');
		
		$this->libs->Template->set(array(
			'projects' => $this->models->Projects->all()
		));

		
		$this->libs->Template->render('dashboard.php');
		//
	}
	function projects() {
		$this->libs->Template->set(array(
			'projects' => $this->models->Projects->all()
		));
		$this->libs->Template->render('projects.php');
	}
	function project() {
		$this->libs->Template->set(array(
			'project' => $this->models->Projects->get($this->libs->Uri->get(1))->one()
		));
		$this->libs->Template->render('project.php');
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
	function __DudeWheresMyCar() {
		header('HTTP/1.0 404 Not Found');
		echo 'Dude where\'s my car?';
	}
}