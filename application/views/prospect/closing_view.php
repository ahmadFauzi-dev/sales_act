<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new closing_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class closing_view {
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

		function index($params=false)
		{
			$loginID = $this->CI->koje_system->getLoginID();
			$pagerID = PAGER::P1;
			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$strSearch = $myForm->renderSearchDataTables();
			$t_stage_id = $this->CI->app_lib->getIDStage('CLOSING');
			$status = $this->CI->app_lib->getProspectStatus('ACTIVE');
			$table = new koje_datatables($myForm->id);
			echo $t_stage_id;
			//tambah by VT			
			if ($loginID == 1) {
				$query = array(	 "from" 	=> "FROM v_prospect_current a",
							 "where"  => "WHERE status='$status' and t_stage_id=$t_stage_id",
							 "params" => array()
							 );
			} else {
				$query = array(	 "from" 	=> "FROM v_prospect_current a",
							 "where"  => "WHERE sales_id=$loginID and status='$status' and t_stage_id=$t_stage_id",
							 "params" => array()
							 );
			}
			//
			
			$str = $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'search'=> $strSearch,
					'title' => 'Customer List',
					'primary_key' => 't_prospect_id',
					"query" => $query,
					'order' => array(
														PAGER::COL5=>'desc',
														PAGER::COL0=>'desc',
													),
					'toolbarButton' => array(
													),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																									 'button'=> array(
																													 'detail' 	=> array(	'title' => getLabel('BTN_DETAIL'),
																																								 'url' => $this->CI->koje_system->URLBuild(false,'detail',''),
																																							 ),
																														'generate' 	=> array(	'title' => getLabel('BTN_GENERATE_BILLING'),
																																									'url' => $this->CI->koje_system->URLBuild(false,'generate',''),
																																								)
																												)
																									),
													'company_name'					=> array(),
													'stage_start_date'   		=> array('format'=>'date','title' => 'Stage Start Date'),
													'duration_days'					=> array(),
													'target_amount'					=> array('format'=>'number'),
													'probability' 		    	=> array('format'=>'percentage'),
													'prospect_no' 					=> array(),
													'quotation_deal_info'	  => array(),
													'prospect_type_desc' 		=> array(),
													'prospect_start_date'   => array('format'=>'date'),
													'sys_created_date' 			=> array('format'=>'datetime'),
										),
										'summary' => array(),
				)
			);

			print $str;
		}

		function detail($params=false)
		{
			$loginID = $this->CI->koje_system->getLoginID();
			$id = $this->CI->input->get_post('t_prospect_id');
			$act = getValueNull($this->CI->input->get_post('act'),'');

			$btnLeft   = array('generate_billing'=>array('url' => 'generate'));
			$btnCenter = array('negotiation','quotation','quotation_item','requirement','survey','presentation','proposal','telemarketing','stage');
			$btnRight  = array();
			$str = $this->CI->app_lib->prospectlistButton($id,$act,$btnLeft, $btnCenter, $btnRight);
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
			$str .= $this->CI->app_lib->getOportunityDoc(array('t_prospect_id'=>$id));
			$str .= $this->CI->app_lib->getInfoProspect($id);
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
		function print(data) {
				id = data.replace('KOJE_BTN_GROUP_print_','');
				windowPopup(base_url+'_system_/ExportPdf/index?lov=quotation_print_lov&t_quotation_id='+id,'','');
				return false;
			}
</script>
