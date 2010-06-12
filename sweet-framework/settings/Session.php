<?
SweetFramework::getClass('lib', 'Session')->setAll('Debug', array(
	'timeout' => 31536000,
	'hashFunction' => 'sha512',
	'cookieSecret' => 'h07rsouY43hSNpNAVvcEKDrXkzs4rasdfasdfkOL9iUe3Vx5RjTfrthw49BC6xeGNvw2nI55z1RH',
	'cookieName' => 'crad-token',
	'use' = array($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']),
	'sslCookies' => false,
	'tableName' => 'sessions',
	'dataTableName' => 'sessionData'
));
