<?php

$userMap = true;

Configure::write('Session', array(
	'defaults' => 'php',
	'handler' => array(
		'engine' => 'RedisSession.RedisSession',
		'userMap' => $userMap,
		//'userMapPrefix' => 'USERS',
		//'userMapField' => 'id',
		//'prefix' => 'PHPREDIS_SESSION',
		//'host' => 'localhost',
		//'port' => '6379',
	),
));

if ($userMap) {
	if (extension_loaded('wddx')) {
		ini_set('session.serialize_handler', 'wddx');
	} else {
		CakeLog::critical('wddx not available. user map not enabled');
	}
}
