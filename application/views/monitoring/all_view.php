<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new all_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class all_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}

		function form_general($params=false)
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
					'title' => 'All Prospect List',
					'primary_key' => 't_prospect_id',
					"query" => array(	 "from" 	=> "FROM v_prospect_current a",
														 "where"  => "WHERE ($loginID=sales_id or utils_is_leader_from($loginID,sales_id)='Y')",
														 "params" => array()
										),
					'order' => array(
														PAGER::COL1=>'desc',
														PAGER::COL3=>'desc',
													),
					'toolbarButton' => array( ),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																									 'button'=> array(
																													'detail'   	=> array(	'title' => getLabel('BTN_DETAIL'),
																																								'url' => $this->CI->koje_system->URLBuild(false,'detail',''),
																																							),
																												)
																									),
													'company_name'					=> array(),
													'probability' 		    	=> array('format'=>'percentage'),
													'duration_days'					=> array(),
													'status_desc' 					=> array(),
													'prospect_type_desc' 		=> array(),
													'prospect_start_date'   => array('format'=>'date'),
													'target_amount'					=> array('format'=>'number'),
													'stage_name'						=> array(),
													'stage_start_date'   		=> array('format'=>'date','title' => 'Stage Start Date'),
													'prospect_no' 					=> array(),
													'sales_name' 						=> array(),
													'sys_created_date' 			=> array('format'=>'datetime'),
										),
										'summary' => array(),
				)
			);

			print $str;
		}

		function detail($params=false)
		{	$id = $this->CI->input->get_post('t_prospect_id');
			$act = getValueNull($this->CI->input->get_post('act'),'');

			$btnLeft   = array();
			$btnCenter = array('negotiation','quotation','quotation_item','requirement','survey','presentation','proposal','telemarketing','stage');
			$str = $this->CI->app_lib->prospectlistButton($id,$act,$btnLeft, $btnCenter);
			switch ($act) {
					case 'requirement':
							$str .= $this->CI->app_lib->getListRequirement($id,true);
							break;
					case 'negotiation':
							$str .= $this->CI->app_lib->getListNegotiation($id,true);
							break;
					case 'quotation':
							$str .= $this->CI->app_lib->getListQuotation($id,true);
							break;
					case 'quotation_item':
							$str .= $this->CI->app_lib->getListQuotationItem($id,true);
							break;
					case 'survey':
							$str .= $this->CI->app_lib->getListSurvey($id,true);
							break;
					case 'presentation':
							$str .= $this->CI->app_lib->getListPresentation($id,true);
							break;
					case 'proposal':
							$str .= $this->CI->app_lib->getListProposal($id,true);
							break;
					case 'telemarketing':
							$str .= $this->CI->app_lib->getListTelemarketing($id,true);
							break;
					case 'stage':
							$str .= $this->CI->app_lib->getListStage($id,true);
							break;
			}
			$str .= $this->CI->app_lib->getOportunityDocView($id);
			$str .= $this->CI->app_lib->getInfoProspect($id);
			print $str;
		}

	}
