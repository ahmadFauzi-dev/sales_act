<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class activity_model extends KOJE_Model
{
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ProspectActivityInsert($input);
    return $result;
  }
	function do_edit($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->ProspectActivityEdit($input);
			return $result;
	}
	function do_drop($params=false) {
			$input  = $this->getInput($params);
			$result = $this->CI->app_db->ProspectActivityDrop($input);
			return $result;
	}
	/* ------------------------ form --------------------------------*/
	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search Prospect Activity',
				'item'	=>
						array(
								'type_desc' => array(
										"type"	=> "select",
                    "options" => $this->CI->koje_system->getRefDomainDescList("PROSPECT__ACTIVITY_TYPE"),
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('type_desc'),html_colEnd(),
					),
		);
	}

	function form_activity($params=false)
	{
		return array(
					'title'	=> 'Form Activity',
					'item'	=> array(
											't_prospect_id'=> array('type' => 'hidden', "rules" => "trim"),
                      't_prospect_step_id'=> array('type' => 'hidden', "rules" => "trim"),
											't_prospect_activity_id'=> array('type' => 'hidden', "rules" => "trim"),
                      'type'=> array('type' => 'select2', "rules" => "trim", 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__ACTIVITY_TYPE')),
                      'start_date'  => array('type' => 'dateTimePicker', "rules" => "trim|required"),
                      'end_date'  => array('type' => 'dateTimePicker', "rules" => "trim"),
											'subject' => array('type' => 'text', "rules" => "trim|required"),
											'description'=> array('type' => 'textarea', "rules" => "trim"),
											'status'  => array('type' => 'select2', "rules" => "trim|required", 'options'	=> $this->CI->koje_system->getRefDomainList('PROSPECT__ACTIVITY_STATUS')),
											'result'    => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Activity Info"),
  												array('type',
                                'start_date',
  															'subject',
                                'description',
                          ),
                        html_blockEnd(),
    									html_colEnd(),
                      html_col2Start(),
                        html_blockStart(),
  												html_blockHeader("Status & Result"),
                          array(
  															'status',
                                'end_date',
  															'result',
  											  ),
										   html_blockEnd(),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array("start_date", "subject", "result"),
			);
	}
}
