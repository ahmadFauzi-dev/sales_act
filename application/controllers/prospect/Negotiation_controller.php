<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class negotiation_controller extends KOJE_Controller {

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
		$this->load->model('prospect/negotiation_model');
		$this->view  = 'prospect/negotiation_view';
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
	function edit($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']  			= 'view_general';
		$this->params['FORMS'] 			= $this->prospect_model->getForm($this->params['MODEL'],$params,false);
		$this->params['DATA']     	= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
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

	function next($params=false)
	{
		$this->params['MODEL']      = "form_prospect";
		$this->params['VIEW']  			= 'view_general';
		$form1 											= $this->prospect_model->getForm($this->params['MODEL'],$params);
		$form2 											= $this->prospect_model->getForm('form_next',$params);
		$this->params['FORMS'] 			= $this->prospect_model->mergeForm($form2,$form1);

		$deal = $this->CI->app_lib->getQuotationStatus('DEAL');
		$cntDeal	= $this->CI->adodb->getOne("select count(*) cnt from v_quotation where t_prospect_id=? and status=?", array($this->input->get_post('t_prospect_id'),$deal));

		if($cntDeal==0)
		{
			$this->params['FORMS']['display'][] = html_warning('no_quotation_deal');
			$this->params['FORMS']['item']['submit']['disabled'] = true;
		}

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

	function requirement_generate($params=false)
	{
		$this->params['MODEL']    = "form_requirement";
		$this->params['VIEW']  		= 'view_general';
		$this->params['TITLE']    = 'Customer Requirement';
		$this->params['BTN_ADD']	= true;
		$this->params['FORMS'] 		= $this->negotiation_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'requirement')
																						);
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
			$this->params['RESULT'] 	= $this->negotiation_model->do_requirement_generate($this->params);
			$this->params['SUBMIT'] 	= true;
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = $this->CI->session->userdata('t_prospect_id');
					redirect("prospect/negotiation_controller/requirement_generate?t_prospect_id=$t_prospect_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function quotation_insert($params=false)
	{
		$this->params['MODEL']      = "form_quotation";
		$this->params['VIEW']  			= 'view_general';
		$this->params['TITLE']      = 'New Quotation';
		$this->params['FORMS'] 			= $this->negotiation_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'quotation')
																						);
		$this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));
		$quotation_title						= $this->adodb->getOne("select name from t_quotation where t_prospect_id=?", array($this->input->get_post('t_prospect_id')));
		$this->params['DATA']['name'] = $quotation_title ? $quotation_title : $this->params['DATA']['prospect_name'];
		$this->params['DATA']['status'] = $this->app_lib->getQuotationStatus('PREPARATION');
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->negotiation_model->do_quotation_generate($this->params);
			$this->params['SUBMIT'] 	= true;
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = getArrayVal($this->params['INPUT'],'t_prospect_id');
					$t_quotation_id = $this->CI->session->userdata('t_quotation_id');
					redirect("prospect/negotiation_controller/quotation_edit?t_prospect_id=$t_prospect_id&t_quotation_id=$t_quotation_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function quotation_edit($params=false)
	{
		$this->params['MODEL']      = "form_quotation";
		$this->params['VIEW']  			= 'view_general';
		$this->params['TITLE']      = 'Update Quotation';
		$this->params['FORMS'] 			= $this->negotiation_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'quotation')
																						);
    $this->params['DATA']				= $this->app_lib->getDataQuotation($this->input->get_post('t_quotation_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->negotiation_model->do_quotation_generate($this->params);
			$this->params['SUBMIT'] 	= true;
			if(getArrayVal($this->params['INPUT'],'submitAddRow')==1)
			{
					$t_prospect_id = getArrayVal($this->params['INPUT'],'t_prospect_id');
					$t_quotation_id = $this->CI->session->userdata('t_quotation_id');
					redirect("prospect/negotiation_controller/quotation_edit?t_prospect_id=$t_prospect_id&t_quotation_id=$t_quotation_id");
			}
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}

	function quotation_request($params=false)
	{
		
		$this->params['MODEL']      = "form_quotation_request";
		$this->params['VIEW']  			= 'view_general';
		$this->params['TITLE']      = 'Request Approval';
		$this->params['r_ro_id'] = $this->session->userdata('r_ro_id');
		$this->params['FORMS'] 			= $this->negotiation_model->getForm($this->params['MODEL'],$this->params,false);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'quotation')
																						);
		$this->params['DATA']				= $this->app_lib->getDataQuotation($this->input->get_post('t_quotation_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->negotiation_model->do_quotation_request($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function quotation_cancel($params=false)
	{
		$this->params['MODEL']      = "form_quotation_cancel";
		$this->params['VIEW']  			= 'view_general';
		$this->params['FORMS'] 			= $this->negotiation_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'quotation')
																						);
    $this->params['DATA']				= $this->app_lib->getDataQuotation($this->input->get_post('t_quotation_id'));
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->negotiation_model->do_quotation_cancel($this->params);
			$this->params['SUBMIT'] 	= true;
		}

		$this->params['VARS'] = $this->params;
		$this->load->template($this->view, $this->params);
	}
	function negotiation_deal($params=false)
	{
		$this->params['MODEL']      = "form_negotiation";
		$this->params['VIEW']  			= 'view_general';
		$this->params['TITLE']      = 'New Negotiation';
		$this->params['FORMS'] 			= $this->negotiation_model->getForm($this->params['MODEL'],$this->params);
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/detail",
																							'params' => array("t_prospect_id"=>$this->input->get_post('t_prospect_id'),'act'=>'deal')
																						);
		$this->params['DATA']				= $this->app_lib->getDataProspect($this->input->get_post('t_prospect_id'));

		$approved = $this->CI->app_lib->getQuotationStatus('APPROVED');
		$deal = $this->CI->app_lib->getQuotationStatus('DEAL');
		$cntQuotation	= $this->CI->adodb->getOne("select count(*) cnt from t_quotation where t_prospect_id=? and status in('$approved','$deal')", array($this->input->get_post('t_prospect_id')));
		if($cntQuotation==0)
		{
			$this->params['FORMS']['display'][] = html_warning('no_quotation');
			$this->params['FORMS']['item']['submit']['disabled'] = true;
		}
		$this->koje_system->array_set_element($this->params['FORMS']['item'], $this->params['DATA'], 'value');
		$this->koje_validation->set_rules($this->params['FORMS']['item']);
		if ($this->koje_validation->run() == FALSE)
		{
			$this->params['RESULT']		= false;
			$this->params['SUBMIT'] 	= false;
		}
		else
		{
			$this->params['RESULT'] 	= $this->negotiation_model->do_negotiation_deal($this->params);
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/next",
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/next",
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
		$this->params['FORMS']['url_back'] =array('url' => "prospect/negotiation_controller/next",
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
