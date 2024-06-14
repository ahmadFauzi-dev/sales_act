<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class ro_model extends KOJE_Model
{
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ROInsert($input);
    return $result;
  }
	function do_edit($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->ROEdit($input);
    return $result;
  }
	function do_delete($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->RODelete($input);
    return $result;
  }
  
	/* ------------------------ form --------------------------------*/

	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search RO',
				'item'	=>
						array(),
				'display' => array(
					),
				'result' => array()
		);
	}
	
	function form_insert($params=false)
	{
		return array(
					'title'	=> 'Form RO Insert',
					'item'	=> array(
											'ID'  => array('type' => 'text', "rules" => "trim|required"),
											'name'  => array('type' => 'text', "rules" => "trim|required"),
											'code'  => array('type' => 'text', "rules" => "trim|required"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('ID','name','code'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('ID','name','code'),
			);
	}
	function form_edit($params=false)
	{
		return array(
					'title'	=> 'Form RO Edit',
					'item'	=> array(
											'r_ro_id'  => array('type' => 'hidden', "rules" => "trim|required"),
											'name'  => array('type' => 'text', "rules" => "trim|required"),
											'ro_code'  => array('type' => 'text', "rules" => "trim|required", "label" => "Code"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('name','ro_code'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('name','ro_code'),
			);
	}
	function form_delete($params=false)
	{
		return array(
					'title'	=> 'Form RO Delete',
					'item'	=> array(
											'r_ro_id'  => array('type' => 'hidden', "rules" => "trim|required"),
											'name'  => array('type' => 'text', "rules" => "trim|required", "readonly" => true),
											'ro_code'  => array('type' => 'text', "rules" => "trim|required", "readonly" => true),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('name','ro_code'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('name','ro_code'),
			);
	}
	
}
