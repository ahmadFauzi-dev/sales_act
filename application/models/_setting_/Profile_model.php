<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class profile_model extends KOJE_Model
{
  function do_pict($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->uploadPict($input);
    return $result;
  }
	/* ------------------------ form --------------------------------*/

	function form_pict($params=false)
	{
		return array(
					'title'	=> 'Form Upload Picture',
					'item'	=> array(
                      'pict'  => array('type' => 'file', "rules" => "trim"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('pict'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array("pict"),
			);
	}
}
