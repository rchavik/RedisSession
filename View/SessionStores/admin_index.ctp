<?php echo $this->Html->tag('h2', __('Sessions')); ?>
<p><?php echo __('Total Sessions: %d', $totalSessions); ?></p>
<p><?php echo __('Total Users: %d', count($userSessions)); ?></p>
<br />
<?php


$header = array('ID', 'Name', 'Email', 'Action');

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

$cells  = $this->Html->tableHeaders($header);
$cells .= $this->Html->tableCells($rows);
echo $this->Html->tag('table', $cells);
