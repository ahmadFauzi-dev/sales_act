<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
  class negotiation_model extends KOJE_Model
  {
    function do_requirement_generate($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->RequirementGenerate($input);
      return $result;
    }
    function do_quotation_generate($params=false)
    {
      /*$input  = $this->getInput($params);
      $result = $this->CI->app_db->QuotationGenerate($input);
      return $result;*/
		$input  = $this->getInput($params);
		if ($this->CI->app_db->CalcItemChargeTotal($input)==0) {
		$result[1] = '99';
		$result[2] = 'Total Amount must not be zero';
		return $result;
      }
      $result = $this->CI->app_db->QuotationGenerate($input);
      return $result;
    }
    function do_quotation_request($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->QuotationRequest($input);
      return $result;
    }

    function do_quotation_cancel($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->QuotationCancel($input);
      return $result;
    }

    function do_quotation_approval($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->QuotationApproval($input);
      return $result;
    }
    function do_quotation_escalation($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->QuotationEscalation($input);
      return $result;
    }
    function do_negotiation_deal($params=false)
    {
      $input  = $this->getInput($params);
      $result = $this->CI->app_db->NegotiationDeal($input);
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
    function form_search_negotiation($params=false)
  	{
  		return array(
  				'title'	=> 'Search Negotiation',
  				'item'	=>
  						array(
  								'type_desc' => array(
  										"type"	=> "select",
                      "options" => $this->CI->koje_system->getRefDomainDescList("NEGOTIATION__TYPE"),
  										"rules" => "trim"
  								),
  						),
  				'display' => array(
  						html_col3Start(),array('type_desc'),html_colEnd(),
  					),
  		);
  	}
  	function form_negotiation($params=false)
  	{
  		return array(
  					'title'	=> 'Form Negotiation',
  					'item'	=> array(
                        't_negotiation_id'   	=> array('type' => 'hidden', "rules" => "trim"),
  											't_prospect_id'           => array('type' => 'hidden', "rules" => "trim"),
                        'current_stage_id'      => array('type' => 'hidden', "rules" => "trim"),
                        'activity_date'        => array('type' =>'datePicker', "rules" => "trim|required"),
                        'contact_name' 		        => array('type' => 'text', "rules" => "trim"),
                        'description'      				=> array('type' => 'textarea', "rules" => "trim"),
  											'submit' 			            => array('type' => 'submit'),
  											'reset' 		 	            => array('type' => 'reset'),
  											'cancel' 			            => array('type' => 'cancel')
  										),
  			'display' => array(
                        html_col1Start(),
                          html_blockStart(),
    												html_blockHeader('Negotiation Info'),
    												array('activity_date',
                                  'contact_name',
                                  'description',
                            ),
                          html_blockEnd(),
      									html_colEnd(),
                        html_col1Start(),
                          html_blockStart(),
    												html_blockHeader('Quotation List'),
    												array(
                                  $this->CI->app_lib->listQuotationNegotiation($_REQUEST),
                            ),
                          html_blockEnd(),
      									html_colEnd(),
    								'_BUTTON_'
  							),
  			'result' => array('activity_date', 'contact_name', 'description'),
  			);
  	}
    function form_search_requirement($params=false)
  	{
  		return array(
  				'title'	=> 'Search Requirement',
  				'item'	=>
  						array(
  								'priority_desc' => array(
  										"type"	=> "select",
                      "options" => $this->CI->koje_system->getRefDomainDescList("REQUIREMENT__PRIORITY"),
  										"rules" => "trim"
  								),
  						),
  				'display' => array(
  						html_col3Start(),array('priority_desc'),html_colEnd(),
  					),
  		);
  	}
  	function form_requirement($params=false)
  	{
      $btnAdd = isset($params['BTN_ADD']) ? $params['BTN_ADD'] : false;
  		return array(
  					'title'	=> 'Form Requirement',
  					'item'	=> array(
  											't_prospect_id'           => array('type' => 'hidden', "rules" => "trim"),
  											'submit' 			            => array('type' => 'submit'),
  											'reset' 		 	            => array('type' => 'reset'),
  											'cancel' 			            => array('type' => 'cancel')
  										),
  			'display' => array(
                    html_col1Start(),
                      html_blockStart(),
                        array($this->CI->app_lib->generateRequirement($_REQUEST,$btnAdd)),
                      html_blockEnd(),
                    html_colEnd(),
    								'_BUTTON_'
  							),
  			'result' => array(),
  			);
  	}
    function form_search_quotation($params=false)
  	{
  		return array(
  				'title'	=> 'Search Quotation',
  				'item'	=>
  				      array(
  						    'status_desc' => array(
                										"type"	=> "select",
                                    "options" => $this->CI->koje_system->getRefDomainDescList("QUOTATION__STATUS"),
                										"rules" => "trim"
        								),
  						),
  				'display' => array(
  						html_col3Start(),array('status_desc'),html_colEnd(),
  					),
  		);
  	}

    function form_search_quotation_item($params=false)
  	{
  		return array(
  				'title'	=> 'Search Quotation Item',
  				'item'	=>
  						array('status_desc' => array(
                                "type"	=> "select",
                                "options" => $this->CI->koje_system->getRefDomainDescList("QUOTATION__STATUS"),
                                "rules" => "trim"
                              ),
  						),
  				'display' => array(
  						html_col3Start(),array('status_desc'),html_colEnd(),
  					),
  		);
  	}
  	function form_quotation($params=false)
  	{
  		return array(
  					'title'	=> 'Form Quotation',
  					'item'	=> array(
                        't_quotation_id' => array('type' => 'hidden', "rules" => "trim"),
  											't_prospect_id'           => array('type' => 'hidden', "rules" => "trim"),
                        'current_stage_id'        => array('type' => 'hidden', "rules" => "trim"),
                        'quotation_no'            => array('type'	=> 'text','rules' => 'trim',"placeholder"=>"kosongkan jika Auto Generate"),
                        'name'                    => array('type'	=> 'text','rules' => 'trim|required'),
                        'expired_date'            => array('type'	=> 'datePicker','rules' => 'trim','value'	=> date('Y-m-d', strtotime('+1 month'))),
                        'status'                  => array('type'	=> 'select','rules' => 'trim', 'readonly'=>true, 'options' => $this->CI->koje_system->getRefDomainList('QUOTATION__STATUS')),
                        'customer_response'       => array('type'	=> 'textarea','rules' => 'trim'),
                        'submitted_date'          => array('type'	=> 'datePicker','rules' => 'trim'),
                        'submitted_notes'         => array('type'	=> 'textarea','rules' => 'trim'),
                        'description'             => array('type'	=> 'textarea','rules' => 'trim'),
  											'submit' 			            => array("type" => 'submit'),
  											'reset' 		 	            => array("type" => 'reset'),
  											'cancel' 			            => array("type" => 'cancel')
  										),
  			'display' => array(
                        html_col2Start(),
                          html_blockStart(),
                            html_blockHeader("Quotation Info"),
                            array('name',
                                  'quotation_no',
                                  'expired_date',
                                  'description',
                            ),
                          html_blockEnd(),
                        html_colEnd(),
                        html_col2Start(),
                          html_blockStart(),
                            html_blockHeader('Progress'),
                            array('status',
                                  'submitted_date',
                                  'submitted_notes',
                                  'customer_response',
                            ),
                          html_blockEnd(),
                        html_colEnd(),
                        html_col1Start(),
                          html_blockStart(),
                            html_blockHeader("Requirement Service List"),
                            array($this->CI->app_lib->listRequirement($_REQUEST)),
                          html_blockEnd(),
                        html_colEnd(),
                        html_col1Start(),
                          html_blockStart(),
                            html_blockHeader("Component Charge - Proposed"),
                            array($this->CI->app_lib->generateItemCharge($_REQUEST,false)),
                          html_blockEnd(),
                        html_colEnd(),
                        html_col1Start(),
                          html_blockStart(),
                            html_blockHeader("Component Charge - Alternative"),
                            array($this->CI->app_lib->generateItemChargeAlternative($_REQUEST,false)),
                          html_blockEnd(),
                        html_colEnd(),
                          "_BUTTON_"
                      ),
  			'result' => array('name', 'expired_date', 'description'),
  			);
  	}
    function form_quotation_request($params=false)
    {
	
			$r_ro_id = $params['r_ro_id'];
			$emp_names = array('parent_id'=> 0, 'r_ro_id' => $r_ro_id);
	  		$listROdb = $this->CI->db	->select('r_flow_id, discount')
									->where($emp_names)
								->get('r_flow');			

			$listRO = array();
			$listRO[] = "";
			foreach ($listROdb->result() as $key => $RO) {
				if(!empty($RO->r_flow_id))
				{
					$listRO[$RO->r_flow_id] = $RO->discount ;
				}
			}

			$discount = array(
				'name' => 'discount',
				'id' => 'discount',
				'type' => 'select',
				'class' => 'select2',
				'options' => $listRO,
				'value' => 'Name',
				'label' => 'Discount (in percent)',
			);
      
	  return array(
        'title'	=> 'Request Approval Quotation',
        'item'	=>
          array(
              't_quotation_id' => array('type'	=> 'hidden',"rules" => "trim|required"),
              'quotation_no' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
              'name' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
              'expired_date' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
              'description' => array("type"	=> "textarea","readonly" => true),
              'submitted_date'          => array('type'	=> 'datePicker','rules' => 'trim','readonly'=>true),
              'submitted_notes'         => array('type'	=> 'textarea','rules' => 'trim','readonly'=>true),
              'request_notes' => array("type"	=> "textarea","rules" => "trim|required"),
			  'discount' => $discount,
              "submit" 	=>  array("type"	=> "submit"),
              "reset" 	=>  array("type" 	=> "reset"),
              "cancel" 	=>  array("type" 	=> "cancel")
            ),
          'display' =>
            array(
              html_col2Start(),
                html_blockStart(),
                  html_blockHeader("Quotation Info"),
                  array('quotation_no',
                        'name',
                        'expired_date',
                  ),
                html_blockEnd(),
              html_colEnd(),
              html_col2Start(),
                html_blockStart(),
                  html_blockHeader('Progress'),
                  array( 'submitted_date',
                        'submitted_notes'
                  ),
                html_blockEnd(),
              html_colEnd(),
                html_col1Start(),
                  html_blockStart(),
                    $this->CI->app_lib->listQuotationItem($_REQUEST),
                    $this->CI->app_lib->listRequirement($_REQUEST),
                  html_blockEnd(),
                html_colEnd(),
                html_col1Start(),
                  html_blockStart(),
                    html_blockHeader("Discount"),
                      array('discount',
                      ),
                  html_blockEnd(),
                html_colEnd(),
				html_col1Start(),
                  html_blockStart(),
                    html_blockHeader("Request Approval Notes"),
                      array('request_notes',
                      ),
                  html_blockEnd(),
                html_colEnd(),
                "_BUTTON_"
          ),
          'result' =>
            array(
             "quotation_no","name","expired_date","description","request_notes"
            )
          );
    }
    function form_quotation_cancel($params=false)
    {
        return array(
          'title'	=> 'Cancel Quotation',
          'item'	=>
            array(
                't_quotation_id' => array('type'	=> 'hidden',"rules" => "trim|required"),
                'quotation_no' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'name' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'expired_date' => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'description' => array("type"	=> "textarea","readonly" => true),
                'submitted_date'          => array('type'	=> 'datePicker','rules' => 'trim','readonly'=>true),
                'submitted_notes'         => array('type'	=> 'textarea','rules' => 'trim','readonly'=>true),
                'cancel_reason' => array('type'	=> 'select','rules' => 'trim', 'options' => $this->CI->koje_system->getRefDomainList('QUOTATION__CANCEL_REASON')),
                'cancel_notes' => array("type"	=> "textarea","rules" => "trim|required"),
                "submit" 	=>  array("type"	=> "submit"),
                "reset" 	=>  array("type" 	=> "reset"),
                "cancel" 	=>  array("type" 	=> "cancel")
              ),
            'display' =>
              array(
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader("Quotation Info"),
                    array('quotation_no',
                          'name',
                          'expired_date'
                    ),
                  html_blockEnd(),
                html_colEnd(),
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader('Progress'),
                    array( 'submitted_date',
                          'submitted_notes'
                    ),
                  html_blockEnd(),
                html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      html_blockHeader("Quotation Item List"),
                      $this->CI->app_lib->listQuotationItem($_REQUEST),
                      $this->CI->app_lib->listRequirement($_REQUEST),
                    html_blockEnd(),
                  html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      html_blockHeader("Cancel Notes"),
                        array('cancel_reason','cancel_notes',
                        ),
                    html_blockEnd(),
                  html_colEnd(),
                  "_BUTTON_"
            ),
            'result' =>
              array(
               "quotation_no","name","cancel_reason","cancel_notes"
              )
            );
      }
      function form_quotation_approval($params=false)
      {
        return array(
          'title'	=> 'Approval Quotation',
          'item'	=>
            array(
                't_quotation_id'  => array('type'	=> 'hidden',"rules" => "trim|required"),
                'quotation_no'    => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'name'            => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'expired_date'    => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'description'     => array("type"	=> "textarea","readonly" => true),
                'submitted_date'  => array('type'	=> 'datePicker','rules' => 'trim','readonly'=>true),
                'submitted_notes' => array('type'	=> 'textarea','rules' => 'trim','readonly'=>true),
                'status'          => array("type"	=> "select2","options" => array('QTST-02' => 'Approved',
				// 'QTST-04'=>'Escalation',
				'QTST-05'=>'Rejected'),"rules" => "trim|required"),
                'approved_notes'  => array("type"	=> "textarea","rules" => "trim|required"),
                "submit" 	=>  array("type"	=> "submit"),
                "reset" 	=>  array("type" 	=> "reset"),
                "cancel" 	=>  array("type" 	=> "cancel")
              ),
            'display' =>
              array(
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader("Quotation Info"),
                    array('quotation_no',
                          'name',
                          'expired_date',
                    ),
                  html_blockEnd(),
                html_colEnd(),
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader('Progress'),
                    array( 'submitted_date',
                          'submitted_notes'
                    ),
                  html_blockEnd(),
                html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      $this->CI->app_lib->listQuotationItem($_REQUEST),
                      $this->CI->app_lib->listRequirement($_REQUEST),
                    html_blockEnd(),
                  html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      html_blockHeader("Approval"),
                        array('status',
                              'approved_notes',
                        ),
                    html_blockEnd(),
                  html_colEnd(),
                  "_BUTTON_"
            ),
            'result' =>
              array(
               "quotation_no","name","expired_date","description","approved_notes"
              )
            );
      }
      function form_quotation_escalation($params=false)
      {
        return array(
          'title'	=> 'Escalation Quotation',
          'item'	=>
            array(
                't_quotation_id'  => array('type'	=> 'hidden',"rules" => "trim|required"),
                'quotation_no'    => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'name'            => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'expired_date'    => array('type'	=> 'text',"readonly" => true,"rules" => "trim"),
                'description'     => array("type"	=> "textarea","readonly" => true),
                'submitted_date'  => array('type'	=> 'datePicker','rules' => 'trim','readonly'=>true),
                'submitted_notes' => array('type'	=> 'textarea','rules' => 'trim','readonly'=>true),
                'status'          => array("type"	=> "select2","options" => array('QTST-03' => 'Approved','QTST-05'=>'Rejected'),"rules" => "trim|required"),
                'approved_notes'  => array("type"	=> "textarea","rules" => "trim",'readonly'=>true),
                'escalation_notes'  => array("type"	=> "textarea","rules" => "trim|required"),
                "submit" 	=>  array("type"	=> "submit"),
                "reset" 	=>  array("type" 	=> "reset"),
                "cancel" 	=>  array("type" 	=> "cancel")
              ),
            'display' =>
              array(
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader("Quotation Info"),
                    array('quotation_no',
                          'name',
                          'expired_date',
                    ),
                  html_blockEnd(),
                html_colEnd(),
                html_col2Start(),
                  html_blockStart(),
                    html_blockHeader('Progress'),
                    array('submitted_date',
                          'submitted_notes',
                          'approved_notes',
                    ),
                  html_blockEnd(),
                html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      $this->CI->app_lib->listQuotationItem($_REQUEST),
                      $this->CI->app_lib->listRequirement($_REQUEST),
                    html_blockEnd(),
                  html_colEnd(),
                  html_col1Start(),
                    html_blockStart(),
                      html_blockHeader("Approval"),
                        array('status',
                              'escalation_notes',
                        ),
                    html_blockEnd(),
                  html_colEnd(),
                  "_BUTTON_"
            ),
            'result' =>
              array(
               "quotation_no","name","expired_date","description","escalation_notes"
              )
            );
      }
  }
