<?php

App::uses('CakeSessionHandlerInterface', 'Model/Datasource/Session');

class RedisSession extends Object implements CakeSessionHandlerInterface {

	protected $_prefix = 'PHPREDIS_SESSION';

	protected $_timeout = 600;

	protected $_store = null;

	protected $_host = 'localhost';

	protected $_port = 6379;

	protected $_userMapPrefix = 'USERS';

	protected $_userMapField = 'id';

	public function __construct() {
		$this->_store = new Redis();
	}

	protected function _configure() {
		$config = Configure::read('Session');
		$this->_timeout = $config['timeout'];
		if (!empty($config['handler']['host'])) {
			$this->_host = $config['handler']['host'];
		}
		if (!empty($config['handler']['port'])) {
			$this->_port = $config['handler']['port'];
		}
		if (!empty($config['handler']['prefix'])) {
			$this->_prefix = $config['handler']['prefix'];
		}
		if (!empty($config['handler']['userMap'])) {
			$this->_userMap = $config['handler']['userMap'];
		}
		if (!empty($config['handler']['userMapPrefix'])) {
			$this->_userMapPrefix= $config['handler']['userMapPrefix'];
		}
		if (!empty($config['handler']['userMapField'])) {
			$this->_userMapField= $config['handler']['userMapField'];
		}
	}

	public function open() {
		$this->_configure();
		$connected = $this->_store->connect($this->_host, $this->_port);
		return $connected;
	}

	public function close() {
		return $this->_store->close();
	}

	public function read($id) {
		$id = sprintf('%s:%s', $this->_prefix, $id);
		return $this->_store->get($id);
	}

	public function write($id, $data) {
		$id = sprintf('%s:%s', $this->_prefix, $id);
		$stored = $this->_store->setex($id, $this->_timeout, $data);
		if ($this->_userMap) {
			$this->_storeUserMap($id, $data);
		}
		return $stored;
	}

	protected function _storeUserMap($id, $data) {
		$decoded = wddx_deserialize($data);
		$uid = Hash::get($decoded, AuthComponent::$sessionKey . '.id');
		if (empty($uid)) {
			return;
		}
		$usermap = $this->_userMapPrefix . ':' . $uid;
		return $this->_store->setex($usermap, $this->_timeout, $id);
	}

	protected function _removeUserMap($id) {
		$data = $this->_store->get($id);
		$decoded = wddx_deserialize($data);
		$uid = Hash::get($decoded, AuthComponent::$sessionKey . '.id');
		if (empty($uid)) {
			return;
		}
		$usermap = $this->_userMapPrefix . ':' . $uid;
		return $this->_store->del($usermap);
	}

	public function destroy($id) {
		$id = sprintf('%s:%s', $this->_prefix, $id);
		if ($this->_userMap) {
			$this->_removeUserMap($id);
		}
		return $this->_store->delete($id);
	}

	public function gc($expires = null) {
	}

}
