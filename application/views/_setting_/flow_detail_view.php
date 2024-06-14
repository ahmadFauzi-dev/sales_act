<?php
	$myView = new flow_detail_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);
	class flow_detail_view {
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
																																'delete' => array('title' => 'Delete', 'url' => $this->CI->koje_system->URLBuild(false,'detail_delete','')),
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
			$myForm = new KOJE_Form('MY_FORM1');
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