<?php

Class Main extends App {

	static $urlPattern = array();

	function __construct() {
		//$this->lib(array('Template', 'databases/Query'));
	}

	function index() {
		D::show('index func');
		//$this->template->render('default.php');
	}
}