<?
class Admin extends App {

	static $urlPattern = array(
		'/user\/add\/do/' => 'doaddUser',
		'/user\/add/' => 'addUser',
		'/user\/edit\/do\/(.*)/' => 'doeditUser',
		'/user\/edit\/(.*)/' => 'editUser',
		'/user\/delete\/(.*)/' => 'deleteUser',
		'/user\/duplicate\/(.*)/' => 'duplicateUser'
	);

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
	
	function sandbox() {
		/*
		if(!$this->models->User->loggedIn()) {
			return $this->libs->Uri->redirect('admin');
		}
		*/
		
		$this->libs->Template->set(array(
			'title'=>'Dashboard',
			'content'=> $this->libs->Template->get('admin/parts/sandbox', array(
				'test' => 'Hello Sandbox World!',
				//'pages' => D::log($this->model('Pages')->pull('user')->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->pull('user', array('tags' => array('tag', 'user' => 'type') ))->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->pull('user', 'tags')->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->pull('user', array('tags' => array('tag', 'user') ))->all(), 'Pages')
				'pages' => $this->model('Pages')->pull(array('user', 'tags' => array('tag', 'user') ) )->all()
				//'pages' => D::log($this->model('Pages')->pull('user', array('tags') ))->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->pull('user', 'tags' ))->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->all(), 'Pages')
				//'pages' => D::log($this->model('Pages')->limit(3)->pull('user')->all(), 'Pages')
			))
		))->render('admin/bases/content');
		
		/*
		
		
		Any time there is a key:
			- there is a sub join needed 
				- Sub joins need:
					- left field name
						- is the key in the parents relation ship structure
							/%
							*'user'* => array(
								'User',
								'id'
							),
							%/
					- right field name
						- is the last element in the parents relation ship structure
							/%
							'user' => array(
								'User',
								*'id'*
							),
							%/
					- table alias
					- right table name = table alias
					- left table name
						- Is the parents alias 
					
					
			- this sub join is based on the realtionShip structure of the key in the current model
		
		
		//Example of a good query:		
		SELECT
		Pages.id, Pages.user, Pages.slug, Pages.title, Pages.description, Pages.content, Pages.dateCreated, user.id AS 'user.id', user.username AS 'user.username', user.email AS 'user.email', user.fullname AS 'user.fullname', user.password AS 'user.password', tags.page AS 'tags.page', tags.tag AS 'tags.tag', tags.user AS 'tags.user', tags_tag.id AS 'tags.tag.id', tags_tag.name AS 'tags.tag.name', tags_user.id AS 'tags.user.id', tags_user.username AS 'tags.user.username', tags_user.email AS 'tags.user.email', tags_user.fullname AS 'tags.user.fullname', tags_user.password AS 'tags.user.password'
		
		FROM (SELECT * FROM Pages) AS Pages	
		 LEFT JOIN Users AS user
			ON Pages.user = user.id 
		 LEFT JOIN PageTags AS tags
			ON Pages.id = tags.page
		 LEFT JOIN Tags AS tags_tag
			ON tags.tag = tags_tag.id
		 LEFT JOIN Users AS tags_user
			ON tags.user = tags_user.id
		
		
		//////////////////==========
		
		pull('user', array('tags' => array('tag', 'user') )):
		SELECT
		Pages.id, Pages.user, Pages.slug, Pages.title, Pages.description, Pages.content, Pages.dateCreated, user.id AS 'user.id', user.username AS 'user.username', user.email AS 'user.email', user.fullname AS 'user.fullname', user.password AS 'user.password', tags.page AS 'tags.page', tags.tag AS 'tags.tag', tags.user AS 'tags.user', tags_tag.id AS 'tags.tag.id', tags_tag.name AS 'tags.tag.name', tags_user.id AS 'tags.user.id', tags_user.username AS 'tags.user.username', tags_user.email AS 'tags.user.email', tags_user.fullname AS 'tags.user.fullname', tags_user.password AS 'tags.user.password'
		
		FROM (SELECT * FROM Pages) AS Pages	
		LEFT JOIN Users AS user
			ON Pages.user = user.id 
		LEFT JOIN PageTags AS tags
			ON Pages.id = tags.page
		LEFT JOIN Tags AS tags_tag
			ON tags.tag = tags_tag.id
		LEFT JOIN Users AS tags_user
			ON tags.user = tags_user.id
		*/
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
	
	function editUser() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$user = $this->model('User')->get($this->libs->Uri->get(0))->one();
		$this->libs->Template->set(array(
			'title' => 'Edit User: ' . $user->fullname,
			'form' => $this->libs->Template->get('admin/parts/edituser', array('user' => $user))
		));
		$this->libs->Template->render('admin/bases/form');
		
	}
	
	function addUser() {
		if(!$this->models->User->loggedIn()) {
			$this->libs->Uri->redirect('admin');
		}
		$this->libs->Template->set(array(
			'title' => 'Add User',
			'form' => $this->libs->Template->get('admin/parts/adduser')
		));
		$this->libs->Template->render('admin/bases/form');
	}
	
	function doaddUser() {
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
		if(!$this->model('User')->add($_POST)) {
			$this->lib('Session')->flash('AdminMessage', 'Add User Failed');
			return $this->libs->Uri->redirect('admin/user/add');
		}
		$this->lib('Session')->flash('AdminMessage', $_POST['fullname'] . 'Was Added Successfully');
		return $this->libs->Uri->redirect('admin/users');
	}
	
	
	function doeditUser() {
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
		if(!$this->model('User')->edit($this->libs->Uri->get(0), $_POST)) {
			$this->lib('Session')->flash('AdminMessage', 'Edit User Failed');
			return $this->libs->Uri->redirect('admin/user/edit/' . $this->libs->Uri->get(0));
		}
		$this->lib('Session')->flash('AdminMessage', $_POST['fullname'] . ' Was Edited Successfully');
		return $this->libs->Uri->redirect('admin/users');
	}
	
	function deleteUser() {
		D::show('delete');
		D::show($this->libs->Uri->get(0));
	}
	
	function duplicateUser() {
		D::show('duplicate');
		D::show($this->libs->Uri->get(0));
	}
	
	function user() {
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
		$user = $this->model('User')->get($this->libs->Uri->get(2))->one();
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
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
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
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
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
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
		$this->libs->Template->set(array(
			'title' => 'Add Page',
			'form' => $this->libs->Template->get('admin/parts/addpage')
		));
		
		$this->libs->Template->render('admin/bases/form');
	}
	
	function doaddpage() {
		if(!$this->models->User->loggedIn()) { return $this->libs->Uri->redirect('admin');}
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