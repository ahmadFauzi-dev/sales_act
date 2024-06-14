<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
  class closing_model extends KOJE_Model
  {
    function do_generate($params=false)
    {
      $input  = $this->getInput($params);
	  //print_r($input);die;
      $result = $this->CI->app_db->GenerateBilling($input);
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
  						),
  				'display' => array(
  						html_col3Start(),array('prospect_type_desc'),html_colEnd(),
  					),
  		);
  	}

  	function form_generate_customer($params=false)
  	{
  		return array(
  					'title'	=> 'Generate Customer Billing',
  					'item'	=> array(
                              't_prospect_id' => array( 'type'	=> 'hidden', "rules" => "trim"),
                              'current_stage_id'=> array('type' => 'hidden', "rules" => "trim"),
							  'r_ro_id'=> array('type' => 'hidden', "rules" => "trim"),
							  'contract_length'=> array('type' => 'hidden', "rules" => "trim"),
							  'agreed_sla'=> array('type' => 'hidden', "rules" => "trim"),
							  'speed'=> array('type' => 'hidden', "rules" => "trim"),
							  'd_uom'=> array('type' => 'hidden', "rules" => "trim"),
							  'd_trial_status'=> array('type' => 'hidden', "rules" => "trim"),
							  'trial_days'=> array('type' => 'hidden', "rules" => "trim"),
							  'delivery_days'=> array('type' => 'hidden', "rules" => "trim"),
                              'company_name' => array('type'	=> 'text',"rules" => "trim|required", 'readonly'=>true),
                              'company_phone' =>  array('type'	=> 'text',"rules" => "trim"),
                              'company_npwp' => array('type'	=> 'text',"rules" => "trim", 'readonly'=>true),
                              'prospect_type' => array('type'	=> 'select2',"rules" => "trim", 'readonly'=>true, 'options'=>$this->CI->koje_system->getRefDomainList('PROSPECT__TYPE')),
                              'd_segmentation' => array('type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingSegmentation(),"rules" => "trim|required"),
                              'd_customer_group' => array('type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingGroup(),"rules" => "trim|required"),
                              'address_street' =>  array('type'	=> 'text',"rules" => "trim", 'readonly'=>true),
                              'address_city_id' => array('type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingCity(),"rules" => "trim", 'readonly'=>true),
                              'address_zip' =>  array('type'	=> 'text',"rules" => "trim", 'readonly'=>true),
                              'install_address_street' =>  array('type'	=> 'text',"rules" => "trim", 'readonly'=>true),
                              'install_address_city_id' => array('type'	=> 'select2','options'	=> $this->CI->app_lib->getBillingCity(),"rules" => "trim", 'readonly'=>true),
                              'install_address_zip' =>  array('type'	=> 'text',"rules" => "trim", 'readonly'=>true),
                              'cp_1_salute' =>  array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE'),"rules" => "trim|required"),
                              'cp_1_name' =>  array('type'	=> 'text',"rules" => "trim|required"),
                              'cp_1_department' => array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__DEPARTMENT'),"rules" => "trim"),
                              'cp_1_mobile' => array( 'type'	=> 'text', "rules" => "trim|required" ),
                              'cp_1_email' =>  array( 'type'	=> 'text', "rules" => "trim|required" ),
							  //add Cpname 2
							  'cp_2_salute' =>  array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__SALUTE'),"rules" => "trim|required"),
                              'cp_2_name' =>  array('type'	=> 'text',"rules" => "trim|required"),
                              'cp_2_department' => array('type'	=> 'select2','options'	=> $this->CI->koje_system->getRefDomainList('LEAD__DEPARTMENT'),"rules" => "trim"),
                              'cp_2_mobile' => array( 'type'	=> 'text', "rules" => "trim|required" ),
                              'cp_2_email' =>  array( 'type'	=> 'text', "rules" => "trim|required" ),
							  //end here
                              "description" =>  array("type"	=> "textarea","rules" => "trim"),
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
  															'company_phone',
  															'd_segmentation',
  															'd_customer_group',
  												),
  											html_blockEnd(),
  									html_colEnd(),
  									html_col2Start(),
  										html_blockStart(),
  											html_blockHeader("Contact Personal Billing"),
  											array('cp_1_salute',
  														'cp_1_name',
  														'cp_1_department',
  														'cp_1_mobile',
  														'cp_1_email'
  											),
  										html_blockEnd(),
									html_colEnd(),
  									html_col2Start(),
  										html_blockStart(),
  											html_blockHeader("Contact Personal Technic"),
  											array('cp_2_salute',
  														'cp_2_name',
  														'cp_2_department',
  														'cp_2_mobile',
  														'cp_2_email'
  											),
  										html_blockEnd(),	
  									html_colEnd(),
  									html_col2Start(),
  										html_blockStart(),
  											html_blockHeader("Billing Info"),
  											array('address_street',
  														'address_city_id',
  														'address_zip'
  											),
  										html_blockEnd(),
  									html_colEnd(),
  									html_col2Start(),
  										html_blockStart(),
  											html_blockHeader("Installation Info"),
  											array( 'install_address_street',
  														'install_address_city_id',
  														'install_address_zip'
  											),
  										html_blockEnd(),
  									html_colEnd(),
  									html_col1Start(),
  										html_blockStart(),
  											html_blockHeader("Quotation"),
                        array('prospect_type'),
                        $this->CI->app_lib->listQuotationItem($_REQUEST,'DEAL'),
  										html_blockEnd(),
  									html_colEnd(),
  									"_BUTTON_"
  							),
  			'result' => array('company_name','d_segmentation','d_customer_group','cp_1_name'),
  			);
  	}
  }
