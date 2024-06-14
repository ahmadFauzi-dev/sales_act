<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class request_survey_controller extends KOJE_Controller {

	var $modelClassFile	= 'prospect/proposal_model';
	var $modelClassName	= 'proposal_model';
	var $view = 'monitoring/request_survey_view';
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
		$this->formName='form_search_survey';
		$this->params['FORMS'] = $this->modelClass->getForm($this->formName,$this->params);
		$this->params['RESULT'] = false;
		$this->params['SUBMIT'] = false;
		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function process($params=false)
	{
		$this->params['MODEL'] = "form_survey";
		$this->params['VIEW'] = "view_general";
		$this->params['TITLE'] = 'Process Survey';
		$this->params['FORMS'] = $this->proposal_model->getForm($this->params['MODEL'],$this->params);
		$this->params['DATA'] = $this->app_lib->getDataSurvey($this->input->get_post('t_survey_id'));

		$service_lists = $this->CI->app_lib->getDataServiceGroupList();
		foreach ($service_lists as $key => $value) {
			$enabled = $this->params['DATA'][$key]=='Y' ? true : false;
			if($enabled)
			{
				$form = $this->proposal_model->getForm('form_sv_'.$key,$this->params);
				$form['item'][$key]['options'] = array("Y"=>array($value[0],$enabled));
				$this->params['FORMS'] = $this->proposal_model->mergeForm($this->params['FORMS'],$form);
			}
		}
		$this->params['FORMS']['item']['status']['options'] = array(
							$this->CI->app_lib->getSurveyStatus('FINISHED')=>'Finished',
							$this->CI->app_lib->getSurveyStatus('ONPROGRESS')=>'On Progress',
							$this->CI->app_lib->getSurveyStatus('PENDING')=>'Pending'
						);
		$this->params['FORMS']['item']['title']['readonly'] = true;
		$this->params['FORMS']['item']['activity_date']['type'] = 'text';
		$this->params['FORMS']['item']['activity_date']['readonly'] = true;
		$this->params['FORMS']['item']['target_date']['type'] = 'text';
		$this->params['FORMS']['item']['target_date']['readonly'] = true;
		$this->params['FORMS']['item']['type']['readonly'] = true;
		$this->params['FORMS']['display'][]	= "_BUTTON_";
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT'] = false;
			$this->params['SUBMIT'] = false;
		}
		else
		{
			$this->params['RESULT'] = $this->proposal_model->do_survey_edit($this->params);
			$this->params['SUBMIT'] = true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
}
