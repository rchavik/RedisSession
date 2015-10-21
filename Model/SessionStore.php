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
		if (!empty($handler['password'])) {
			$this->_store->auth($handler['password']);
		}
		if (!empty($handler['db'])) {
			$this->_store->select($handler['db']);
		}
	}

	public function userMap($userId = null) {
		if ($userId) {
			$mapKey = 'USERS:' . intval($userId);
			return $this->_store->get($mapKey);
		}
		return $this->_store->keys('USERS:*');
	}

	public function sessionData($key) {
		$data = $this->_store->get($key);
		$data = wddx_deserialize($data);
		$data['ttl'] = $this->_store->ttl($key);
		return $data;
	}

	public function disconnect($id) {
		$map = 'USERS:' . $id;
		$session = $this->_store->get($map);
		$this->_store->delete($session);
		$this->_store->delete($map);
	}

	public function total() {
		return count($this->_store->keys('PHP*'));
	}

}
