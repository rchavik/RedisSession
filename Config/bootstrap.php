<?php

Configure::write('Session', array(
	'handler' => array(
		'engine' => 'RedisSession.RedisSession',
		//'prefix' => 'PHPREDIS_SESSION',
		//'host' => 'localhost',
		//'port' => '6379',
	),
));
