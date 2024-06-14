<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new flow_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class flow_view {
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

			$strSearch = $myForm->renderSearchDataTables();
			$str = "";
			$table = new koje_datatables($myForm->id);
			$str .= $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'title' => "Data Flow Discount",
					"query" 	=> array("from" => "FROM v_flow",
													 "where" => "where 1=1 and parent_id=0",
													 "params" => array()
										),
					'primary_key' => 'r_flow_id',
					'search'	=> $strSearch,
					'order' => array(1 =>'asc'),					
					
					'toolbarButton' => array(
					'insert'  => array('title' => 'New Record ',  'url' => $this->CI->koje_system->URLBuild(false,'insert')),
													),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => '',
	'button'=> array(
																																'edit'   => array('title' => 'Edit',   'url' => $this->CI->koje_system->URLBuild(false,'edit','')),
																																'delete' => array('title' => 'Delete', 'url' => $this->CI->koje_system->URLBuild(false,'delete','')),
																																'_DIVIDER_',
																																// 'detail' 			=> array(	'title' => getLabel('BTN_DETAIL'),
																																											// 'url' => '#',
																																											// 'attr' => 'onclick=\"return showLog(\':data\');\"',
																																										// ),
	'detail' => array('title' => 'Detail', 'url' => $this->CI->koje_system->URLBuild(false,'detail','')),																														)
																									),
													
													'name' => array('title' => 'Flow Approval'),
													'discount' => array('title' => 'Discount (in percent)'),
													'regional_office' => array('title' => 'Regional Office')
										)
				)
			);

			print $str;

		}


		function detail($params=false) {
			$pagerID = PAGER::P1;
			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
					// echo "<pre>";
		// var_dump($params['DATA_PARENT']['r_ro_id']);
		// echo "</pre>";
		// die();
			$parent_id = $params['r_flow_id'];
			
			$strSearch = $myForm->renderSearchDataTables();
			$str = "";
			$table = new koje_datatables($myForm->id);
			$str .= $table->renderDataTable(
				array(					
					'id' 		=> $pagerID,
					'title' => "Detail Data ".$params['DATA_PARENT']['name']." & ".$params['DATA_PARENT']['regional_office'],
					"query" 	=> array("from" => "FROM v_flow",
													 "where" => "where 1=1 and parent_id=$parent_id",
													 "params" => array()
										),
					'primary_key' => 'r_flow_id',
					'search'	=> "",
					'order' => array(
														1=>'asc',														
													),
					'toolbarButton' => array(
					'insert'  => array('title' => 'New Record ',  'url' => $this->CI->koje_system->URLBuild(false,'detail_insert','parent_id='.$parent_id)),
													),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => '',
	'button'=> array(
																																'edit'   => array('title' => 'Edit',   'url' => $this->CI->koje_system->URLBuild(false,'detail_edit','')),
																																'delete' => array('title' => 'Delete', 'url' => $this->CI->koje_system->URLBuild(false,'delete','')),
																																																	)
																									),
													'priority' =>array('title' => 'Priority'),
													'name' => array('title' => 'Flow Approval'),
													// 'discount' => array('title' => 'Discount (in percent)'),
													'val' => array('title' => 'Name PIC')
										)
				)
			);

			print $str;

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
	}
?>

<script>
	function showLog(data) {
			id = data.replace('KOJE_BTN_GROUP_detail_','');
			windowPopup(base_url+'_system_/showWindow/index?lov=flow_detail_lov&r_flow_id='+id,'','');
			return false;
		}
		$(document).ready(function(){
			$("#list_quotation :input").prop("readonly", true);
			$("#list_quotation select").prop("disabled", true);
		})
</script>