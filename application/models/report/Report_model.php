<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class report_model {
	var $CI;

	/*  START  DO NOT EDIT  */
	/* Next --> menjadi KOJE_Model.php */
	function __construct() {
		$this->CI =& get_instance();
	}
	function getForm($str,$params, $readonly=false)
	{
		$form = $this->$str($params);

		if($readonly)
		{
				$this->CI->koje_system->array_add_element($form['item'], array('readonly'=>true));
		}

		foreach ($form['item'] as $key => $value) {
			if (!isset($form['item'][$key]['field'])) $form['item'][$key]['field'] 	= $key;
			if (!isset($form['item'][$key]['id'])) 		$form['item'][$key]['id'] 		= $key;
			if (!isset($form['item'][$key]['name'])) 	$form['item'][$key]['name'] 	= $key;
			if (!isset($form['item'][$key]['label'])) $form['item'][$key]['label'] 	= getLabel($key);
			if (isset($form['item'][$key]['readonly']) && $form['item'][$key]['type']=='select2')
			{
				$form['item'][$key]['type'] = "select";
			}
			if (isset($form['item'][$key]['readonly']) && $form['item'][$key]['type']=='dateTimePicker')
			{
				$form['item'][$key]['type'] = "text";
			}
		}
		return $form;
	}
  /*  STOP DO NOT EDIT  */
/* -------------------------- Action ---------------------------------*/
	function getYear()
	{
		return $this->CI->koje_system->listYear(2018,date("Y"));
		return array("2018"=>"2018","2019"=>"2019","2020"=>"2020","2021"=>"2021","2022"=>"2022","2023"=>"2023","2024"=>"2024");
	}

	/* ------------------------ form --------------------------------*/
	function form_search($params=false)
	{
		return array(
				'title'	=> 'Search Year',
				'item'	=>
						array(
								'year' => array(
										"type"	=> "select",
										"options" => $this->getYear(),
										"rules" => "trim"
								),
						),
				'display' => array(
						html_col3Start(),array('year'),html_colEnd(),
					),
		);
	}
}
