<?php
class KOJE_Controller extends CI_Controller{
	function __construct() {
		parent::__construct();
		global $config;
		$this->koje_config = $config;

		$folder 		= $this->uri->slash_segment(1);
		$controller = $this->uri->segment(2);
		$cm = $folder.$controller;
		$ar = $this->session->userdata('ACCESS_RIGHT');
		if(!is_array($ar)) $ar = array();
		if($folder!='_system_/' && $cm!='welcome/' && !in_array($cm,$ar))
		{
			redirect('welcome');
		}
	}
}
