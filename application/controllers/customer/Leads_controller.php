<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class leads_controller extends KOJE_Controller {

	var $modelClassName;
	var $view;
	var $params;
	var $modelClass;
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model('customer/leads_model');
		$this->view  = 'customer/leads_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['MODEL']      = "form_search";
		$this->params['FORMS'] 		= $this->leads_model->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function detail()
	{
		//$this->params['FORMS'] 		= $this->leads_model->getForm('form_search',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function leads_insert($params=false)
	{
		$this->params['MODEL']      = "form_leads";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'New Leads';
		$this->params['FORMS'] 			= $this->leads_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->leads_model->do_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function edit($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['MODEL']      = "form_leads";
		$this->params['FORMS'] 			= $this->leads_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['DATA'] = $this->app_lib->getDataLeads($this->input->get_post('t_account_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->leads_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function level_up($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['MODEL']      = "form_level_up";
		$this->params['FORMS']		= $this->leads_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['DATA'] 		= $this->app_lib->getDataLeads($this->input->get_post('t_account_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->leads_model->do_level_up($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
}
