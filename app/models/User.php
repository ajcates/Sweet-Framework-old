<?
class User extends App {

	var $tableName = 'users';

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
	 * logIn function. Trys to login in the user
	 * 
	 * @access public
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	function logIn($username, $password) {
		$user = $this->get($username)->one();
		if(empty($user) || $user->password !== $password) {
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