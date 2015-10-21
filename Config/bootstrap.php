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

if (class_exists('CroogoNav')) {
	CroogoNav::add('extensions.children.RedisSession', array(
		'title' => __d('redis_session', 'Login Sessions'),
		'url' => array(
			'plugin' => 'redis_session',
			'controller' => 'session_stores',
			'action' => 'index'
		),
		'children' => array(),
	));
}
