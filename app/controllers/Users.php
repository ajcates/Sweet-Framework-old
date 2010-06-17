<?php

Class Users extends App {

	static $urlPattern = array();

	function __construct() {
		$this->lib(array('Template', 'Uri'));
		$this->model('User');
	}

	function index() {
	
		//Write to the log.
		//D::show();
		
		
		
		//D::show('hello world');
		//Show somethign to the screen.
		//D::show(, 'Some Projects');
		
		
		
		$this->libs->Template->set(array(
		));

		
		$this->libs->Template->render('users/main.php');
		//
	}
	
	function login() {
		$this->libs->Template->set(array(
		));
		$this->libs->Template->render('users/login.php');
	}
	
	function dologin() {
		D::log($_POST, '_POST');
		
		$this->lib('Session');
		
		if(isset($_POST['username']) && isset($_POST['password']) && $this->models->User->logIn($_POST['username'], $_POST['password'])) {
			//D::show('we good');
			$this->libs->Session->flash('loginFail', false);
		} else {
			//D::show('fail');
			$this->libs->Session->flash('loginFail', true);
		}
		$this->libs->Uri->redirect('/');
	}
	
	function logout() {
		$this->lib('Session')->destroy();
		$this->libs->Uri->redirect('/');
	}
	
	function add() {
		$this->libs->Template->set(array(
		));
		$this->libs->Template->render('users/add.php');
	}
}