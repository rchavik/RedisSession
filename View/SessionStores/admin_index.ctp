<?php echo $this->Html->tag('h2', __('Sessions')); ?>
<p><?php echo __('Total Sessions: %d', $totalSessions); ?></p>
<p><?php echo __('Total Users: %d', count($userSessions)); ?></p>
<br />
<?php


$header = array('ID', 'Name', 'Email', 'TTL', 'Action');

$rows = array();
foreach ($userSessions as $userSession) {
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

$cells  = $this->Html->tableHeaders($header);
$cells .= $this->Html->tableCells($rows);
echo $this->Html->tag('table', $cells, array('class' => 'table'));
