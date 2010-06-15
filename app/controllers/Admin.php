<?php

Class Admin extends App {

	static $urlPattern = array();

	function __construct() {
		$this->lib(array('Template', 'Uri'));
		$this->model('User');
	}

	function index() {
	
		/*
		@todo
		Make the admin message work.
		*/
		
		$this->libs->Template->set(array(
			
		));
		
		if($this->models->User->loggedIn()) {
			$this->libs->Template->render('admin/dashboard.php');
		} else {
			$this->libs->Template->render('admin/login.php');
		}
	}
	
	function pages() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$this->libs->Template->set(array(
		));
		/*
		@todo
		Make this template a genric for a listing
		*/
		$this->libs->Template->render('admin/pages.php');
	}
	
	function addpage() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		
		$this->libs->Template->render('admin/addpage.php');
	}
	
	function doaddpage() {
		if(!$this->models->User->loggedIn()) {
			return $this->libs->Uri->redirect('admin');
		}
		
		if(!$this->model('Pages')->add($_POST)) {
			$this->lib('Session')->flash('AdminMessage', 'Add Page Failed');
			return $this->libs->Uri->redirect('admin/addpage');
		}
		
		$this->lib('Session')->flash('AdminMessage', 'Page Added Successfully');
		return $this->libs->Uri->redirect('admin/pages');
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