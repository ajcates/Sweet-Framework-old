<?
class User extends SweetModel {

	var $tableName = 'Users';
	
	var $pk = 'id';
	
	var $fields = array(
		'id' => array('int', 11),
		'username' => array('varchar', 256),
		'email' => array('varchar', 256),
		'fullname' => array('varchar', 256),
		'password' => array('varchar', 256)
	);
	
	var $relationships = array();


	function __construct() {
		$this->lib(array('databases/Query', 'Session'));
	}
	
	function get($id) {
		//description
		if(is_numeric($id)) {
			$this->libs->Query->where(array('id' => $id));
		} else if(is_string($id)) {
			$this->libs->Query->where(array('username' => $id));
		} else if(is_array($id)) {
			$this->libs->Query->where($id);
			//$this->libs->Query->select('*')->from('projects')->limit($offset, $count)->results();
		}
		return $this;
	}
	
	function limit($count=10, $offset=0) {
		$this->libs->Query->limit($count, $offset);
		return $this;
	}
	
	function all() {
		return $this->libs->Query->select('*')->from($this->tableName)->results();
	}
	
	function one() {
		return f_first($this->libs->Query->select('*')->limit(1, 0)->from($this->tableName)->results());
	}
	
	/**
	 * add function. Takes a key/val of user details then adds it to the db.
	 * 
	 * @access public
	 * @param array $user.
	 * @return void
	 */
	function add($user=null) {
		if(empty($user['fullname']) || empty($user['username']) || $user['password'] !== $user['passwordcheck']) {
			return false;
		}
		unset($user['passwordcheck']);
		$user['password'] = $this->hashPassword($user['password']);
		return $this->libs->Query->insert($user)->into($this->tableName)->results('bool');
	}
	
	private function hashPassword($password) {
		return sha1($password . $this->lib('Config')->get('site', 'salt'));
	}

	function edit($id, $user) {
		if(empty($user['password'])) {
			unset($user['password'], $user['passwordcheck']);
		} else if($user['password'] !== $user['passwordcheck']) {
			return false;
		} else {
			unset($user['passwordcheck']);
			$user['password'] = $this->hashPassword($user['password']);
		}
		return $this->get($id)->libs->Query->update($this->tableName)->set($user)->results('bool');
	}
	
	/**
	 hash()ogIn function. Trys to login in the user
	 * 
	 * @access public
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	function logIn($username, $password) {
		$user = $this->get($username)->one();
		if(empty($user) || $user->password !== $this->hashPassword($password)) {
			$this->libs->Session->data('loggedIn', false);
			return false;
		}
		$this->libs->Session->data('loggedIn', true);
		$this->libs->Session->data('userId', $user->id);
		return true;
	}
	
	function getCurrentUser() {
		return $this->get($this->libs->Session->data('userId'))->one();
	}
	
	/**
	 * loggedIn function. checks to see if the person is currently logged in
	 * 
	 * @access public
	 * @return bool
	 */
	function loggedIn() {
		return (bool) $this->libs->Session->data('loggedIn');
	}
}