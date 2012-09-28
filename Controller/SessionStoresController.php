<?php

class SessionStoresController extends AppController {

	public $uses = array('RedisSession.SessionStore');

	public function admin_index() {
		$userMaps = $this->SessionStore->userMap();
		foreach ($userMaps as $userMap) {
			$data = $this->SessionStore->sessionData($userMap);
			$userSessions[$userMap] = $data;
		}
		$this->set(compact('userMaps', 'userSessions'));
	}

	public function admin_disconnect($id) {
		$this->SessionStore->disconnect($id);
		$this->redirect($this->referer());
	}

}
