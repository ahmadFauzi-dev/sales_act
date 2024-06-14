<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class opportunity_controller extends KOJE_Controller {

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
		$this->view  = 'prospect/opportunity_view';
		$this->params['INPUT'] = $this->CI->input->post();
	}
	function index()
	{
		$this->params['FORMS'] 		= $this->prospect_model->getForm('form_search_stage',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
	}
	function cancel($params=false)
	{
		$form1 											= $this->prospect_model->getForm('form_prospect',$params, true);
		$form2 											= $this->prospect_model->getForm('form_cancel',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form2,$form1);
		$this->params['VIEW']       = "view_general";
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
		
		$form1 											= $this->prospect_model->getForm('form_prospect',$params);
		$form2 											= $this->prospect_model->getForm('form_next',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form2,$form1);
		$this->params['VIEW']       = "view_general";
		
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
	function canvassing_insert($params=false)
	{
		$this->params['MODEL']      = "form_canvassing";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']		= array();

		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$this->params);
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
	function canvassing_upload($params=false)
	{
		$this->params['MODEL']      = "form_canvassing_upload";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']				= array();

		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_upload($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function canvassing_edit($params=false)
	{
		$this->params['MODEL'] = 'form_canvassing';
		//$this->params['VIEW']  = "view_general";
		$this->params['DATA']	 = $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
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
	function telemarketing_insert($params=false)
	{
		$this->params['MODEL']      = "form_telemarketing";
		$this->params['VIEW']       = "view_general";
    $this->params['DATA']				= array();

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/opportunity_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'telemarketing')
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
			$this->params['RESULT'] 	= $this->prospect_model->do_telemarketing_insert($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function telemarketing_edit($params=false)
	{
		$this->params['MODEL']      = "form_telemarketing";
		$this->params['VIEW']       = "view_general";
    $this->params['DATA']				= $this->app_lib->getDataTelemarketing($this->input->get_post('t_telemarketing_id'));

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/opportunity_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'telemarketing')
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
			$this->params['RESULT'] 	= $this->prospect_model->do_telemarketing_edit($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function profiling_insert($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']       = "view_general";
    $this->params['DATA']				= array();
		$this->params['BTN_ADD']		= true;

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
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
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = $this->CI->session->userdata('t_prospect_id');
					redirect("prospect/opportunity_controller/profiling_edit?t_prospect_id=$t_prospect_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function profiling_edit($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']		= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->params['BTN_ADD']		= true;

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);

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
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = $this->CI->session->userdata('t_prospect_id');
					redirect("prospect/opportunity_controller/profiling_edit?t_prospect_id=$t_prospect_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	
	function manage($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']       = "view_general";
    $this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$this->params['BTN_ADD']		= true;

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);

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
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = $this->CI->session->userdata('t_prospect_id');
					redirect("prospect/opportunity_controller/profiling_edit?t_prospect_id=$t_prospect_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function detail()
	{
		$this->params['FORMS'] 		= $this->prospect_model->getForm('form_search',$this->params);
		$this->params['RESULT']		= false;
		$this->params['SUBMIT'] 	= false;
		$this->params['VARS'] 		= $this->params;
		$this->load->template($this->view, $this->params);
  }
  
	function manage_customer($params=false)
	{
		$this->params['MODEL']      = "form_manage_customer";
		$this->params['VIEW']       = "view_general";
		$this->params['DATA']				= array();
		$this->params['BTN_ADD']		= true;

		$this->params['FORMS'] = $this->prospect_model->getForm($this->params['MODEL'],$this->params);
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->prospect_model->do_manage_customer($this->params);
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/opportunity_controller/next",
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/opportunity_controller/manage",
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/opportunity_controller/manage",
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
