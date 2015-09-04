<?php

$handlerConfig = Hash::merge(array(
	'engine' => 'RedisSession.RedisSession',
	'userMap' => true,
), Configure::consume('RedisSession.handler'));

Configure::write('Session', Hash::merge(
	Configure::read('Session'),
	array(
		'defaults' => 'php',
		'handler' => $handlerConfig,
	)
));

if (isset($handlerConfig['userMap'])) {
	if (extension_loaded('wddx')) {
		ini_set('session.serialize_handler', 'wddx');
	} else {
		CakeLog::critical('wddx not available. user map not enabled');
	}
}
