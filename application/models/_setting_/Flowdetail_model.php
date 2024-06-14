<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class flowdetail_model extends KOJE_Model
{
  function do_insert($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->FLOWdetailInsert($input);
    return $result;
  }
	function do_edit($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->FLOWdetailEdit($input);
    return $result;
  }
	function do_delete($params=false)
  {
    $input  = $this->getInput($params);
    $result = $this->CI->app_db->FLOWdetailDelete($input);
    return $result;
  }
  
	/* ------------------------ form --------------------------------*/

	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search By RO',
				'item'	=>
						array(
							'regional_office'=> array('label'=>'Regional Office','type' => 'select', 'options'	=> $this->CI->adodb->getAssoc("select name, name from r_ro"), 'value' => 'regional_office'),
						),
				'display' => array(
						html_col1Start(),array('regional_office'),html_colEnd(),
					),
				'result' => array()
		);
	}
	
	function form_insert($params=false)
	{
		$emp_position = array(
				'name' => 'name',
				'id' => 'name',
				'type' => 'select',
				'class' => 'select2',
				'options' => array('SVP' => 'SVP', 'MGR'=>'MGR', 'GM' => 'GM','BOD' => 'BOD'),
				'value' => 'SALES'
			);
			
		$listROdb = $this->CI->db	->select('r_ro_id, name ro_name')
		
								->get('r_ro');
			// $listROdb = 	$this->CI->adodb->getAssoc("select name, name from r_ro");			
			$listRO = array();
			$listRO[] = "";
			foreach ($listROdb->result() as $key => $RO) {
				if(!empty($RO->r_ro_id))
				{
					$listRO[$RO->r_ro_id] = $RO->ro_name;
				}
			}

			$r_ro_id = array(
				'name' => 'r_ro_id',
				'id' => 'r_ro_id',
				'type' => 'select',
				'class' => 'select2',
				'options' => $listRO,
				'value' => 'Regional Office',
				'label' => 'Regional Office',
			);
			
		return array(
					'title'	=> 'Form Flow Discount Insert',
					'item'	=> array(
											
											'name'  => array('type' => 'text', "rules" => "trim|required"),
											'discount'  => array('type' => 'text', "rules" => "trim|required","label" => "Discount"),
											'r_ro_id' => $r_ro_id,
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('name','discount','r_ro_id'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('name','discount','r_ro_id'),
			);
	}
	function form_edit($params=false)
	{
				$emp_position = array(
				'name' => 'name',
				'id' => 'name',
				'type' => 'select',
				'class' => 'select2',
				'options' => array('SVP' => 'SVP', 'MGR'=>'MGR', 'GM' => 'GM','BOD' => 'BOD'),
				'value' => 'SALES'
			);
		return array(
					'title'	=> 'Form Flow Discount Edit',
					'item'	=> array(
											'r_flow_id'  => array('type' => 'hidden', "rules" => "trim|required"),
											'name'  => array('type' => 'text', "rules" => "trim|required"),
											'discount'  => array('type' => 'text', "rules" => "trim|required", "label" => "Discount"),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('name','discount'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('name','discount','r_ro_id'),
			);
	}
	function form_delete($params=false)
	{
		return array(
					'title'	=> 'Form Flow Discount Delete',
					'item'	=> array(
											'r_flow_id'  => array('type' => 'hidden', "rules" => "trim|required"),
											'name'  => array('type' => 'text', "rules" => "trim|required", "readonly" => true),
											'discount'  => array('type' => 'text', "rules" => "trim|required", "readonly" => true),
											"submit" 			 => array("type" => "submit"),
											"reset" 		 	 => array("type" => "reset"),
											"cancel" 			 => array("type" => "cancel")
										),
			'display' => array(
											html_col1Start(),
  												array('name','discount'
                          ),
									   html_colEnd(),
									'_BUTTON_'
							),
			'result' => array('name','discount','r_ro_id'),
			);
	}
	
}
