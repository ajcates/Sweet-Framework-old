<?php

Class Admin extends App {

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
		
		if($this->models->User->loggedIn()) {
			$this->libs->Template->render('admin/dashboard.php');
		} else {
			$this->libs->Template->render('admin/login.php');
		}
	}
	
	function pages() {
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
		$this->libs->Uri->redirect('/admin');
	}
	
	function logout() {
		$this->lib('Session')->destroy();
		$this->libs->Uri->redirect('/');
	}
}