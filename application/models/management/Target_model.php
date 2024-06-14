<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class target_model extends KOJE_Model
{
		function do_insert($params=false)
	  {
      $input  = $this->getInput($params);
	    $result = $this->CI->app_db->targetInsert($input);
	    return $result;
	  }
		function do_edit($params=false)
	  {
      $input  = $this->getInput($params);
	    $result = $this->CI->app_db->targetEdit($input);
	    return $result;
	  }
		function do_upload($params=false)
	  {
      $input  = $this->getInput($params);
	    $result = $this->CI->app_db->targetUpload($input);
	    return $result;
	  }
		/* ------------------------ form --------------------------------*/
		function form_search($params=false)
		{
			return array(
					'title'	=> 'Search Target',
					'item'	=>
							array(
									'year' => array(
											"type"	=> "select",
											"options" => $this->CI->koje_system->listYear(2018,date("Y")+1),
											"rules" => "trim"
									),
							),
					'display' => array(
							html_col3Start(),array('year'),html_colEnd(),
						),
			);
		}
		function form_general($params=false)
		{
			return	array(
								'title'	=> 'Target',
								'item'	=>
										array(
											't_target_id' => array("type"	=> "hidden","rules" => "trim"),
											'sales_id' 	=> array(
																				"type"	=> "select2",
																				"rules" => "trim|required",
																				"options" => $this->CI->adodb->getAssoc("select id, username from sys_users where active=1")
																		),
											'year' 			=> array("type"	=> "select2","rules" => "trim|required", "options"=>$this->CI->koje_system->listYear(2018,date("Y")+1)),
									    'description' => array("type"	=> "text","rules" => "trim"),
											'opportunity_jan_01'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jan'),
									    'opportunity_feb_02' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Feb'),
									    'opportunity_mar_03' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Mar'),
									    'opportunity_apr_04' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Apr'),
									    'opportunity_may_05' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'May'),
									    'opportunity_jun_06' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jun'),
									    'opportunity_jul_07' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jul'),
									    'opportunity_aug_08' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Aug'),
									    'opportunity_sep_09' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Sep'),
									    'opportunity_oct_10'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Oct'),
									    'opportunity_nov_11' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Nov'),
									    'opportunity_dec_12' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Dec'),
											'opportunity_total'			=> array("type"	=> "text","rules" => "trim", "readonly"=> true, 'label'=>'Total','format'=>'number'),
											'proposal_jan_01'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jan'),
									    'proposal_feb_02' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Feb'),
									    'proposal_mar_03' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Mar'),
									    'proposal_apr_04' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Apr'),
									    'proposal_may_05' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'May'),
									    'proposal_jun_06' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jun'),
									    'proposal_jul_07' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jul'),
									    'proposal_aug_08' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Aug'),
									    'proposal_sep_09' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Sep'),
									    'proposal_oct_10'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Oct'),
									    'proposal_nov_11' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Nov'),
									    'proposal_dec_12' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Dec'),
											'proposal_total'			=> array("type"	=> "text","rules" => "trim", "readonly"=> true, 'label'=>'Total','format'=>'number'),
											'negotiation_jan_01'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jan'),
									    'negotiation_feb_02' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Feb'),
									    'negotiation_mar_03' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Mar'),
									    'negotiation_apr_04' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Apr'),
									    'negotiation_may_05' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'May'),
									    'negotiation_jun_06' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jun'),
									    'negotiation_jul_07' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jul'),
									    'negotiation_aug_08' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Aug'),
									    'negotiation_sep_09' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Sep'),
									    'negotiation_oct_10'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Oct'),
									    'negotiation_nov_11' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Nov'),
									    'negotiation_dec_12' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Dec'),
											'negotiation_total'			=> array("type"	=> "text","rules" => "trim", "readonly"=> true, 'label'=>'Total','format'=>'number'),
											'closing_jan_01'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jan'),
									    'closing_feb_02' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Feb'),
									    'closing_mar_03' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Mar'),
									    'closing_apr_04' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Apr'),
									    'closing_may_05' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'May'),
									    'closing_jun_06' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jun'),
									    'closing_jul_07' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Jul'),
									    'closing_aug_08' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Aug'),
									    'closing_sep_09' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Sep'),
									    'closing_oct_10'		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Oct'),
									    'closing_nov_11' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Nov'),
									    'closing_dec_12' 		=> array("type"	=> "text","rules" => "trim|required",	'format' => 'number', 'label'=>'Dec'),
											'closing_total'			=> array("type"	=> "text","rules" => "trim", "readonly"=> true, 'label'=>'Total','format'=>'number'),
											'submit' 		=> array("type" 	=> "submit"),
											'reset' 		=> array("type" 	=> "reset"),
											'cancel' 		=> array("type" 	=> "cancel"),
										),
										'display' =>
												array(
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("Target Info"),
		                          array(
																		'sales_id',
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("&nbsp;"),
		                          array(
																		'year',
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col2Start(),
		                        html_blockStart(),
		                          html_blockHeader("&nbsp;"),
		                          array(
																		'description',
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("Target Opportunity (count)"),
		                          array(	'opportunity_jan_01',
																			'opportunity_feb_02',
																			'opportunity_mar_03',
																			'opportunity_apr_04',
																			'opportunity_may_05',
																			'opportunity_jun_06',
																			'opportunity_jul_07',
																			'opportunity_aug_08',
																			'opportunity_sep_09',
																			'opportunity_oct_10',
																			'opportunity_nov_11',
																			'opportunity_dec_12'
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("Target Proposal (count)"),
		                          array(	'proposal_jan_01',
																			'proposal_feb_02',
																			'proposal_mar_03',
																			'proposal_apr_04',
																			'proposal_may_05',
																			'proposal_jun_06',
																			'proposal_jul_07',
																			'proposal_aug_08',
																			'proposal_sep_09',
																			'proposal_oct_10',
																			'proposal_nov_11',
																			'proposal_dec_12'
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("Target Negotiation (count)"),
		                          array(	'negotiation_jan_01',
																			'negotiation_feb_02',
																			'negotiation_mar_03',
																			'negotiation_apr_04',
																			'negotiation_may_05',
																			'negotiation_jun_06',
																			'negotiation_jul_07',
																			'negotiation_aug_08',
																			'negotiation_sep_09',
																			'negotiation_oct_10',
																			'negotiation_nov_11',
																			'negotiation_dec_12'
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
													html_col3Start(),
		                        html_blockStart(),
		                          html_blockHeader("Target Closing (revenue)"),
		                          array(	'closing_jan_01',
																			'closing_feb_02',
																			'closing_mar_03',
																			'closing_apr_04',
																			'closing_may_05',
																			'closing_jun_06',
																			'closing_jul_07',
																			'closing_aug_08',
																			'closing_sep_09',
																			'closing_oct_10',
																			'closing_nov_11',
																			'closing_dec_12'
		                          ),
		                        html_blockEnd(),
		                    html_colEnd(),
												html_col3Start(),
	                        html_blockStart(),
	                          array(
																	'opportunity_total'
	                          ),
	                        html_blockEnd(),
	                    html_colEnd(),
											html_col3Start(),
												html_blockStart(),
													array(
																'proposal_total'
													),
												html_blockEnd(),
										html_colEnd(),
										html_col3Start(),
											html_blockStart(),
												array(
															'negotiation_total'
												),
											html_blockEnd(),
									html_colEnd(),
									html_col3Start(),
										html_blockStart(),
											array(
														'closing_total'
											),
										html_blockEnd(),
								html_colEnd(),
														"_BUTTON_"
											),
										'result' =>
												array(
													"sales_id",
													"year",
													'description'
											)
							);
		}
		function form_upload($params=false)
		{
			return array(
						'title'	=> 'Upload Data Target',
						'item'	=> array(
												'year' 			=> array("type"	=> "select2","rules" => "trim|required", "options"=>$this->CI->koje_system->listYear(date("Y"),date("Y")+1)),
												"file"     		=> array("type"=>"file", "title"=>"File XLS", "template" => 'tpl_target.xlsx'),
												'description' => array("type"	=> "text","rules" => "trim"),
												"submit" 			 => array("type" => "submit"),
												"reset" 		 	 => array("type" => "reset"),
												"cancel" 			 => array("type" => "cancel")
											),
				'display' => array(
												html_col2Start(),
													html_blockStart(),
														html_blockHeader("Target Info"),
														array('year','description'
														),
													html_blockEnd(),
												html_colEnd(),
												html_col2Start(),
													html_blockStart(),
														html_blockHeader("File Upload"),
														array('file' ),
													html_blockEnd(),
												html_colEnd(),
										'_BUTTON_'
								),
				'result' => array("year","description"),
				);
		}
}
