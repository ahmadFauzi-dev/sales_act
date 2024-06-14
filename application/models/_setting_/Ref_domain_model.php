<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class ref_domain_model extends KOJE_Model
{
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ReferenceInsert($input);
    return $result;
  }
	function do_edit($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ReferenceEdit($input);
    return $result;
  }
	/* ------------------------ form --------------------------------*/

	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search Reference',
				'item'	=>
						array(
							'group_reference'=> array('type' => 'select', "rules" => "trim", 'options'	=> $this->CI->adodb->getAssoc("select distinct group_reference, group_reference as name from t_reference")),
						),
				'display' => array(
						html_col3Start(),array('group_reference'),html_colEnd(),
					),
				'result' => array()
		);
	}
	function form_insert($params=false)
	{
		return array(
					'title'	=> 'Form Reference Insert',
					'item'	=> array(
											'group_reference'=> array('type' => 'select', "rules" => "trim", 'options'	=> $this->CI->adodb->getAssoc("select distinct group_reference, group_reference as name from t_reference")),
											'val'  => array('type' => 'text', "rules" => "trim|required"),
											'priority'  => array('type' => 'text', "rules" => "trim"),
											'description'  => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('group_reference','val','priority','description'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('group_reference','val','priority','description'),
			);
	}
	function form_edit($params=false)
	{
		return array(
					'title'	=> 'Form Reference Edit',
					'item'	=> array(
											'reference_id'  => array('type' => 'hidden', "rules" => "trim|required"),
											'group_reference'=> array('type' => 'select', "rules" => "trim", 'options'	=> $this->CI->adodb->getAssoc("select distinct group_reference, group_reference as name from t_reference")),
											'val'  => array('type' => 'text', "rules" => "trim|required"),
											'priority'  => array('type' => 'text', "rules" => "trim"),
											'description'  => array('type' => 'textarea', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('group_reference','val','priority','description'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('group_reference','val','priority','description'),
			);
	}
}
