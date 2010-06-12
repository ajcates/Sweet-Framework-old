<?




class Projects extends App {



	function __construct() {
		$this->lib(array('databases/Query'));
	}
	
	function get($count=10, $offset=0) {
		//description
		return $this->libs->Query->select('*')->from('projects')->limit($offset, $count)->results();
	}
}