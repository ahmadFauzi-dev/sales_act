<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class flow_controller extends KOJE_Controller {

	var $modelClassName;
	var $view;
	var $params;
	var $modelClass;
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model('_setting_/flow_model');
		$this->view  = '_setting_/flow_view';
		$this->view_detail  = '_setting_/flow_detail_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['FORMS'] 		= $this->flow_model->getForm('form_search',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	function insert($params=false)
	{
		$this->params['MODEL']      = "form_insert";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']				= array();

		$this->params['FORMS'] = $this->flow_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->flow_model->do_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function edit($params=false)
	{
		$this->params['MODEL']      = "form_edit";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']     	= $this->CI->adodb->GetRow("select * from r_flow where r_flow_id=?", array($this->input->get_post('r_flow_id')));
		$this->params['FORMS'] = $this->flow_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->flow_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function detail_insert($params=false)
	{
		$this->params['MODEL']      = "form_detail_insert";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']				= array();
		$this->params['DATA_PARENT']    = $this->CI->adodb->GetRow("select * from v_flow where r_flow_id=?", array($this->input->get_post('parent_id')));
		
		$this->params['parent_id'] 		=	$this->input->get_post('parent_id');
		$this->params['FORMS'] = $this->flow_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "_setting_/flow_controller/detail",
																							'params' => array("r_flow_id"=>$this->input->get_post('parent_id'))
																						);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->flow_model->do_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view_detail, $this->params);
	}
	function detail_edit($params=false)
	{
		$this->params['MODEL']      = "form_detail_edit";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']     	= $this->CI->adodb->GetRow("select * from r_flow where r_flow_id=?", array($this->input->get_post('r_flow_id')));		

		$this->params['parent_id'] 		=	$this->params['DATA']['parent_id'];
		$this->params['FORMS'] = $this->flow_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "_setting_/flow_controller/detail",
																							'params' => array("r_flow_id"=>$this->params['parent_id'])
																						);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->flow_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view_detail, $this->params);
	}
	
	function delete($params=false)
	{
		$this->params['MODEL']      = "form_delete";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']     	= $this->CI->adodb->GetRow("select * from r_flow where r_flow_id=?", array($this->input->get_post('r_flow_id')));
		$this->params['FORMS'] = $this->flow_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->flow_model->do_delete($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function detail($params=false)
	{
		// $this->params['MODEL']      = "form_survey";
		$this->params['VIEW']       = "index";
		// $this->params['TITLE']		= 'detail_flow';
		$this->params['r_flow_id']     	= $this->input->get_post('r_flow_id');
		$this->params['DATA_PARENT']     	= $this->CI->adodb->GetRow("select * from v_flow where r_flow_id=?", array($this->input->get_post('r_flow_id')));

		$this->params['FORMS'] 		= $this->flow_model->getForm('form_search_detail',$this->params);

		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view_detail, $this->params);
	}
	
}
