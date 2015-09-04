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
?>
<p><?php echo __('Total Sessions: %d', $totalSessions); ?></p>
<p><?php echo __('Total Users: %d', count($userSessions)); ?></p>
<?php
$this->end();


$header = array('ID', 'Name', 'Email', 'TTL', 'Action');

$rows = array();
foreach ($userSessions as $userSession) {
	$actions = array();
	$disconnectLink = $this->Form->postLink(__('Disconnect'), array(
			'action' => 'disconnect',
			$userSession['Auth']['User']['id']
		), array(
			'escape' => true,
		), 'Are you sure?'
	);

	if ($userSession['Auth']['User']['id'] == $this->Session->read('Auth.User.id')) {
		$disconnectLink = null;
	}

	$actions[] = $disconnectLink;
	$rows[] = array(
		$userSession['Auth']['User']['id'],
		$userSession['Auth']['User']['name'],
		$userSession['Auth']['User']['email'],
		$userSession['ttl'],
		implode(' ', $actions),
	);
}

$this->append('table-heading', $this->Html->tableHeaders($header));
$this->append('table-body', $this->Html->tableCells($rows));
