<?php

Class Admin extends App {

	static $urlPattern = array();

	function __construct() {
		$this->lib(array('Template', 'Uri'));
		$this->model('User');
		$this->helper('misc');
		$this->libs->Template->set(array(
			'message' => $this->lib('Session')->flash('AdminMessage')
		));
		
		//
	}

	function index() {
		if($this->models->User->loggedIn()) {
			$this->libs->Template->set(array('title'=>'Dashboard','content'=> $this->libs->Template->get('admin/parts/dashboard')))->render('admin/bases/content');
		} else {
			$this->libs->Template->set(array('title'=>'Please login','form'=> $this->libs->Template->get('admin/parts/login')))->render('admin/bases/form');
		}
	}
	
	function users() {
		if(!$this->models->User->loggedIn()) {
			return $this->libs->Uri->redirect('admin');
		}
		//V::get('admin/pages/brief', array('page' => $v))
		$this->libs->Template->set(array(
			'title' => 'Users',
			'actions' => B::a(array('href'=> SITE_URL .'admin/adduser'), 'Add User'),
			'items' => $this->model('User')->limit(10)->all(),
			'itemsEach' => function($v) {
				//return B::li($v->fullname);
				return B::li(V::get('admin/users/brief', array('user'=> $v)));
			}
		))->render('admin/bases/list');
	}
	
	function user() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$user = $this->model('pages')->get($this->libs->Uri->get(2))->one();
		D::log($user, 'user');
		if(!$user) {
			$this->libs->Template->set(array(
				'message' => 'User could not be found',
				'title' => 'No User Found',
				'content' => ''
			));	
		} else {
			$this->libs->Template->set(array(
				'title' => 'User: ' . $user->fullname,
				'content' => V::get('admin/users/detail', array('user' => $user))
			));
		}		
		$this->libs->Template->render('admin/bases/content');
	}
	
	
	function pages() {
		if(!$this->models->User->loggedIn()) {
			return $this->libs->Uri->redirect('admin');
		}
		//V::get('admin/pages/brief', array('page' => $v))
		$this->libs->Template->set(array(
			'title' => 'Pages',
			'actions' => B::a(array('href'=> SITE_URL .'admin/addpage'), 'Add Page'),
			'items' => $this->model('Pages')->limit(10)->all(),
			'itemsEach' => function($v) {
				return B::li(V::get('admin/pages/brief', array('page'=> $v)));
			}
		))->render('admin/bases/list');
	}
	
	function page() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$page = $this->model('pages')->get($this->libs->Uri->get(2))->one();
		$this->libs->Template->set(array(
			'title' => 'Page: ' . $page->title,
			'content' => 'page test'
		));
		/*
		@todo
		Make this template a genric for a listing
		*/
		$this->libs->Template->render('admin/bases/content');
	}
	
	function addpage() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$this->libs->Template->set(array(
			'title' => 'Add Page',
			'form' => $this->libs->Template->get('admin/parts/addpage.php')
		));
		
		$this->libs->Template->render('admin/bases/form');
	}
	
	function doaddpage() {
		if(!$this->models->User->loggedIn()) {
			return $this->libs->Uri->redirect('admin');
		}
		if(!$this->model('Pages')->add($_POST)) {
			$this->lib('Session')->flash('AdminMessage', 'Add Page Failed');
			return $this->libs->Uri->redirect('admin/addpage');
		}
		$this->lib('Session')->flash('AdminMessage', $_POST['title'] . ' Added Successfully');
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