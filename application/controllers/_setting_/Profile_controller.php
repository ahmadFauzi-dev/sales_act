<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class profile_controller extends KOJE_Controller {
	var $modelClassFile	= '_setting_/profile_model';
	var $modelClassName	= 'profile_model';
	var $view  					= '_setting_/profile_view';

	var $params;
	var $modelClass;

	function __construct() {
		parent::__construct();
		$CI =& get_instance();
		$this->params = array();
		$this->load->model($this->modelClassFile);
		$cn = $this->modelClassName;
		$this->modelClass =& $CI->$cn;
		$this->params['INPUT'] = $CI->input->post();
	}
	function index() {
		$id = $this->koje_system->getLoginID();
		$url = "home";
		redirect("_system_/auth/edit_user/$id/$url", $this->data);
	}
	function pict($params=array())
	{
		$this->params['MODEL']      = "form_pict"; 
		$this->params['TITLE']			= 'Upload Picture';
		$this->params['FORMS'] 			= $this->modelClass->getForm($this->params['MODEL'],$params,false);
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->modelClass->do_pict($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
}
