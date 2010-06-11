<?php
SweetFramework::loadClass('lib', 'Config')->setAll('SweetFramework', array(
	'benchMark' => false,
	'niceUrls' => false,
	'app' => array(
		'folder' => 'app',
		'paths' => array(
			'config' => 'settings',
			'lib' => 'libs',
			'model' => 'models',
			'helper' => 'helpers',
			'controller' => 'controllers'
		)
	)
));