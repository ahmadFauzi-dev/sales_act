<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class target_controller extends KOJE_Controller {

	var $modelClassFile	= 'management/target_model';
	var $modelClassName	= 'target_model';
	var $view  					= 'management/target_view';

	var $params;
	var $modelClass;
	var $formName;
	var $readOnly = false;

	function __construct() {
		parent::__construct();
		$CI =& get_instance();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model($this->modelClassFile);
		$cn = $this->modelClassName;
		$this->modelClass =& $CI->$cn;
		$this->params['INPUT'] = $CI->input->post();
	}
	function index()
	{
		$this->formName='form_search';
		$this->params['FORMS'] = $this->modelClass->getForm($this->formName,$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function insert($params=false)
	{
			$this->formName ='form_general';
			$this->params['sales_id'] = $this->koje_system->getLoginID();
			$this->params['FORMS'] = $this->modelClass->getForm($this->formName,$this->params,$this->readOnly);
			$this->params['VIEW']       = 'view_general';

			$this->koje_validation->set_rules($this->params['FORMS']['item']);
			if ($this->koje_validation->run() == FALSE)
			{
				$this->params['RESULT']		= false;
				$this->params['SUBMIT'] 	= false;
			}
			else
			{
				$this->params['RESULT'] 	= $this->modelClass->do_insert($this->params);
				$this->params['SUBMIT'] 	= true;
			}

			$this->params['VARS'] = $this->params;
			$this->load->template($this->view, $this->params);
	}
	function edit($params=false)
	{
			$this->formName ='form_general';
			$this->params['VIEW']       = 'view_general';

			$this->params['sales_id'] = $this->koje_system->getLoginID();
			$this->params['FORMS'] = $this->modelClass->getForm($this->formName,$this->params,$this->readOnly);

			$rowData = $this->app_lib->getDataTarget($this->input->get_post('t_target_id'));
			$this->koje_system->array_set_element($this->params['FORMS']['item'], $rowData, 'value');

			$this->koje_validation->set_rules($this->params['FORMS']['item']);
			if ($this->koje_validation->run() == FALSE)
			{
				$this->params['RESULT']		= false;
				$this->params['SUBMIT'] 	= false;
			}
			else
			{
				$this->params['RESULT'] 	= $this->modelClass->do_edit($this->params);
				$this->params['SUBMIT'] 	= true;
			}

			$this->params['VARS'] = $this->params;
			$this->load->template($this->view, $this->params);
	}
	function upload($params=false)
	{
			$this->formName ='form_upload';
			$this->params['VIEW']       = 'view_general';

			$this->params['FORMS'] = $this->modelClass->getForm($this->formName,$this->params,$this->readOnly);

			$this->koje_validation->set_rules($this->params['FORMS']['item']);
			if ($this->koje_validation->run() == FALSE)
			{
				$this->params['RESULT']		= false;
				$this->params['SUBMIT'] 	= false;
			}
			else
			{
				$this->params['RESULT'] 	= $this->modelClass->do_upload($this->params);
				$this->params['SUBMIT'] 	= true;
			}

			$this->params['VARS'] = $this->params;
			$this->load->template($this->view, $this->params);
	}
}
