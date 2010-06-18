<?

class SweetRow {
	$name;
	$table;
	
}

class SweetModel extends App {

/*
	CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `content` text,
  `dateCreated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
*/

	$model;
	$items;
	
	
	
	
	
	function all() {
	
	}
	
	function one() {
	
	}
	
	var $_buildOptions = array();
	
	function get() {
		$this->_buildOptions['get'] = func_get_args();
	}
	
	var $_filter;
	
	function filter() {
		$this->_buildOptions['filter'] = func_get_args();
	}
	
	
	
	function limit() {
		$this->_buildOptions['limit'] = func_get_args();
	}
	
	
	
	
	
	
	


}



