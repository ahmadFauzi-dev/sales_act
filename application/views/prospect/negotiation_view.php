<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new negotiation_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class negotiation_view {
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
			$t_stage_id = $this->CI->app_lib->getIDStage('NEGOTIATION');
			$status = $this->CI->app_lib->getProspectStatus('ACTIVE');
			$table = new koje_datatables($myForm->id);
			
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
									'title' => 'Negotiation',
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
					 																													'next' 	=> array(	'title' => getLabel('BTN_NEXT_STAGE'),
					 																																								'url' => $this->CI->koje_system->URLBuild(false,'next',''),
					 																																							),
																																	'_DIVIDER_',
				 																													 'edit' 			=> array(	'title' => getLabel('BTN_EDIT_PROFILE'),
				 																																								 'url' => $this->CI->koje_system->URLBuild(false,'edit',''),
				 																																							 ),
																																	'cancel' 			=> array(	'title' => getLabel('BTN_CANCEL'),
																																												'url' => $this->CI->koje_system->URLBuild(false,'cancel',''),
																																											),
																																)
																													),
																	'company_name'					=> array(),
																	'stage_start_date'   		=> array('format'=>'date','title' => 'Stage Start Date'),
																	'duration_days'					=> array(),
																	'target_amount'					=> array('format'=>'number'),
																	'probability' 		    	=> array('format'=>'percentage'),
																	'quotation_info'				=> array(),
																	'quotation_deal_info'	  => array(),
																	'prospect_type_desc' 		=> array(),
																	'prospect_no' 					=> array(),
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
			$this->CI->load->model('prospect/negotiation_model');
			$loginID = $this->CI->koje_system->getLoginID();
			$id = $this->CI->input->get_post('t_prospect_id');
			$act = getValueNull($this->CI->input->get_post('act'),'');

			$btnLeft   = array('requirement','quotation','quotation_item','deal','document');
			$btnCenter = array('negotiation','survey','presentation','proposal','telemarketing','stage');
			$btnRight  = array('next_stage'=>array('url' => 'next'));
			$str = $this->CI->app_lib->prospectlistButton($id,$act,$btnLeft, $btnCenter, $btnRight);


			switch ($act) {
				case 'requirement' :
									$pagerID = PAGER::P2;
									$params['FORMS'] = $this->CI->negotiation_model->getForm('form_search_requirement',$params);
									$myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
									$strSearch = $myForm->renderSearchDataTables();
									$table = new koje_datatables($myForm->id);
									$str .= $table->renderDataTable(
										array(
											'id' 		=> $pagerID,
											'search'=> $strSearch,
											'title' => "Customer Requirement",
											'primary_key' => 't_requirement_id',
											"query" => array(	 "from" 	=> "FROM v_requirement",
																				 "where"  => "WHERE t_prospect_id=$id",
																				 "params" => array()
																),
											'order' => array(
																				PAGER::COL1=>'asc',
																			),
											'toolbarButton' => array(
																				'insert'  => array(	'title' => 'Change Requirement', 'url' => $this->CI->koje_system->URLBuild(false,'requirement_generate',"t_prospect_id=$id")),
																			),
											'columns' => array(
																			'product_code' 						=> array(),
																			'product_name' 						=> array(),
																			'service_code' 						=> array(),
																			'service_name' 						=> array(),
																			'speed'						=> array(),
																			'uom_desc'				=> array(),
																			'priority_desc'		=> array(),
																			'description' 	 	=> array(),
																			'sys_created_date'=> array('format'=>'datetime'),
																),
												'summary'	=> array(),
										)
									);
									break;
				case 'quotation':
									$pagerID = PAGER::P2;
									$params['FORMS'] = $this->CI->negotiation_model->getForm('form_search_quotation',$params);
									$myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
									$strSearch = $myForm->renderSearchDataTables();
									$table = new koje_datatables($myForm->id);

									$str .= $table->renderDataTable(
										array(
											'id' 		=> $pagerID,
											'search'=> $strSearch,
											'title' => "Quotation",
											'primary_key' => 't_quotation_id',
											"query" => array(	 "from" 	=> "FROM v_quotation a",
																				 "where"  => "WHERE t_prospect_id=$id",
																				 "params" => array(),
																),
											'order' => array(
																	PAGER::COL1=>'asc',
																),
											'toolbarButton' => array(
																	'insert'  => array(	'title' => 'New Quotation', 'url' => $this->CI->koje_system->URLBuild(false,'quotation_insert',"t_prospect_id=$id")),
																),
											'columns' => array(
																		'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																															 'button'=> array(
																																						'edit' 			=> array(	'title' =>getLabel('BTN_EDIT'),
																																																	'url' => $this->CI->koje_system->URLBuild(false,'quotation_edit',"t_prospect_id=$id"),

																																																),
						 																																'request' 	=> array(	'title' => getLabel('BTN_REQ_APPROVAL'),
						 																																											'url' => $this->CI->koje_system->URLBuild(false,'quotation_request',"t_prospect_id=$id"),
																																																	'visible' => 'btn_req',
																																																),
																																						'cancel' 			=> array(	'title' => getLabel('BTN_CANCEL'),
																																																	'url' => $this->CI->koje_system->URLBuild(false,'quotation_cancel',"t_prospect_id=$id"),
																																																),
																																						'_DIVIDER_',
																																						'print' 	=> array(	'title' => getLabel('BTN_PRINT_PDF'),
																																																'url' => '#',
																																																'attr' => 'onclick=\"return print(\':data\');\"',
																																																),
																																						'log' 			=> array(	'title' => getLabel('BTN_LOG'),
																																																	'url' => '#',
																																																	'attr' => 'onclick=\"return showLog(\':data\');\"',
																																																),
																																					),
																																	'btn_visibility' => array('request'),
																															),
																			'quotation_no' 					=> array(),
																			'name' 									=> array(),
																			'status_desc' 					=> array(),
																			'sum_amount' 						=> array('format'=>'number'),
																			'expired_date' 					=> array('format'=>'date'),
																			'approved_date' 	  		=> array('format'=>'date'),
																			'approved_notes'				=> array(),
																			'escalation_notes' 	  		=> array('format'=>'date'),
																			'sys_created_date' 	  	=> array('format'=>'datetime'),
																			'btn_visibility' 	  		=> array('visible'=> false),
																),
													'summary'	=> array(4),
												)
									);
								break;
				case 'deal' :
								$pagerID = PAGER::P2;
								$params['FORMS'] = $this->CI->negotiation_model->getForm('form_search_quotation',$params);
								$myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
								$strSearch = $myForm->renderSearchDataTables();
								$table = new koje_datatables($myForm->id);

								$approves = $this->CI->app_lib->getQuotationStatus('APPROVED');
								$deal = $this->CI->app_lib->getQuotationStatus('DEAL');
								$str .= $table->renderDataTable(
									array(
										'id' 		=> $pagerID,
										'search'=> $strSearch,
										'title' => "Quotation",
										'primary_key' => 't_quotation_id',
										"query" => array(	 "from" 	=> "FROM v_quotation a",
																			 "where"  => "WHERE t_prospect_id=$id and status in('$approves','$deal')",
																			 "params" => array(),
															),
										'order' => array(
																PAGER::COL1=>'asc',
															),
										'toolbarButton' => array(
																'negotiation_deal'  => array(	'title' => getLabel('BTN_DEAL'), 'url' => $this->CI->koje_system->URLBuild(false,'negotiation_deal',"t_prospect_id=$id")),
															),
										'columns' => array(
																	'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																														 'button'=> array(
																																					'print' 	=> array(	'title' => getLabel('BTN_PRINT_PDF'),
																																															'url' => '#',
																																															'attr' => 'onclick=\"return print(\':data\');\"',
																																															),
																																					'log' 			=> array(	'title' => getLabel('BTN_LOG'),
																																																'url' => '#',
																																																'attr' => 'onclick=\"return showLog(\':data\');\"',
																																															),
																																				),
																														),
																		'quotation_no' 					=> array(),
																		'name' 									=> array(),
																		'status_desc' 					=> array(),
																		'sum_amount' 						=> array('format'=>'number'),
																		'expired_date' 					=> array('format'=>'date'),
																		'approved_date' 	  		=> array('format'=>'date'),
																		'approved_notes'				=> array(),
																		'escalation_notes'				=> array(),
																		'sys_created_date' 	  	=> array('format'=>'datetime'),
															),
												'summary'	=> array(4),
											)
								);
							break;
		      case 'negotiation':
							$str .= $this->CI->app_lib->getListNegotiation($id,true);
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
					case 'document':
							$str .= $this->CI->app_lib->getOportunityDoc(array('t_prospect_id'=>$id));
							break;
			}
			//$str .= $this->CI->app_lib->getOportunityDoc1($id);
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
