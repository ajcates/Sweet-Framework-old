<?
/*
@todo
Make this code really fast, it basicly is ran on every page no matter what. 
 */
 
class Session extends App {
	
	private $_id = false;
	private $_valid = false;
	private $_changed = array();
	private $_new = array();
	private $_data = array();
	private $_flashRemove = array();
	private $_flashAdd = array();
	private $_flash = array();
	
	function __construct() {
		$this->getLibrary('Config', 'config');
		$this->getLibrary('Query', 'query');
		
		//$this->config->get('Session', 'sessionTableName')
		
		//$this->config->get('Session', 'sessionDataTableName')
		D::log('FRESH N EASY');
		$this->start();
		
	}
	
	function start() {
		return $this->checkCookie();
	}
	
	function loadData($data) {
		foreach($data as $d) {
			if($d->flash) {
				$this->_flash[$d->name] = $d->value;
				$this->_flashRemove[] = $d->name;
			} else {
				$this->_data[$d->name] = $d->value;
			}
		}
	}
	
	function checkCookie() {
		if($this->_valid === true) {
			return true;
		}
		$cookieName = $this->config->get('Session', 'cookieName');
		if(isset($_COOKIE[$cookieName])) {
			//D::log($_COOKIE[$cookieName], 'Cookie Set');
			$cookie = explode('_', $_COOKIE[$cookieName]);
			$row = f_first($this->query->select('*')->from($this->config->get('Session', 'tableName'))->where(array('id' => f_first($cookie)))->results());
			if(!empty($row) && $this->encryptCheckString($row->uid) === f_last($cookie)) {
				if($this->encryptCheckString($this->getCheckString($row->uid)) === $row->checkString) {
					$this->_valid = true;
					$this->_id = $row->id;
					$this->loadData($this->query->select('*')->from($this->config->get('Session', 'dataTableName'))->where(array('session' => $this->_id))->results());
					D::log($this->_data, '_data');
					return true;
				}
			}
		}
		$uid = $this->getUid();
		if(!$this->saveCookie($this->generateCookie($this->getCheckString($uid), $uid)) ) {
			D::warn('Cookie Failed');
			return false;
		} else {
			return true;
		}
	}
	
	function getUid() {
		return uniqid(mt_rand(), true);
	}
	
	function getCheckString($uid) {
		return $uid . '_' . join('', $this->config->get('Session', 'use'));
	}
	function encryptCheckString($checkString) {
		return hash_hmac($this->config->get('Session', 'hashFunction'), $checkString, $this->config->get('Session', 'cookieSecret'));
	}
	
	function generateCookie($checkString, $uid) {
		//@todo remove the mssql depencdy here.
		return f_first(f_first( $this->query->insert(array(
			'checkString' => $this->encryptCheckString($checkString),
			'created' => time(),
			'uid' => $uid
		))->into($this->config->get('Session', 'tableName'))->go()->getDriver()->query('SELECT max(@@IDENTITY) AS \'id\' FROM ' . $this->config->get('Session', 'tableName'))->get('assoc') )) . '_' . $this->encryptCheckString($uid);
	}
	
	function saveCookie($info) {
		//@todo pull out into a cookie library.
		//setcookie($cookieName, $encyptedCookieString, $expire, $path, $domain, $secure);
		D::log($info, 'Setting cookie');
		return setcookie($this->config->get('Session', 'cookieName'), $info, time() + $this->config->get('Session', 'timeout'), '/', null, $this->config->get('Session', 'sslCookies'));
	}
	
	function data($key, $value=null) {
		if($this->checkCookie()) {
			if(!isset($value)) {
				return @$this->_data[$key];
			}
			if(is_array($key)) {
				foreach($key as $k => $v) {
					$this->data($k, $v);
				}
				return $this->data(f_last($key));
			}
			if(isset($this->_data[$key])) {
				$this->_changed[] = $key;
			} else {
				$this->_new[] = $key;
			}
			$this->_data[$key] = $value;
		}
	}
	
	function flash($key, $value=null) {
		if($this->checkCookie()) {
			if(!isset($value)) {
				return @$this->_flash[$key];
			}
			if(is_array($key)) {
				foreach($key as $k => $v) {
					$this->flash($k, $v);
				}
				return $this->flash(f_last($key));
			}
			$this->_flash[$key] = $value;
			$this->query->insert(array('name' => $key, 'value' => $this->_flash[$key], 'session' => $this->_id, 'flash' => 1))->into($this->config->get('Session', 'dataTableName'))->go();
		}
	}
	
	function save() {
		if($this->checkCookie()) {
			foreach($this->_changed as $key) {
				$this->query->update($this->config->get('Session', 'dataTableName'))->where(array('name' => $key, 'session' => $this->_id))->set(array('value' => $this->_data[$key]))->go();
			}
			foreach($this->_new as $key) {
				$this->query->insert(array('name' => $key, 'value' => $this->_data[$key], 'session' => $this->_id))->into($this->config->get('Session', 'dataTableName'))->go();
			}
			if(!empty($this->_flashRemove)) {
				$this->query->delete()->where(array('name' => $this->_flashRemove, 'session' => $this->_id, 'flash' => 1))->from($this->config->get('Session', 'dataTableName'))->go();
			}
		}
	}
	
	function destory() {
		setcookie ($this->config->get('Session', 'cookieName'), '', time() - 86400);
		$this->query->delete()->where(array('id' => $this->_id))->from($this->config->get('Session', 'tableName'))->go();
	}
}