<?php
//location of the mysql db
SweetFramework::getClass('lib', 'Config')->setAll('databases', array(
	'default' => array(
		'driver' => 'My_SQL',
		'host' => 'localhost',
		'username' => 'sweetie',
		'password' => 'password',
		'databaseName' => 'sweet-framework'
	)
));