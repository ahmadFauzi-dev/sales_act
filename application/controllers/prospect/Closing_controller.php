<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class closing_controller extends KOJE_Controller {

	var $modelClassFile	= 'prospect/prospect_model';
	var $modelClassName	= 'prospect_model';
	var $view  					= 'prospect/closing_view';

	var $params;
	var $modelClass;
	var $CI;

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
		$this->params['MODEL']    = "form_search_stage";
		$this->params['FORMS'] 		= $this->modelClass->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}

	function edit($params=false)
	{
		$this->params['FORMS'] 			= $this->modelClass->getForm('form_prospect',$params,false);
		$this->params['VIEW']       = 'view_general';

		$rowData										= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
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
	function cancel($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']  			= 'view_general';
		$form1 											= $this->prospect_model->getForm($this->params['MODEL'],$params, true);
		$form2 											= $this->prospect_model->getForm('form_cancel',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form2,$form1);

		$rowData										= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $rowData, 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_cancel($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function generate($params=false)
	{
		$this->CI->load->model('prospect/closing_model');
		$this->params['FORMS'] 			= $this->closing_model->getForm('form_generate_customer',$params);
		$this->params['VIEW']       = 'view_general';
		$rowData										= $this->app_lib->getDataProspectCurrent($this->input->get_post('t_prospect_id'));
		if($rowData)
		{
			$rowData['install_address_street']		=  $rowData['address_street'];
			$rowData['install_address_city_id']			=  $rowData['address_city_id'];
			$rowData['install_address_zip']				=  $rowData['address_zip'];
		}
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $rowData, 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->closing_model->do_generate($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function detail()
	{
		$this->params['MODEL']    = "form_search";
		$this->params['FORMS'] 		= $this->modelClass->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
}
