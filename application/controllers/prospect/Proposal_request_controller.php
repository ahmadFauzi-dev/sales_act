<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class proposal_request_controller extends KOJE_Controller {

	var $modelClassName;
	var $view;
	var $params;
	var $modelClass;
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->params = array();
		$this->load->model('prospect/prospect_model');
		$this->view  = 'prospect/proposal_request_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['MODEL']    = "form_search_stage";
		$this->params['FORMS'] 		= $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}

	function edit($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['MODEL']      = "form_prospect";
		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function cancel($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['MODEL']      = "form_prospect";
		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$this->params, true);
		$form1 											= $this->prospect_model->getForm('form_cancel',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form1,$this->params['FORMS']);
		$this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');

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

	function next($params=false)
	{
		$this->params['VIEW']       = "view_general";
		$this->params['MODEL']      = "form_prospect";
		
		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$params);
		$form2 											= $this->prospect_model->getForm('form_next',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form2,$this->params['FORMS']);

		$this->params['DATA']				= $this->app_lib->getDataProspectCurrent($this->input->get_post('t_prospect_id'));
		$rowData1										= $this->app_lib->getDataProspectNextStage($this->input->get_post('t_prospect_id'));
		if($rowData1) {
			$this->params['DATA']['stage_name']			= $rowData1['stage_name'];
		}

		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$attr['start_date']						= $this->params['DATA']['stage_start_date'];
		$attr['target_closed_date']		= $this->params['DATA']['stage_start_date'];
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $attr, 'data-date-start-date');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_edit($this->params);
			$this->params['RESULT'] 	= $this->prospect_model->do_next($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function detail()
	{
		$this->params['MODEL']    = "form_search";
		$this->params['FORMS'] 		= $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}

	function proposal_insert($params=false)
	{
		$this->params['MODEL']      = "form_proposal";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'New Proposal';
		$this->params['FORMS'] 			= $this->proposal_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'proposal')
																						);
    $this->params['DATA']			  = $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->params['DATA']['title'] = $this->params['DATA']['prospect_name'];
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->proposal_model->do_proposal_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function proposal_edit($params=false)
	{
		$this->params['MODEL']      = "form_proposal";
		$this->params['VIEW']       = "view_general";
		$this->params['FORMS'] 			= $this->proposal_model->getForm($this->params['MODEL'],$params,false);
		$this->params['FORMS']['url_back'] = array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'proposal')
																						);
    $this->params['DATA']				= $this->app_lib->getDataProposal($this->input->get_post('t_proposal_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->proposal_model->do_proposal_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function presentation_insert($params=false)
	{
		$this->params['MODEL']      = "form_presentation";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'New Presentation';
		$this->params['FORMS'] 			= $this->proposal_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'presentation')
																						);
		$this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->params['DATA']['title'] = $this->params['DATA']['prospect_name'];
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->proposal_model->do_presentation_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function presentation_edit($params=false)
	{
		$this->params['MODEL']      = "form_presentation";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'Update Presentation';
		$this->params['FORMS'] 			= $this->proposal_model->getForm($this->params['MODEL'],$this->params);

		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'presentation')
																						);
		$this->params['DATA']				= $this->app_lib->getDataPresentation($this->input->get_post('t_presentation_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->proposal_model->do_presentation_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function survey_insert($params=false)
	{
		$this->params['MODEL']      = "form_survey";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']			= 'New Survey';
		$this->params['FORMS']			= $this->proposal_model->getForm($this->params['MODEL'],$this->params);
		$this->params['DATA']			  = $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->params['DATA']['title'] = $this->params['DATA']['prospect_name'];

		$service_lists = $this->CI->app_lib->getDataServiceGroupList();
		foreach ($service_lists as $key => $value) {
			$form 										= $this->proposal_model->getForm('form_sv_'.$key,$this->params);
			$this->params['FORMS'] 		= $this->proposal_model->mergeForm($this->params['FORMS'],$form);
		}
		$this->params['FORMS']['display'][]	= "_BUTTON_";
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'survey')
																						);
		$this->params['FORMS']['item']['status']['options'] = array(
							$this->CI->app_lib->getSurveyStatus('REQUEST')=>'Order To Provisioning',
							$this->CI->app_lib->getSurveyStatus('FINISHED')=>'Finished'
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
			$this->params['RESULT'] 	= $this->proposal_model->do_survey_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function survey_edit($params=false)
	{
		$this->params['MODEL']      = "form_survey";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']			= 'New Survey';
		$this->params['FORMS']			= $this->proposal_model->getForm($this->params['MODEL'],$this->params);

		$this->params['DATA']				= $this->app_lib->getDataSurvey($this->input->get_post('t_survey_id'));
		$service_lists = $this->CI->app_lib->getDataServiceGroupList();

		foreach ($service_lists as $key => $value) {
			$form 										= $this->proposal_model->getForm('form_sv_'.$key,$this->params);
			$enabled = $this->params['DATA'][$key]=='Y' ? true : false;
			$form['item'][$key]['options'] 					= array("Y"=>array($value[0],$enabled));
			$this->params['FORMS'] 		= $this->proposal_model->mergeForm($this->params['FORMS'],$form);
		}
		$this->params['FORMS']['display'][]	= "_BUTTON_";
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'survey')
																						);
		$this->params['FORMS']['item']['status']['options'] = array('SVST-01'=>'Order To Provisioning','SVST-03'=>'Finished','SVST-04'=>'Cancel');
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');

		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->proposal_model->do_survey_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function insertDoc($params=false)
	{ 
		$id = $this->CI->input->get_post('t_prospect_id');
		$this->params['MODEL']      = "form_manage_doc";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'Upload Document';
		$this->params['BTN_ADD']	= true;
    	$this->params['DATA']		= array();

    	$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/next",
											'params' => array('t_prospect_id' => $id)
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
			$this->params['RESULT'] 	= $this->prospect_model->do_insert_doc($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	  }
	  
  
  function editDoc($params=false)
	{ 
		$id = $this->CI->input->get_post('t_prospect_id');
		$psid = $this->CI->input->get_post('t_prospect_doc_id');
	  	$this->params['MODEL']      = "form_manage_doc";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'Edit Document';
		$this->params['BTN_ADD']		= true;
    	$this->params['DATA']				= array();

    	$row = $this->CI->adodb->GetRow("select * from v_prospect_doc where t_prospect_doc_id=?",array($psid));
   	 	$this->params['DATA'] = array_merge($this->params['DATA'],$row);

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/next",
												'params' => array('t_prospect_id' => $id)
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
			$this->params['RESULT'] 	= $this->prospect_model->do_edit_doc($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
  }

  function deleteDoc($params=false)
	{ 
		$id = $this->CI->input->get_post('t_prospect_id');
		$psid = $this->CI->input->get_post('t_prospect_doc_id');
		$this->params['MODEL']      = "form_delete_doc";
		$this->params['VIEW']       = "view_general";
		$this->params['TITLE']      = 'Delete Document';	
    	$this->params['READONLY']	= true;
		$this->params['BTN_ADD']	= true;
    	$this->params['DATA']		= array(); 

    	$row = $this->CI->adodb->GetRow("select * from v_prospect_doc where t_prospect_doc_id=?",array($psid));
    	$this->params['DATA'] = array_merge($this->params['DATA'],$row);

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/proposal_controller/next",
												'params' => array('t_prospect_id' => $id)
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
			$this->params['RESULT'] 	= $this->prospect_model->do_delete_doc($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
  }
}
