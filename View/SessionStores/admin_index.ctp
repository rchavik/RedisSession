<?php

foreach ($userSessions as $userSession) {
	$rows[] = array(
		$userSession['Auth']['User']['id'],
		$userSession['Auth']['User']['name'],
		$userSession['Auth']['User']['email'],
		$this->Form->postLink(__('Disconnect'), array(
			'action' => 'disconnect',
			$userSession['Auth']['User']['id']
		), null, 'Are you sure?'),
	);
}

$cells = $this->Html->tableCells($rows);
echo $this->Html->tag('table', $cells);
