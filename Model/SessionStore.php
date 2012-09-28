<?php

class SessionStore extends AppModel {

	public $useTable = false;

	protected $_store;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->_store = new Redis();
		$handler = Configure::Read('Session.handler');
		$host = 'localhost';
		$port = 6379;
		if (!empty($handler['host'])) {
			$host = $handler['host'];
		}
		if (!empty($handler['port'])) {
			$port = $handler['port'];
		}
		$this->_store->connect($host, $port);
	}

	public function userMap() {
		return $this->_store->keys('USERS:*');
	}

	public function sessionData($key) {
		$id = $this->_store->get($key);
		$data = $this->_store->get($id);
		return wddx_deserialize($data);
	}

	public function disconnect($id) {
		$map = 'USERS:' . $id;
		$session = $this->_store->get($map);
		$this->_store->delete($session);
		$this->_store->delete($map);
	}

}
