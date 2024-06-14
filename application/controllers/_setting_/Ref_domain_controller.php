<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class ref_domain_controller extends KOJE_Controller {

	var $modelClassName;
	var $view;
	var $params;
	var $modelClass;
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model('_setting_/ref_domain_model');
		$this->view  = '_setting_/ref_domain_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['FORMS'] 		= $this->ref_domain_model->getForm('form_search',$this->params);
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

		$this->params['FORMS'] = $this->ref_domain_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function edit($params=false)
	{
		$this->params['MODEL']      = "form_edit";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']     	= $this->CI->adodb->GetRow("select * from t_reference where reference_id=?", array($this->input->get_post('reference_id')));
		$this->params['FORMS'] = $this->ref_domain_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->ref_domain_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
}
