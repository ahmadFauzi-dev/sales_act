<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new request_survey_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class request_survey_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false) {
			$pagerID = PAGER::P1;
			$loginID = $this->CI->koje_system->getLoginID();
			$params['FORMS'] = $this->CI->proposal_model->getForm('form_search',$params);
			$myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);

			$str = $table->renderDataTable(
														array(
															'id' 		=> $pagerID,
															'search'=> '&nbsp;',
															'title' => "Request Survey",
															'primary_key' => 't_survey_id',
															"query" => array(	 "from" 	=> "FROM v_survey a",
																								 "where"  => "WHERE status in('SVST-01','SVST-02','SVST-05')",
																								 "params" => array(),
																				),
															'order' => array(
																								PAGER::COL2=>'asc',
																								PAGER::COL3=>'asc',
																							),
															'toolbarButton' => array(
																							),
															'columns' => array(
																						'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																																			 'button'=> array(
																																										'approval' 			=> array(	'title' => getLabel('BTN_PROCESS'),
																																																					'url' => $this->CI->koje_system->URLBuild(false,'process',''),
																																																				),
																																			),
																																),
																						'title' 					=> array(),
																						'target_date' 		=> array('format'=>'date'),
																						'type_desc' 			=> array(),
																						'description' 		=> array(),
																						'service_lists_info'		=> array(),
																						'status_desc' 					=> array(),
																						'survey_date' 		=> array('format'=>'date'),
																						'survey_notes' 		=> array(),
																						'activity_date' 	=> array('format'=>'date'),
																						'sys_created_date'=> array('format'=>'datetime'),
																						'attachment_1_info'	=> array('format'=>'file'),
																						'attachment_2_info' => array('format'=>'file'),
																						'attachment_3_info' => array('format'=>'file'),
																				),
																	'summary'	=> array(),
																)
			);

			print $str;
		}
		function view_general($params=false) {
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

<script>
$(document).ready(function(){
	$("#conn_1,#busol_1,#mgsrv_1,#dc_1,#vicon_1").change(function() {
			this.checked = true;
	});
})
</script>
