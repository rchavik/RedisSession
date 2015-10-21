<?php

$title = __d('redis_session', 'Sessions');
$this->set('title_for_layout', $title);
$this->set('modelClass', $title);
$this->set('showActions', false);

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => $this->Theme->getIcon('home')))
	->addCrumb($title, '/' . $this->request->url);

$this->append('table-heading');
$this->end();

$header = array('ID', 'Name', 'Email', 'TTL', 'Action');

$rows = array();
foreach ($userSessions as $sessionKey => $userSession) {
	$actions = array();
	list(, $sessionId) = explode(':', $sessionKey);

	$disconnectLink = $this->Form->postLink(__('Disconnect'), array(
		'action' => 'disconnect',
		$sessionId,
	), array(
		'escape' => true,
	), 'Are you sure?');

	if (isset($userSession['Auth']['User'])) {

		if ($userSession['Auth']['User']['id'] == $this->Session->read('Auth.User.id') &&
			($sessionId == session_id())
		) {
			$disconnectLink = null;
		}

		$actions[] = $disconnectLink;

		$name = $userSession['Auth']['User']['name'];
		$email = $userSession['Auth']['User']['email'];
		$ttl = $userSession['ttl'];
		$action = implode(' ', $actions);
	} else {
		$action = $disconnectLink;
		$name = $email = $ttl = null;
	}

	$rows[] = array(
		$sessionId,
		$name,
		$email,
		$ttl,
		$action,
	);
}

$this->append('table-heading', $this->Html->tableHeaders($header));
$this->append('table-body', $this->Html->tableCells($rows));

$this->append('paging');
	echo $this->Html->link('<< first', array('action' => 'index')) . ' ';
	if ($next):
		echo $this->Html->link('next >', array('action' => 'index', $next));
	endif;
$this->end();
