<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new approval_quotation_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class approval_quotation_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false) {
			$pagerID = PAGER::P1;
			$loginID = $this->CI->koje_system->getLoginID();
			$params['FORMS'] = $this->CI->negotiation_model->getForm('form_search',$params);
			$myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);
			$status = $this->CI->app_lib->getQuotationStatus('REQUEST');
			$str = $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'search'=> '&nbsp;',
					'title' => "Request Approval",
					'primary_key' => 't_quotation_id',
					"query" => array(	 "from" 	=> "FROM v_quotation a",
														 // "where"  => "WHERE status='$status' and ($loginID=1 or utils_is_leader_from($loginID,request_by)='Y')",
														 "where"  => "WHERE status='$status' and ($loginID=1 or is_workflow($loginID,t_quotation_id)='Y')",
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
																																'approval' 			=> array(	'title' => getLabel('BTN_APPROVAL'),
																																											'url' => $this->CI->koje_system->URLBuild(false,'approval',''),
																																										),
																																'_DIVIDER_',
																																'log' 			=> array(	'title' => getLabel('BTN_LOG'),
																																											'url' => '#',
																																											'attr' => 'onclick=\"return showLog(\':data\');\"',
																																										),
																															),
																									),
													'quotation_no' 					=> array(),
													'name' 									=> array(),
													'status_desc' 					=> array(),
													'request_by_name' 	    => array( ),
													'sum_amount' 						=> array('format'=>'number'),
													'expired_date' 					=> array('format'=>'date'),
													'approved_date' 	  		=> array('format'=>'datetime')
										),
							'summary'	=> array(5),
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
		function view_approval($params=false) {
			$myForm = new KOJE_Form('MY_FORM');
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$myForm->setResult($params['FORMS']['result']);
			$myForm->setUrlBack($params['FORMS']);
			$myForm->setUrlCancel($params['FORMS']);
      $myForm->show($params);
      $t_quotation_id = $this->CI->input->get_post('t_quotation_id');
      $str = $this->CI->app_lib->getProspectDuplicate($t_quotation_id);
      print $str;
		}
	}
?>

<script>
	function showLog(data) {
			id = data.replace('KOJE_BTN_GROUP_log_','');
			windowPopup(base_url+'_system_/showWindow/index?lov=quotation_log_lov&t_quotation_id='+id,'','');
			return false;
		}
		$(document).ready(function(){
			$("#list_quotation :input").prop("readonly", true);
			$("#list_quotation select").prop("disabled", true);
		})
</script>
