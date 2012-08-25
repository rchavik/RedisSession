<?php

App::uses('CakeSessionHandlerInterface', 'Model/Datasource/Session');

class RedisSession extends Object implements CakeSessionHandlerInterface {

	protected $_prefix = 'PHPREDIS_SESSION';

	protected $_timeout = 600;

	protected $_store = null;

	protected $_host = 'localhost';

	protected $_port = 6379;

	public function __construct() {
		$this->_store = new Redis();
	}

	protected function _configure() {
		$config = Configure::read('Session');
		if (!empty($config['handler']['host'])) {
			$this->_host = $config['handler']['host'];
		}
		if (!empty($config['handler']['port'])) {
			$this->_port = $config['handler']['port'];
		}
		if (!empty($config['handler']['prefix'])) {
			$this->_prefix = $config['handler']['prefix'];
		}
		$this->_timeout = $config['timeout'];
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
		return $stored;
	}

	public function destroy($id) {
		$id = sprintf('%s:%s', $this->_prefix, $id);
		return $this->_store->delete($id);
	}

	public function gc($expires = null) {
	}

}
