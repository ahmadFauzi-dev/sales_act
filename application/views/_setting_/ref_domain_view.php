<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new ref_domain_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class ref_domain_view {
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
					'title' => "Data Reference",
					"query" 	=> array("from" => "FROM t_reference",
													 "where" => "where 1=1",
													 "params" => array()
										),
					'primary_key' => 'reference_id',
					'search'	=> $strSearch,
					'order' => array(
														2=>'asc',
														3=>'asc',
													),
					'toolbarButton' => array(
														'insert'  => array('title' => 'New Record ',  'url' => $this->CI->koje_system->URLBuild(false,'insert')),
													),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => '',
																									 'button'=> array(
																																'edit'   => array('title' => 'Edit',   'url' => $this->CI->koje_system->URLBuild(false,'edit','')),
																																'delete' => array('title' => 'Delete', 'url' => $this->CI->koje_system->URLBuild(false,'delete','')),
																																'_DIVIDER_',
																																'submit' => array('title' => 'Submit', 'url' => $this->CI->koje_system->URLBuild(false,'submit','')),
																															)
																									),
													'reference_id' 		=> array('title' => 'ID'),
													'group_reference' => array('title' => 'Group'),
													'val' 						=> array('title' => 'Value'),
													'description' 		=> array('title' => 'Description'),
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
