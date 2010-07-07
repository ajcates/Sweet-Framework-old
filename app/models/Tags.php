<?
class Tags extends SweetModel {

	var $tableName = 'Tags';
	var $pk = 'id';
	var $fields = array(
		'id' => array('int', 11),
		'name' => array('varchar', 256)
	);
	var $relationships = array(
		'pageTags' => array(
			'id' => array(
				'PageTags',
				'tag'
			)
		)
	);
	
	function __construct() {}
}