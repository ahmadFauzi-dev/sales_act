<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new target_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class target_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
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
														 "where"  => "WHERE (a.sales_id=$loginID or utils_is_leader_from($loginID,a.sales_id)='Y')",
														 "params" => array()
										),
					'order' => array(
														PAGER::COL5=>'desc',
													),
					'toolbarButton' => array(
													),
					'columns' => array(
													'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																		 'button'=> array(
																									'edit'   	=> array(	'title' => TITLE_MSG::BUTTON_DETAIL,
																																				'url' => $this->CI->koje_system->URLBuild(false,'view',''),
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
		function view($params=false) {
			$myForm = new KOJE_Form('MY_FORM');
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$myForm->setResult($params['FORMS']['result']);
			$myForm->setUrlBack($params['FORMS']);
			$myForm->setUrlCancel($params['FORMS']);
			$myForm->show($params);
		}
	}
?>
