<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class accounts_controller extends KOJE_Controller {

	var $modelClassName;
	var $view;
	var $params;
	var $modelClass;
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model('customer/accounts_model');
		$this->view  = 'customer/accounts_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['MODEL']      = "form_search";
		$this->params['FORMS'] 		= $this->accounts_model->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function detail()
	{
		//$this->params['FORMS'] 		= $this->accounts_model->getForm('form_search',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function edit($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'Edit accounts';
		$this->params['MODEL']      = "form_account_edit";
		$this->params['FORMS'] 			= $this->accounts_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['DATA'] = $this->app_lib->getDataAccount($this->input->get_post('t_account_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->accounts_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
}
