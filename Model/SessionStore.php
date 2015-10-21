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

	public function userMap($userId = null, $cursor = null) {
		if ($userId) {
			$mapKey = 'USERS:' . intval($userId);
			return $this->_store->get($mapKey);
		}
		return $this->_store->scan($cursor, 'USERS:*');
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

	public function destroy($sessionId) {
		$key = 'PHPREDIS_SESSION:' . $sessionId;
		$data = $this->sessionData($key);
		if (isset($data['Auth']['User']['id'])) {
			$this->_store->delete($key);
			$map = $this->userMap($data['Auth']['User']['id']);
			if ($key === $map) {
				$this->_store->delete($map);
			}
		}
	}

	public function total() {
		return count($this->_store->keys('PHP*'));
	}

	public function sessionList($cursor = null) {
		$result = $this->_store->scan($cursor, 'PHP*');
		return array($cursor, $result);
	}

}
