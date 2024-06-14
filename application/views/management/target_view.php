<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new target_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class target_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function view_general($params=false)
		{
			$myForm = new KOJE_Form('MY_FORM');
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$myForm->setResult($params['FORMS']['result']);
			$myForm->setUrlBack($params['FORMS']);
			$myForm->setUrlCancel($params['FORMS']);
			$myForm->show($params);
		}
		function index($params=false) {
			$pagerID = PAGER::P1;
			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$loginID = $this->CI->koje_system->getLoginID();
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);
			$str = $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'search'=> $strSearch,
					'title' => "Current Target",
					'primary_key' => 't_target_id',
					"query" => array(	 "from" 	=> "FROM v_target a",
														 "where"  => "WHERE 1=1",
														 "params" => array()
										),
					'order' => array(
														PAGER::COL5=>'desc',
													),
					'toolbarButton' => array(
														'insert'  => array('title' => 'New Target ',  'url' => $this->CI->koje_system->URLBuild(false,'insert','')),
														'upload'  => array('title' => 'Upload Target ',  'url' => $this->CI->koje_system->URLBuild(false,'upload','')),
													),
					'columns' => array(
													'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																		 'button'=> array(
																									'edit'   	=> array(	'title' => TITLE_MSG::BUTTON_EDIT,
																																				'url' => $this->CI->koje_system->URLBuild(false,'edit',''),
																																			),
																								)
																		),
													'sales_name' 		=> array(),
													'year' 					=> array(),
													'description' 	=> array(),
													'opportunity_total'	=> array('format'=>'number'),
													'proposal_total' 		=> array('format'=>'number'),
													'negotiation_total' => array('format'=>'number'),
													'closing_total' 		=> array('format'=>'number'),
										),
					'summary'	=> array(4,5,6,7)
				)
			);

			print $str;
		}
	}
?>
<script>
	$(document).ready(function(){
		$("input").on("change", function(){
			               $("#opportunity_total").val(
												intVal($("#opportunity_jan_01").val())+intVal($("#opportunity_feb_02").val())+intVal($("#opportunity_mar_03").val())+intVal($("#opportunity_apr_04").val())+
												intVal($("#opportunity_may_05").val())+intVal($("#opportunity_jun_06").val())+intVal($("#opportunity_jul_07").val())+intVal($("#opportunity_aug_08").val())+
												intVal($("#opportunity_sep_09").val())+intVal($("#opportunity_oct_10").val())+intVal($("#opportunity_nov_11").val())+intVal($("#opportunity_dec_12").val())
											);
												$("#proposal_total").val(
 												intVal($("#proposal_jan_01").val())+intVal($("#proposal_feb_02").val())+intVal($("#proposal_mar_03").val())+intVal($("#proposal_apr_04").val())+
 												intVal($("#proposal_may_05").val())+intVal($("#proposal_jun_06").val())+intVal($("#proposal_jul_07").val())+intVal($("#proposal_aug_08").val())+
 												intVal($("#proposal_sep_09").val())+intVal($("#proposal_oct_10").val())+intVal($("#proposal_nov_11").val())+intVal($("#proposal_dec_12").val())
 											);
											$("#negotiation_total").val(
 												intVal($("#negotiation_jan_01").val())+intVal($("#negotiation_feb_02").val())+intVal($("#negotiation_mar_03").val())+intVal($("#negotiation_apr_04").val())+
 												intVal($("#negotiation_may_05").val())+intVal($("#negotiation_jun_06").val())+intVal($("#negotiation_jul_07").val())+intVal($("#negotiation_aug_08").val())+
 												intVal($("#negotiation_sep_09").val())+intVal($("#negotiation_oct_10").val())+intVal($("#negotiation_nov_11").val())+intVal($("#negotiation_dec_12").val())
 											);
											$("#closing_total").val(
 												intVal($("#closing_jan_01").val())+intVal($("#closing_feb_02").val())+intVal($("#closing_mar_03").val())+intVal($("#closing_apr_04").val())+
 												intVal($("#closing_may_05").val())+intVal($("#closing_jun_06").val())+intVal($("#closing_jul_07").val())+intVal($("#closing_aug_08").val())+
 												intVal($("#closing_sep_09").val())+intVal($("#closing_oct_10").val())+intVal($("#closing_nov_11").val())+intVal($("#closing_dec_12").val())
 											);
		});
	})
</script>
