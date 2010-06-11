<?
class Template extends App {
	
	private $data = array();
	
	public function __construct($data=null) {}
	
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	public function __get($name) {
		return $this->data[$name];
	}
	
	public function set($data) {
		$this->data = array_merge($this->data, $data);
		return $this;
	}
	
	public function render($fileNameThatNoOneBetterUse) {
		extract($this->data);
		include(LOC . $this->getLibrary('Config')->get('SweetFramework', 'theme') . '/templates/' . $fileNameThatNoOneBetterUse);
	}
	
	public function get($file=null) {
		ob_start();
		$this->render($file);
		return ob_get_clean();
	}
}