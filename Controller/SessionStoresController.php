<?php

class SessionStoresController extends AppController {

	public $uses = array('RedisSession.SessionStore');

	public function admin_index($next = null) {
		$prev = $next;
		$result = $this->SessionStore->sessionList($next);
		list($next, $sessionList) = $result;
		$userSessions = array();
		foreach ($sessionList as $key) {
			$userSessions[$key] = $this->SessionStore->sessionData($key);
		}
		$this->set(compact('userSessions', 'next'));
	}

	public function admin_disconnect($sessionId) {
		$this->SessionStore->destroy($sessionId);
		$this->redirect($this->referer());
	}

}
