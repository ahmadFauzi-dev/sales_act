<?php

/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class leads_model extends KOJE_Model
{
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->LeadsInsert($input);
    return $result;
  }
  function do_edit($params=false) {
		$input  = $this->getInput($params);
		$result = $this->CI->app_db->LeadsEdit($input);
		return $result;
  }
  function do_level_up($params=false) {
		$input  = $this->getInput($params);
		$result = $this->CI->app_db->LeadsLevelUp($input);
		return $result;
  }

	/* ------------------------ form --------------------------------*/
	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search Leads',
				'item'	=>
				array(
					'company_name' => array(
					  "type"  => "text",
					  "rules" => "trim"
					),
					'cp_1_name' => array(
					  "type"  => "text",
					  "rules" => "trim"
					),
					'industry_desc' => array(
					  "type"  => "select",
					  "rules" => "trim",
					  "options" => $this->CI->koje_system->getRefDomainDescList('CUST__INDUSTRY')
					)
				),
				'display' => array(
            html_col3Start(), array('company_name'), html_colEnd(),
			html_col3Start(), array('cp_1_name'), html_colEnd(),
			html_col3Start(), array('industry_desc'), html_colEnd()
					),
        'result' => array()
		);
	}
  function form_leads($params=false)
  {
		return array(
					'title'	=> 'Form Leads',
					'item'	=> array(
											't_account_id'=> array('type' => 'hidden', "rules" => "trim"),
											'company_name' => array('type' => 'text', "rules" => "trim|required"),
											'company_phone'=> array('type' => 'text', "rules" => "trim"), 
											'industry'=> array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('CUST__INDUSTRY')),
                      'company_address_info' => array('type' => 'text', "rules" => "trim"),
											'region'  => array('label' => 'Region', 'type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->app_lib->getROList()),
											'cp_1_salute'  => array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE')),
											'cp_1_name'    => array('type' => 'text', "rules" => "trim|required"),
											'cp_1_mobile'  => array('type' => 'text', "rules" => "trim|required"),
											'cp_1_email' 	 => array('type' => 'text', "rules" => "trim|required"),
                      'source'       => array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SOURCE'),"rules" => "trim"),
											"description"  => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Account Info"),
  												array(
  														'company_name',
  														'company_phone',
														'company_address_info',
														'region',
														'industry'
                          ),
                        html_blockEnd(),
    									html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Contact Info"),
                          array(
  															'cp_1_salute',
  															'cp_1_name',
  															'cp_1_mobile',
  															'cp_1_email',
                                'source'
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
			'result' => array( "company_name", "cp_1_name", "cp_1_mobile","company_address_info"),
			);
	}
	
	function form_level_up($params=false)
  {
		return array(
					'title'	=> 'Form Level Up To Account',
					'item'	=> array(
											't_account_id'	=> array('type' => 'hidden', "rules" => "trim"),
											'company_name' => array('type' => 'text', "rules" => "trim|required"),
											'company_phone'=> array('type' => 'text', "rules" => "trim"),'company_npwp'=> array('type' => 'text', "rules" => "trim"), 
											'company_anniversary'=> array('type' => 'datePicker', 'rules' => 'trim|required', 'format' => 'date'),
											'address_area' => array('type' => 'text', "rules" => "trim"),
											'address_street' => array('type' => 'text', "rules" => "trim"),
											'address_city_id' => array('type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingCity(),"rules" => "trim|required"),
											'address_city' => array('type' => 'hidden', "rules" => "trim"),
											'address_zip' => array('type' => 'text', "rules" => "trim"),	'industry'	=> array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('CUST__INDUSTRY')),
											'company_address_info' => array('type' => 'text', "rules" => "trim"),
											'rating' => array('type' => 'text', "rules" => "trim"),
											'r_ro_id'  => array('label' => 'Region', 'type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->app_lib->getROList()),
											'cp_1_salute'  => array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE')),
											'cp_1_name' => array('type' => 'text', "rules" => "trim|required"),
											'cp_1_mobile' => array('type' => 'text', "rules" => "trim|required"),
											'cp_1_email' => array('type' => 'text', "rules" => "trim|required"),
											'cp_1_department' => array('type' => 'text', "rules" => "trim"),
											'cp_1_info' => array('type' => 'text', "rules" => "trim"),
											'cp_2_salute'  => array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE')),
											'cp_2_name' => array('type' => 'text', "rules" => "trim|required"),
											'cp_2_mobile' => array('type' => 'text', "rules" => "trim|required"),
											'cp_2_email' => array('type' => 'text', "rules" => "trim|required"),
											'cp_2_department' => array('type' => 'text', "rules" => "trim"),
											'cp_2_info' => array('type' => 'text', "rules" => "trim"),
                      'source'       => array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SOURCE'),"rules" => "trim"),
											"description"  => array('type' => 'textarea', "rules" => "trim"),
											"submit" => array("type" => "submit"),
											"reset" => array("type" => "reset"),
											"cancel" => array("type" => "cancel")
										),
			'display' => array(
											html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Account Info"),
  												array('company_name',
  														'company_phone',
  														'company_npwp',
  														'company_anniversary',
														'company_address_info',
														'address_area',
														'address_street',
														'address_city_id',
														'address_zip',
														'r_ro_id',
														'industry',
														'rating'
                          ),
                        html_blockEnd(),
    									html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Contact Info 1"),
                          array(
  															'cp_1_salute',
  															'cp_1_name',
  															'cp_1_mobile',
  															'cp_1_email',
  															'cp_1_department',
  															'cp_1_info',
															'source'
  											  ),
										   html_blockEnd(),
									   html_colEnd(),html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Contact Info 2"),
                          array(
  															'cp_2_salute',
  															'cp_2_name',
  															'cp_2_mobile',
  															'cp_2_email',
  															'cp_2_department',
  															'cp_2_info'
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
			'result' => array( "company_name", "cp_1_name", "cp_1_mobile","company_address_info"),
			);
	}
}
