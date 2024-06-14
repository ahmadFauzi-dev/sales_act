<?php

/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class prospect_model extends KOJE_Model
{
  function do_insert_doc($params = false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ProspectDocInsert($input);
    return $result;
  }
  function do_edit_doc($params = false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ProspectDocEdit($input);
    return $result;
  }
  function do_delete_doc($params = false)
  {
    $input  = $this->getInput($params);
	//var_dump($input);
    $result = $this->CI->app_db->ProspectDocDelete($input);
    return $result;
  }
  
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ProspectInsert($input);
    return $result;
  }
  function do_upload($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->canvassingUpload($input);
    return $result;
  }
	function do_edit_presales($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->ProspectEditPresales($input);
			return $result;
	}
	function do_next($params=false) {
		$input  = $this->getInput($params);
		$result = $this->CI->app_db->ProspectNext($input);
		return $result;
	}
	function do_cancel($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->ProspectCancel($input);
			return $result;
	}
  function do_done($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->prospectDone($input);
    return $result;
  }
  function do_telemarketing_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->telemarketingInsert($input);
    return $result;
  }
	function do_telemarketing_edit($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->telemarketingEdit($input);
			return $result;
  }
  function do_manage_customer($params=false) {
    $input  = $this->getInput($params); 
	//print_r($input);die;
    $result = $this->CI->app_db->AccountChangeProduct($input);
    return $result;
}

	/* ------------------------ form --------------------------------*/
	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search Prospect',
				'item'	=>
						array(
								'prospect_type_desc' => array(
										"type"	=> "select",
                    "options" => $this->CI->koje_system->getRefDomainDescList("PROSPECT__TYPE"),
										"rules" => "trim"
								),
                'stage_name' => array(
  										"type"	=> "select",
                      "options" => $this->CI->app_lib->getDataStageAssoc('name,name'),
  										"rules" => "trim"
  								),
						),
				'display' => array(
						html_col3Start(),array('prospect_type_desc'),html_colEnd(),
            html_col3Start(),array('stage_name'),html_colEnd(),
					),
        'result' => array()
		);
	}
  function form_search_stage($params=false)
	{
		return array(
				'title'	=> 'Search Prospect',
				'item'	=>
						array(
								'prospect_type_desc' => array(
										"type"	=> "select",
                    "options" => $this->CI->koje_system->getRefDomainDescList("PROSPECT__TYPE"),
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('prospect_type_desc'),html_colEnd(),
					),
        'result' => array()
		);
	}
	function form_canvassing($params=false)
	{
		return array(
					'title'	=> 'Form Manage Canvassing',
					'item'	=> array(
											't_prospect_id'=> array('type' => 'hidden', "rules" => "trim"),
											't_stage_id'   => array('type' => 'hidden', "rules" => "trim"),
											'company_npwp'   => array('type' => 'hidden', "rules" => "trim"),
											'industry'   => array('type' => 'hidden', "rules" => "trim"),
											'address_zip'   => array('type' => 'hidden', "rules" => "trim"),
											'address_city'   => array('type' => 'hidden', "rules" => "trim"),
											'address_city_id'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_1_department'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_1_info'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_name'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_mobile'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_email'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_department'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_salute'   => array('type' => 'hidden', "rules" => "trim"),
											'cp_2_info'   => array('type' => 'hidden', "rules" => "trim"),
											'current_stage_id'   => array('type' => 'hidden', "rules" => "trim"),
                      'prospect_start_date'  => array('type' => 'datePicker', "rules" => "trim|required"),
											'prospect_type'=> array('type' => 'select2', "rules" => "trim|required", "value"=>'PSTY-01', 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__TYPE', 1)),
											't_account_id' => array('type' => 'hidden', "rules" => "trim"),
											'company_name'=> array( 'type' => 'pickList', 
													  'label' => 'Accounts',
                                                      'readonly'	=> true,
                                                      'pickList' => 't_account_lov',
                                                      'pickListStyle' => 'Scrollbars=1,resizable=1,width=1100,height=550,top=100,left=100',
                                                      'pickListAssign' => array (
                                                        't_account_id'  => 't_account_id',
                                                        'company_name'  => 'company_name',
                                                        'company_phone' => 'company_phone',
                                                        'company_address_info' => 'company_address_info',
														'company_npwp'  => 'company_npwp',
														'industry'  => 'industry',
														'address_zip'  => 'address_zip',
														'address_city'  => 'address_city',
														'address_city_id'  => 'address_city_id',
                                                        'r_ro_id' => 'region',
                                                        'ro_name' => 'region_desc',
														'cp_1_name'  => 'cp_1_name',
														'cp_1_mobile'  => 'cp_1_mobile',
														'cp_1_email'  => 'cp_1_email',
														'cp_1_salute'  => 'cp_1_salute',
														'cp_1_salute_desc'  => 'cp_1_salute_desc',
														'cp_1_department'  => 'cp_1_department',
														'cp_1_info'  => 'cp_1_info',
														'cp_2_name'  => 'cp_2_name',
														'cp_2_mobile'  => 'cp_2_mobile',
														'cp_2_email'  => 'cp_2_email',
														'cp_2_salute'  => 'cp_2_salute',
														'cp_2_department'  => 'cp_2_department',
														'cp_2_info'  => 'cp_2_info',
														'source'  => 'source',
														'source_desc'  => 'source_desc',
                                                      ),
                                                    "rules" => "trim",
                                                ), 
											'company_phone'=> array('type' => 'text', "rules" => "trim","readonly" => true), 
											'ro_name' => array('label' => 'Region','type' => 'text', "rules" => "trim","readonly" => true), 
											'company_address_info' => array('type' => 'text', "rules" => "trim","readonly" => true),
											'r_ro_id'  => array('type' => 'hidden', "rules" => "trim"),
											'cp_1_salute'  =>  array('type' => 'hidden', "rules" => "trim"),
											'cp_1_salute_desc'  =>  array('label' => 'Salute','type' => 'text', "rules" => "trim","readonly" => true),
											'cp_1_name'    => array('type' => 'text', "rules" => "trim","readonly" => true),
											'cp_1_mobile'  => array('type' => 'text', "rules" => "trim","readonly" => true),
											'cp_1_email' 	 => array('type' => 'text', "rules" => "trim","readonly" => true),
											'source'       => array('type' => 'hidden', "rules" => "trim"),
											'source_desc'       => array('label' => 'Source', 'type' => 'text', "rules" => "trim","readonly" => true),
											"description"  => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Canvassing Info"),
  												array('prospect_type',
  															'company_name',
  															'company_phone',
														'company_address_info',
														'ro_name',
														'prospect_start_date',
														// 'company_npwp',
														// 'industry',
														// 'address_zip',
														// 'address_city',
														// 'address_city_id',
														// 'cp_1_department',
														// 'cp_1_info',
														// 'cp_2_name',
														// 'cp_2_mobile',
														// 'cp_2_email',
														// 'cp_2_salute',
														// 'cp_2_department',
														// 'cp_2_info'
                          ),
                        html_blockEnd(),
    									html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Contact Info"),
                          array(
  															'cp_1_salute_desc',
  															'cp_1_name',
  															'cp_1_mobile',
  															'cp_1_email',
															'source_desc'
  											  ),
										   html_blockEnd(),
									   html_colEnd(),
                     html_col1Start(),
                        array
                        (
                            'description'
                        ),
                    html_colEnd(),
									'_BUTTON_'
							),
			'result' => array( "company_name", "cp_1_name", "cp_1_mobile","prospect_start_date","prospect_type","company_address_info"),
			);
	}

  function form_canvassing_upload($params=false)
  {
    return array(
          'title'	=> 'Upload Data Canvassing',
          'item'	=> array(
                      'prospect_start_date'  => array('type' => 'datePicker', "rules" => "trim|required"),
                      'prospect_type'=> array('type' => 'select2', "rules" => "trim|required", "value"=>'PSTY-01', 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__TYPE', 1)),
                      "file"          => array("type"=>"file", "title"=>"File XLS", "template" => 'tpl_canvassing.xlsx'),
                      "submit" 			 => array("type" => "submit"),
                      "reset" 		 	 => array("type" => "reset"),
                      "cancel" 			 => array("type" => "cancel")
                    ),
      'display' => array(
                      html_col2Start(),
                        html_blockStart(),
                          html_blockHeader("Canvassing Info"),
                          array('prospect_type',
                                'prospect_start_date'
                          ),
                        html_blockEnd(),
                      html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
                          html_blockHeader("File Upload"),
                          array('file' ),
                        html_blockEnd(),
                      html_colEnd(),
                  '_BUTTON_'
              ),
      'result' => array("prospect_start_date","prospect_type"),
      );
  }
  function form_next($params=false)
  {
    $form = array(
                'title'	=> 'Next Stage',
                'item'	=> array(
                                'stage_name'=> array('type' => 'text','readonly' => true),
                                'stage_start_date'=> array('type' => 'text', "rules" => "trim", 'readonly' => true, 'label'=>'Prev Stage Date'),
                                'start_date'=> array('type' => 'datePicker', "rules" => "trim|required", 'label'=>'New Stage Date'),
                                "notes"  => array("type" => "textarea", "rules" => "trim"),
                            ),
                'display' => array(
                                    html_col1Start(),
                                        array('stage_name',
                                              'stage_start_date',
                                              'start_date',
                                              'notes',
                                      ),
                                  html_colEnd()
                            ),
                'result' => array("stage_name", "stage_start_date", 'start_date','notes'),
            );
      return $form;
  }
  function form_cancel($params=false)
  {
    $form = array(
                'title'	=> 'Cancel Prospect',
                'item'	=> array(
                                'cancel_reason'=> array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__CANCEL_REASON')),
                                "cancel_notes"  => array("type" => "textarea", "rules" => "trim|required"),
                            ),
                'display' => array(
                                    html_col1Start(),
                                      html_blockStart(),
                                        html_blockHeader("Cancel Info"),
                                        array('cancel_reason',
                                              'cancel_notes',
                                      ),
                                    html_blockEnd(),
                                html_colEnd()
                            ),
                'result' => array("cancel_reason", "cancel_notes"),
            );
      return $form;
  }

  function form_prospect($params=false)
  {
    $btnAdd = isset($params['BTN_ADD']) ? $params['BTN_ADD'] : false;
	//print_r('testing');die;
    return array(
					'title'	=> 'Manage Prospect',
					'item'	=> array(
											't_prospect_id' => array( 'type'	=> 'hidden', "rules" => "trim"),
											'current_stage_id'=> array('type' => 'hidden', "rules" => "trim"),
											'prospect_no' => array('type'	=> 'text',"readonly" => true),
											'prospect_start_date'  => array('type' => 'datePicker', "rules" => "trim"),
											'prospect_type'=> array('type' => 'select2', "rules" => "trim", "value"=>'PSTY-01', 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__TYPE')),
											'prospect_name' => array('type'	=> 'text',"rules" => "trim", "placeholder"=>"jika kosong, default akan diisi companny name"),
											'target_amount' => array('type'	=> 'text','format' => 'number',"rules" => "trim","value"=>"0"),
											'target_closed_date' => array('type'	=> 'datePicker',"rules" => "trim"),
											'company_name' => array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'company_phone' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'address_name' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											//'company_npwp' => array('type'	=> 'text',"rules" => "trim|required"),
											'company_npwp' => array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'company_anniversary' =>  array('readonly'	=> true, 'readonly'	=> true, 'type'	=> 'datePicker', "rules" => "trim"),
                      'company_address_info' =>  array('readonly'	=> true, 'type'	=> 'text', "rules" => "trim"),
											'd_segmentation' => array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingSegmentation(),"rules" => "trim"),
											'd_customer_group' => array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingGroup(),"rules" => "trim"),
                      'address_area' =>  array('type'	=> 'text',"rules" => "trim"),
											'address_street' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
                      'address_city_id' => array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingCity(),"rules" => "trim"),
											'address_zip' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'cp_1_salute' =>  array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE'),"rules" => "trim"),
											'cp_1_name' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'cp_1_department' => array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__DEPARTMENT'),"rules" => "trim"),
											'cp_1_mobile' => array('readonly'	=> true,'type'	=> 'text', "rules" => "trim" ),
											'cp_1_email' =>  array('readonly'	=> true,'type'	=> 'text', "rules" => "trim" ),
											'cp_1_info' =>  array('readonly'	=> true,'type'	=> 'textarea', "rules" => "trim" ),
											'cp_2_salute' =>  array('readonly'	=> true, 'type'	=> 'select2', 'options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE'), "rules" => "trim"),
											'cp_2_name' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'cp_2_department' =>  array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__DEPARTMENT'),"rules" => "trim"),
											'cp_2_mobile' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'cp_2_email' =>  array('readonly'	=> true, 'type'	=> 'text',"rules" => "trim"),
											'cp_2_info' =>  array('readonly'	=> true, 'type'	=> 'textarea',"rules" => "trim"),
											'source' =>   array('readonly'	=> true, 'type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SOURCE'),"rules" => "trim"),
											"description" =>  array("type"	=> "text","rules" => "trim"),
										  'company_curr_service' => array('readonly'	=> true,'type'	=> 'text',"rules" => "trim"),
										  'company_curr_provider' => array('readonly'	=> true,'type'	=> 'text',"rules" => "trim"),
											'company_curr_expense' => array('readonly'	=> true,'type'	=> 'text',"rules" => "trim"),
											'presales_desc' => array('type'	=> 'textarea',"rules" => "trim", "label"=> "Description"),
											'presales_status' => array("label"=> "Status",'type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('PRESALES__STATUS')),
											'r_ro_id'  => array('label' => 'Region', 'type' => 'select2', "rules" => "trim", 'options'	=> $this->CI->app_lib->getROList()),
											"submit" 	=>  array("type"	=> "submit"),
											"reset" 	=>  array("type" 	=> "reset"),
											"cancel" 	=>  array("type" 	=> "cancel")
									),
			'display' => array(
                        html_col2Start(),
                          html_blockStart(),
                            html_blockHeader("Company Profile"),
                            array('company_name',
                                  'company_npwp',
                                  'company_anniversary',
                                  'd_segmentation',
                                  'd_customer_group'
                            ),
                          html_blockEnd(),
                      html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
                          html_blockHeader("Company Address"),
                          array('address_street',
                                'address_city_id',
                                'address_zip',
                                'company_phone',
                                'company_address_info',
                          ),
                        html_blockEnd(),
                      html_colEnd(),
											html_col2Start(),
  											html_blockStart(),
													html_blockHeader("Prospect Info"),
													array('prospect_no',
																'prospect_name',
															'prospect_type',
															'r_ro_id',
															//'description'
													),
  											html_blockEnd(),
  									html_colEnd(),
										html_col2Start(),
											html_blockStart(),
                        html_blockHeader("Target"),
												array('prospect_start_date',
                              'target_amount',
															'target_closed_date',
                              'source'
												),
											html_blockEnd(),
									html_colEnd(),

									html_col2Start(),
										html_blockStart(),
											html_blockHeader("Contact Personal 1"),
											array('cp_1_salute',
														'cp_1_name',
														'cp_1_department',
														'cp_1_mobile',
														'cp_1_email',
														'cp_1_info',
											),
										html_blockEnd(),
									html_colEnd(),
									html_col2Start(),
										html_blockStart(),
												html_blockHeader("Contact Personal 2"),
												array('cp_2_salute',
															'cp_2_name',
															'cp_2_department',
															'cp_2_mobile',
															'cp_2_email',
															'cp_2_info'
												),
										html_blockEnd(),
									html_colEnd(),
									html_col1Start(),
										html_blockStart(),
											html_blockHeader("Current Services"),
											array('company_curr_service',
                            'company_curr_provider',
                            'company_curr_expense',
											),
										html_blockEnd(),
									html_colEnd(),
									html_col1Start(),
										html_blockStart(),
											html_blockHeader("Service Requirement"),
                      array($this->CI->app_lib->generatePreRequirement($_REQUEST,$btnAdd)),
										html_blockEnd(),
										//DOCUMENTS
										html_blockStart(),
											html_blockHeader(""),
                      array($this->CI->app_lib->getOportunityPreDoc($_REQUEST)),
										html_blockEnd(),
										html_colEnd(),
										html_col1Start(),
										html_col1Start(),
										html_blockStart(),
											html_blockHeader("Presales Verification"),
											array('presales_status',
													'presales_desc',
											),
										html_blockEnd(),
									html_colEnd(),
									"_BUTTON_"
							),
			'result' => array('prospect_name','company_name','presales_status','presales_desc')
			);
  }
  function form_search_telemarketing($params=false)
  {
    return array(
        'title'	=> 'Search Telemarketing',
        'item'	=>
            array(
                'type_desc' => array(
                    "type"	=> "select",
                    "options" => $this->CI->koje_system->getRefDomainDescList("TELEMARKETING__TYPE"),
                    "rules" => "trim"
                ),
            ),
        'display' => array(
            html_col3Start(),array('type_desc'),html_colEnd(),
          ),
    );
  }
	function form_telemarketing($params=false)
	{
		return array(
					'title'	=> 'Form Telemarketing',
					'item'	=> array(
											't_prospect_id'=> array('type' => 'hidden', "rules" => "trim"),
  										't_telemarketing_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'type'=> array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('TELEMARKETING__TYPE')),
                      'activity_date'  => array('type' => 'datePicker', "rules" => "trim|required"),
											'description'=> array('type' => 'textarea', "rules" => "trim"),
											'result'    => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
                        html_blockStart(),
  												html_blockHeader("Activity Info"),
  												array('type',
                                'activity_date',
                                'description',
  															'result',
  											  ),
										   html_blockEnd(),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array("type","activity_date", "description", "result"),
			);
  }
  
  function form_manage_customer($params=false)
  { 
    $form = array(
                'title'	=> 'New Order',
                'item'	=> array(
                                't_account_id'=> array('type' => 'hidden'),
                                't_account_product_id'=> array('type' => 'hidden'),
                                'prospect_type'=> array('type' => 'select', "rules" => "trim|required", 'options' => $this->CI->koje_system->getRefDomainList('PROSPECT__TYPE', 2)),
                                'account_no'=> array( 'type' => 'pickList', 
                                                      'readonly'	=> true,
                                                      'pickList' => 'account_lov',
                                                      'pickListStyle' => 'Scrollbars=1,resizable=1,width=1100,height=550,top=100,left=100',
                                                      'pickListAssign' => array (
                                                        'account_no'    => 'account_no',
                                                        't_account_id'  => 't_account_id',
                                                        'company_name'  => 'company_name',
                                                        'd_segmentation' => 'd_segmentation',
                                                        'd_customer_group' => 'd_customer_group'
                                                    ),
                                                    "rules" => "trim",
                                                ), 
                                'company_name' => array('type' => 'text', "rules" => "trim"), 
                                'd_segmentation'=> array('type' => 'hidden', "rules" => "trim"), 
                                'd_customer_group'=> array('type' => 'hidden', "rules" => "trim"), 
                                "description"=> array('type' => 'text', "rules" => "trim"), 
                                'product_name_order'=> array( 'type' => 'pickList', 
                                                              'readonly'	=> true, 
                                                              'pickList' => 'account_product_post_lov',
                                                              'pickListStyle' => 'Scrollbars=1,resizable=1,width=1100,height=550,top=100,left=100',
                                                              'pickListFilter' => array('t_account_id' => 't_account_id'),
                                                              'pickListAssign' => array (
                                                                  't_account_product_id' => 't_account_product_id',
                                                                  'product_name_order' => 'product_name',
																  'speed' => 'speed',
																  'd_uom' => 'd_uom',
																  'agreed_sla' => 'agreed_sla',
																  'contract_length' => 'contract_length',
																  'speed' => 'speed',
																  'd_trial_status' => 'd_trial_status',
																  'delivery_days' => 'delivery_days',
                                                              ),"rules" => "trim"),  

                                'contract_length'       => array('type' => 'text', 'rules' => 'trim|required'),
                                'agreed_sla'            => array('type' => 'text', 'rules' => 'trim|required'),
                                'speed'                 => array('type' => 'text', 'rules' => 'trim|required'),
                                'd_uom'                 => array('type' => 'select', 'rules' => 'trim|required','options' => $this->CI->adodb_billing->getAssoc("select val, description from ref_domain where group_name='PRODUCT__UOM'")),
                                'd_trial_status'        => array('type' => 'select', 'rules' => 'trim|required','options' => $this->CI->adodb_billing->getAssoc("select val, description from ref_domain where group_name='TRIAL__STATUS'")),
                                'trial_days'            => array('type' => 'text', 'value'=>'0', 'rules' => 'trim|required|numeric'),
                                'delivery_days'         => array('type' => 'text', 'rules' => 'trim|required|numeric'),
                                'submit' 			            => array("type" => 'submit'),
                                'reset' 		 	            => array("type" => 'reset'),
                                'cancel' 			            => array("type" => 'cancel')        
                            ),
                'display' => array(
                                html_col2Start(),
                                    array(
                                      'prospect_type', 
                                      'account_no',  
                                      'company_name',  
                                      'product_name_order',  
                                      'speed',
                                      'd_uom', 
                                    ),
                                html_colEnd(),
                                html_col2Start(),
                                    array( 
                                      'contract_length',
                                      'agreed_sla',
                                      'd_trial_status',
                                      'trial_days',
                                      'delivery_days', 
                                      'description'
                                    ),
                                html_colEnd(),
                                  "_BUTTON_"
                            ),
                'result' => array("account_no", "company_name", 'prospect_type'),
            );
      return $form;
  } 
  function form_manage_doc($params = false)
  {
    $form = array(
      'title'  => 'Upload Documents',
      'item'  => array(
        't_prospect_doc_id' => array('type' => 'hidden'),
        't_prospect_id'          => array('type' => 'hidden'),
        't_prospect_product_id'  => array('type' => 'hidden'),
        'doc_no'                => array('type' => 'text', 'rules'  => 'trim|required'),
        'description'           => array('type' => 'text', 'rules'  => 'trim'),
        't_file_id'             => array('type' => 'file', 'rules'  => 'trim'),
        'doc_type'              => array('type' => 'select', 'rules' => 'trim|required', 'options' => $this->CI->app_lib->getReferenceDoc('DOCUMENT__TYPE','PRESALES')),
        'status'                => array('type' => 'select', 'rules' => 'trim|required', 'options' => $this->CI->app_lib->getReferenceList('DOCUMENT__STATUS')),
        'doc_date'              => array('type' => 'datePicker', 'rules'  => 'trim|required'),

        'submit'               => array("type" => 'submit'),
        'reset'                => array("type" => 'reset'),
        'cancel'               => array("type" => 'cancel'),
      ),
      'display' => array(
        html_col2Start(),
        array('description', 'doc_type', 'doc_no'),
        html_colEnd(),
        html_col2Start(),
        array('t_file_id', 'status', 'doc_date'),
        html_colEnd(),
        '_BUTTON_'
      ),
      'result'  => array('description', 'doc_type', 'doc_no', 'doc_date'),
    );
    return $form;
  }
  function form_delete_doc($params = false)
  {
    $form = array(
      'title'  => 'Upload Documents',
      'item'  => array(
        't_prospect_doc_id' => array('type' => 'hidden'),
        't_prospect_id'          => array('type' => 'hidden'),
        't_prospect_product_id'  => array('type' => 'hidden'),
        'doc_no'                => array('type' => 'text', 'readonly'	=> true, 'rules'  => 'trim'),
        'description'           => array('type' => 'text', 'readonly'	=> true, 'rules'  => 'trim'),
        't_file_id'             => array('type' => 'hidden'),
        'doc_type'              => array('disabled'	=> true, 'type' => 'select', 'rules' => 'trim', 'options' => $this->CI->app_lib->getReferenceList('DOCUMENT__TYPE')),
        'status'                => array('disabled'	=> true,'type' => 'select', 'rules' => 'trim', 'options' => $this->CI->app_lib->getReferenceList('DOCUMENT__STATUS')),
        'doc_date'              => array('disabled'	=> true, 'type' => 'datePicker', 'rules'  => 'trim'),

        'submit'               => array("type" => 'submit'),
        'reset'                => array("type" => 'reset'),
        'cancel'               => array("type" => 'cancel'),
      ),
      'display' => array(
        html_col2Start(),
        array('description', 'doc_type', 'doc_no'),
        html_colEnd(),
        html_col2Start(),
        array('status', 'doc_date'),
        html_colEnd(),
        '_BUTTON_'
      ),
      'result'  => array('description', 'doc_type', 'doc_no', 'doc_date'),
    );
    return $form;
  }
}
