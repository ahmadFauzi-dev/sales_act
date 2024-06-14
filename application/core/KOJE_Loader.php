<?php
class KOJE_Loader extends CI_Loader{
	protected $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
	}
	public function template($view_name='', $vars = array(), $auth=true, $show='all')
  {
		if ($auth && !$this->CI->ion_auth->logged_in())
		{
			redirect('_system_/auth/login', 'refresh');
		}
		switch ($show) {
			case 'asset':
				$this->view('_system_/asset_start.php', $vars);
				$this->view($view_name, $vars);
				$this->view('_system_/asset_stop.php', $vars);
				break;
			case 'none':
			case 'body':
				$this->view($view_name, $vars);
				break;
			case 'json':
				header('Content-Type: application/json;charset=utf-8');
				$this->view($view_name, $vars);
				break;
			case 'header':
				$this->view('_system_/header', $vars);
				break;
			case 'footer':
				$this->view('_system_/footer', $vars);
				break;
				case 'all':
				$this->view('_system_/header', $vars);
				$this->view($view_name, $vars);
				$this->view('_system_/footer', $vars);
				break;
		}
	}
}
