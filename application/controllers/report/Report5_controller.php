<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class report5_controller extends KOJE_Controller {

	var $modelClassFile	= 'report/report_model';
	var $modelClassName	= 'report_model';
	var $view  					= 'report/report5_view';

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
	function index()
	{
		$this->params['FORMS'] = $this->modelClass->getForm('form_search',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
}
