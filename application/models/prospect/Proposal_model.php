<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class proposal_model extends KOJE_Model
{
  function do_survey_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->surveyInsert($input);
    return $result;
  }
	function do_survey_edit($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->surveyEdit($input);
			return $result;
	}

  function do_proposal_insert($params=false)
  {
    $input  = $this->getInput($params);
    $input['service_lists'] = json_encode($this->CI->koje_system->getArrayVal($input,'service_lists',array()));
    $result = $this->CI->app_db->ProposalInsert($input);
    return $result;
  }
	function do_proposal_edit($params=false) {
			$input  = $this->getInput($params);
      $input['service_lists'] = json_encode($this->CI->koje_system->getArrayVal($input,'service_lists',array()));
			$result = $this->CI->app_db->ProposalEdit($input);
			return $result;
	}
  function do_presentation_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->presentationInsert($input);
    return $result;
  }
	function do_presentation_edit($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->presentationEdit($input);
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
  function form_search_proposal($params=false)
	{
		return array(
				'title'	=> 'Search Proposal',
				'item'	=>
						array(
								'title' => array(
										"type"	=> "text",
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('title'),html_colEnd(),
					),
		);
	}

	function form_proposal($params=false)
	{
		return array(
					'title'	=> 'Form Proposal',
					'item'	=> array(
                      't_proposal_id'=> array('type' => 'hidden', "rules" => "trim"),
											't_prospect_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'current_step_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'activity_date'  => array('type' => 'datePicker', "rules" => "trim|required"),
                      'title'  => array('type' => 'text', "rules" => "trim|required"),
                      'description'  => array('type' => 'textarea', "rules" => "trim"),
                      'send_by'=> array('type' => 'select', "rules" => "trim", 'options'	=> $this->CI->koje_system->getRefDomainList('PROPOSAL__SEND_BY')),
											'technical_info'=> array('type' => 'textarea', "rules" => "trim"),
											'business_info'  => array('type' => 'textarea', "rules" => "trim"),
                      'response_info'  => array('type' => 'textarea', "rules" => "trim"),
											'attachment_1_id'  => array('type' => 'file', "rules" => "trim"),
											'attachment_1_desc' => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                      'attachment_2_id' => array('type' => 'file', "rules" => "trim"),
											'attachment_2_desc' => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                      'attachment_3_id' => array('type' => 'file', "rules" => "trim"),
											'attachment_3_desc' => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Proposal Info"),
  												array('title',
                                'description',
  															'send_by',
                                'activity_date',
                                'response_info',
                                'business_info',
                                'technical_info',
                          ),
                      html_blockEnd(),
                    html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
                        html_blockHeader("Document & Attachment"),
                        array(
                              'attachment_1_id',
                              'attachment_2_id',
                              'attachment_3_id',
                        ),
                        html_blockEnd(),
    									html_colEnd(),
  								'_BUTTON_'
							),
			'result' => array('title', "activity_date", "description"),
			);
	}

  function form_search_presentation($params=false)
	{
		return array(
				'title'	=> 'Search Presentation',
				'item'	=>
						array(
								'activity_date' => array(
										"type"	=> "datePicker",
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('activity_date'),html_colEnd(),
					),
		);
	}
  function form_presentation($params=false)
	{
		return array(
					'title'	=> 'Form Presentation',
					'item'	=> array(
                      't_presentation_id'=> array('type' => 'hidden', "rules" => "trim"),
											't_prospect_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'current_stage_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'activity_date'  => array('type' => 'datePicker', "rules" => "trim|required"),
                      'title'  => array('type' => 'text', "rules" => "trim|required"),
                      'description'  => array('type' => 'textarea', "rules" => "trim"),
                      'result_info'  => array('type' => 'textarea', "rules" => "trim"),
											'attachment_1_id'    => array('type' => 'file', "rules" => "trim"),
											'attachment_1_desc'    => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                      'attachment_2_id'    => array('type' => 'file', "rules" => "trim"),
											'attachment_2_desc'    => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                      'attachment_3_id'    => array('type' => 'file', "rules" => "trim"),
											'attachment_3_desc'    => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
											'submit' 			 => array("type" => 'submit'),
											'reset' 		 	 => array("type" => 'reset'),
											'cancel' 			 => array("type" => 'cancel')
										),
			'display' => array(
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader('Presentation Info'),
  												array('title',
                                'activity_date',
  															'description',
                                'result_info'
                          ),
                        html_blockEnd(),
    									html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
                        html_blockHeader("Document & Attachment"),
                        array(
                              'attachment_1_id',
                              'attachment_2_id',
                              'attachment_3_id',
                        ),
                        html_blockEnd(),
    									html_colEnd(),
  								'_BUTTON_'
							),
			'result' => array('title', "activity_date", 'description','result_info'),
			);
	}

  function form_search_survey($params=false)
	{
		return array(
				'title'	=> 'Search Survey',
				'item'	=>
						array(
								'activity_date' => array(
										"type"	=> "datePicker",
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('activity_date'),html_colEnd(),
					),
		);
	}
  function form_survey($params=false)
  	{
      return array(
              'title'	=> 'Form Survey',
              'item'	=> array(
  												't_survey_id'        => array('type'	=> 'hidden', "rules" => "trim" ),
                          't_prospect_id'      => array('type' => 'hidden', "rules" => "trim" ),
                          'current_stage_id'   => array('type' => 'hidden', "rules" => "trim" ),
  						  'title'              => array('type' => 'text',"rules" => "trim|required"),
                          'activity_date'      => array('type' => 'datePicker',"rules" => "trim|required"),
                          'target_date'        => array('type' => 'datePicker',"rules" => "trim"),
                          'description'        => array('type' => 'textarea',"rules" => "trim|required"),
                          'status'             => array('type' => 'select2',"rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('SURVEY__STATUS')),
                          'type'               => array('type' => 'select2',"rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('SURVEY__TYPE')),
                          'survey_date'        => array('type' => 'datePicker',"rules" => "trim"),
                          'survey_notes'       => array('type' => 'textarea',"rules" => "trim"),
    					  'attachment_1_id'    => array('type' => 'file', "rules" => "trim"),
    					  'attachment_1_desc'  => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                          'attachment_2_id'    => array('type' => 'file', "rules" => "trim"),
    					  'attachment_2_desc'  => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                          'attachment_3_id'    => array('type' => 'file', "rules" => "trim"),
    					  'attachment_3_desc'  => array('type' => 'hidden', "rules" => "trim","readonly"=>true),
                          "submit" 		=> array("type"	=> "submit"),
                          "reset" 		=> array("type" => "reset"),
                          "cancel" 		=> array("type" => "cancel"),
                        ),
          'display' => array(
                        '<div class="row col-sm-12">',
                          html_col2Start(),
                            html_blockStart(),
                              html_blockHeader("Request"),
                              array('title',
                                    'activity_date',
                                    'type',
                              ),
                            html_blockEnd(),
                        html_colEnd(),
                          html_col2Start(),
                            html_blockStart(),
                              html_blockHeader("Target"),
                              array(
                                    'target_date',
                                    'status',
                                    'description'
                              ),
                            html_blockEnd(),
                        html_colEnd(),
                          html_col2Start(),
                            html_blockStart(),
                              html_blockHeader("Result & Activity"),
                              array('survey_date',
                                    'survey_notes',
                              ),
                            html_blockEnd(),
                        html_colEnd(),
                        html_col1Start(),
                          html_blockStart(),
                          html_blockHeader(""),
                          array($this->CI->app_lib->getSurveyDoc($_REQUEST)),
                          html_blockEnd(),
                        html_colEnd(),
                        "</div>",
                        "<div class='col-sm-12'><div class='alert alert-info text-right'>SERVICE LIST</div></div>",
                      ),
          'result' => array( 'title', 'activity_date'),
          );
      }

/* WARNING : FIELD_NAME TIDAK BOLEH SAMA UNTUK SEMUA SERVICE !!!!!!!!!!!!!!!!!!!!!!!!!! */
  function form_SV_CONN($params=false)
  {
    return array(
            'title'	=> 'Connectivity',
            'item'	=> array(
                        't_survey_conn_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
                        't_survey_conn_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
  											'conn'               => array('type'	=> 'checkbox','label'=>'conn', "rules" => "trim","options"=>array("Y"=>array("CONNECTIVITY",false))),
  											'conn_distance'      => array('type'	=> 'text',"rules" => "trim"),
                        'conn_capacity'      =>  array('type'	=> 'text',"rules" => "trim"),
  											'conn_port'          =>  array('type'	=> 'text',"rules" => "trim"),
                        'conn_info'          =>  array('type'	=> 'textarea',"rules" => "trim"),
                        'conn_recommendation' =>  array('type'	=> 'textarea',"rules" => "trim"),
                      ),
        'display' => array(
                      html_col1Start(),
                        html_blockStart(),
                          array('conn'),
                          array('<div id=conn>',
                                'conn_distance',
                                'conn_capacity',
                                'conn_port',
                                'conn_info',
                                'conn_recommendation',
                                "</div>"
                          ),
                        html_blockEnd(),
                    html_colEnd(),
                ),
        'result' => array('conn'),
        );
    }
  function form_SV_VICON($params=false)
	{
    return array(
            'title'	=> 'Video Conference',
            'item'	=> array(
                        't_survey_vicon_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
                        'vicon'      => array('type'	=> 'checkbox','label'=>'vicon', "rules" => "trim","options"=>array("Y"=>array("VIDEO CONFERENCE",false))),
												'vicon_info_1'          => array('type'	=> 'textarea',"rules" => "trim"),
                        'vicon_info_2'  => array('type'	=> 'textarea',"rules" => "trim"),
                        'vicon_info_3' =>  array('type'	=> 'textarea',"rules" => "trim"),
												'vicon_info_4' =>  array('type'	=> 'textarea',"rules" => "trim"),
                        'vicon_recommendation' =>  array('type'	=> 'textarea',"rules" => "trim"),
                      ),
        'display' => array(
                      html_col1Start(),
                        html_blockStart(),
                          array('vicon'),
                          array('<div id=vicon>',
                                'vicon_info_1',
                                'vicon_info_2',
                                'vicon_info_3',
                                'vicon_info_4',
                                'vicon_recommendation',
                                '</div>'
                          ),
                        html_blockEnd(),
                    html_colEnd(),
                ),
        'result' => array( "vicon"),
        );
    }
  function form_SV_MGSRV($params=false)
	{
    return array(
            'title'	=> 'Manage Services',
            'item'	=> array(
                        't_survey_mgsrv_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
                        'mgsrv'      => array('type'	=> 'checkbox','label'=>'mgsrv', "rules" => "trim","options"=>array("Y"=>array("MANAGE SERVICES",false))),
												'mgsrv_info_1'          => array('type'	=> 'textarea',"rules" => "trim"),
                        'mgsrv_info_2'  => array('type'	=> 'textarea',"rules" => "trim"),
                        'mgsrv_info_3' =>  array('type'	=> 'textarea',"rules" => "trim"),
												'mgsrv_info_4' =>  array('type'	=> 'textarea',"rules" => "trim"),
                        'mgsrv_recommendation' =>  array('type'	=> 'textarea',"rules" => "trim"),
                      ),
        'display' => array(
                      html_col1Start(),
                        html_blockStart(),
                          array('mgsrv'),
                          array('<div id=mgsrv>',
                                'mgsrv_info_1',
                                'mgsrv_info_2',
                                'mgsrv_info_3',
                                'mgsrv_info_4',
                                'mgsrv_recommendation',
                                '</div>'
                              ),
                        html_blockEnd(),
                    html_colEnd(),
                ),
        'result' => array('mgsrv'),
        );
    }
    function form_SV_DC($params=false)
  	{
      return array(
            'title'	=> 'Data Center',
            'item'	=> array(
                        't_survey_dc_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
                        'dc'      => array('type'	=> 'checkbox','label'=>'dc', "rules" => "trim","options"=>array("Y"=>array("DATA CENTER",false))),
												'dc_info_1'          => array('type'	=> 'textarea',"rules" => "trim"),
                        'dc_info_2'  => array('type'	=> 'textarea',"rules" => "trim"),
                        'dc_info_3' =>  array('type'	=> 'textarea',"rules" => "trim"),
												'dc_info_4' =>  array('type'	=> 'textarea',"rules" => "trim"),
                        'dc_recommendation' =>  array('type'	=> 'textarea',"rules" => "trim"),
                      ),
        'display' => array(
                      html_col1Start(),
                        html_blockStart(),
                          array('dc'),
                          array('<div id=dc>',
                                'dc_info_1',
                                'dc_info_2',
                                'dc_info_3',
                                'dc_info_4',
                                'dc_recommendation',
                                '</div>'
                              ),
                        html_blockEnd(),
                    html_colEnd(),
                ),
        'result' => array('dc'),
        );
    }
    function form_SV_OTHER($params=false)
  	{
      return array(
              'title'	=> 'Other Service',
              'item'	=> array(
                          't_survey_other_id'   => array( 'type'	=> 'hidden', "rules" => "trim" ),
                          'other'             => array('type'	=> 'checkbox','label'=>'Other', "rules" => "trim","options"=>array("Y"=>array("OTHER SERVICE",false))),
                          'other_name'        => array('type'	=> 'text',"rules" => "trim"),
  												'other_info_1'        => array('type'	=> 'textarea',"rules" => "trim"),
                          'other_info_2'  => array('type'	=> 'textarea',"rules" => "trim"),
                          'other_info_3' =>  array('type'	=> 'textarea',"rules" => "trim"),
  												'other_info_4' =>  array('type'	=> 'textarea',"rules" => "trim"),
                          'other_recommendation' =>  array('type'	=> 'textarea',"rules" => "trim"),
                        ),
          'display' => array(
                        html_col1Start(),
                          html_blockStart(),
                            array('other'),
                            array('<div id=other>',
                                  'other_name',
                                  'other_info_1',
                                  'other_info_2',
                                  'other_info_3',
                                  'other_info_4',
                                  'other_recommendation',
                                  '</div>'
                                ),
                          html_blockEnd(),
                      html_colEnd(),
                  ),
          'result' => array('other'),
          );
      }
}
