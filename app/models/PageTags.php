<?
class PageTags extends SweetModel {

	var $tableName = 'PageTags';
	var $pk = null;
	var $fields = array(
		'page' => array('int', 11),
		'tag' => array('int', 11),
		'user' => array('int', 11)
	);
	var $relationships = array(
		'page' => array(
			'Pages',
			'id'
		),
		'tag' => array(
			'Tags',
			'id'
		),
		'user' => array(
			'User',
			'id'
		)
	);
	
	function __construct() {}
}