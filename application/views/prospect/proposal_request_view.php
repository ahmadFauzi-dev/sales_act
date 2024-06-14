<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new proposal_request_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class proposal_request_view {
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
			$t_stage_id = $this->CI->app_lib->getIDStage('PROPOSAL');
			$status = $this->CI->app_lib->getProspectStatus('ACTIVE');
			$table = new koje_datatables($myForm->id);
			
			//tambah by VT			
			if ($loginID == 1) {
				$query = array(	 "from" 	=> "FROM v_prospect_current a",
							 "where"  => "WHERE status='$status' and t_stage_id=$t_stage_id and presales_status = 'PRESALES-01'",
							 "params" => array()
							 );
			} else {
				$query = array(	 "from" 	=> "FROM v_prospect_current a",
							 "where"  => "WHERE sales_id=$loginID and status='$status' and t_stage_id=$t_stage_id and presales_status = 'PRESALES-01'",
							 "params" => array()
							 );
			}
			//
			
			$str = $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'search'=> $strSearch,
					'title' => 'Proposal',
					'primary_key' => 't_prospect_id',
					"query" => $query,
					'order' => array(
														PAGER::COL1=>'asc',
													),
					'toolbarButton' => array(
													),
													'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																									 'button'=> array(
																													 'detail' 	=> array(	'title' => getLabel('BTN_DETAIL'),
																																								 'url' => $this->CI->koje_system->URLBuild(false,'detail',''),
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
													'prospect_type_desc' 		=> array(),
													'prospect_start_date'   => array('format'=>'date'),
													'prospect_name'					=> array(),
													'presales_status_desc'					=> array(),
													'duration_days'					=> array(),
													'target_amount'					=> array('format'=>'number'),
													'probability' 		    	=> array('format'=>'percentage'),
													'survey_info'						=> array(),
													'prospect_no' 					=> array(),
													'sys_created_date' 			=> array('format'=>'datetime'),
										),
										'summary' => array(),
				)
			);

			print $str;
		}

		function detail($params=false)
		{
			$id = $this->CI->input->get_post('t_prospect_id');
			$act = getValueNull($this->CI->input->get_post('act'),'');

			$btnLeft   = array('proposal','presentation','survey');
			$btnCenter = array('telemarketing','stage');
			$btnRight  = array('next_stage'=>array('url' => 'next'));
			$str 			 = $this->CI->app_lib->prospectlistButton($id,$act,$btnLeft, $btnCenter, $btnRight);

			$pagerID = PAGER::P1;
			$this->CI->load->model('activity/activity_model');
			$params['FORMS'] = $this->CI->activity_model->getForm('form_search',$params);

			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$loginID = $this->CI->koje_system->getLoginID();
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);

			switch ($act) {
				case 'survey':
									$str .= $table->renderDataTable(
										array(
											'id' 		=> $pagerID,
											'search'=> '&nbsp;',
											'title' => 'Survey',
											'primary_key' => 't_survey_id',
											"query" => array(	 "from" 	=> "FROM v_survey",
																				 "where"  => "WHERE t_prospect_id=$id",
																				 "params" => array()
																),
											'order' => array(
																				PAGER::COL1=>'desc',
																			),
											'toolbarButton' => array(
																					'insert'  => array(	'title' => 'New Survey', 'url' => $this->CI->koje_system->URLBuild(false,'survey_insert',"t_prospect_id=$id")),
																			),
											'columns' => array(
																				'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																																	 'button'=> array(
																																								'edit'   	=> array(	'title' => getLabel('BTN_EDIT'),
																																																		'url' => $this->CI->koje_system->URLBuild(false,'survey_edit',"t_prospect_id=$id"),
																																																	),
																																							)
																																	),
																				'title' 					=> array(),
																				'activity_date' 		=> array('format'=>'date'),
																				'target_date' 		=> array('format'=>'date'),
																				'description' 		=> array(),
																				'type_desc' 					=> array(),
																				'service_lists_info'		=> array(),
																				'status_desc' 					=> array(),
																				'survey_date' 		=> array('format'=>'date'),
																				'survey_notes' 		=> array(),
																				'attachment_1_info'	=> array('format'=>'file'),
																				'attachment_2_info' => array('format'=>'file'),
																				'attachment_3_info' => array('format'=>'file'),
																				'sys_created_date'=> array('format'=>'datetime'),
																)
										)
									);
								break;
					case 'proposal':
						$str .= $table->renderDataTable(
							array(
								'id' 		=> $pagerID,
								'search'=> '&nbsp;',
								'title' => "Proposal",
								'primary_key' => 't_proposal_id',
								"query" => array(	 "from" 	=> "FROM v_proposal",
																	 "where"  => "WHERE t_prospect_id=$id",
																	 "params" => array()
													),
								'order' => array(
																	PAGER::COL1=>'desc',
																),
								'toolbarButton' => array(
																		'insert'  => array(	'title' => 'New Proposal', 'url' => $this->CI->koje_system->URLBuild(false,'proposal_insert',"t_prospect_id=$id")),
																),
								'columns' => array(
																'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																													 'button'=> array(
																																				'edit'   	=> array(	'title' => getLabel('BTN_EDIT'),
																																														'url' => $this->CI->koje_system->URLBuild(false,'proposal_edit',"t_prospect_id=$id"),
																																													),
																																			)
																													),
																'title' 					=> array(),
																'activity_date' 		=> array('format'=>'date'),
																'service_lists_desc' 		=> array(),
																'description' 		=> array(),
																'attachment_1_info'	=> array('format'=>'file'),
																'attachment_2_info' => array('format'=>'file'),
																'attachment_3_info' => array('format'=>'file'),
																'sys_created_date'=> array('format'=>'datetime'),
													)
							)
						);
					break;
					case 'presentation':
						$str .= $table->renderDataTable(
							array(
								'id' 		=> $pagerID,
								'search'=> '&nbsp;',
								'title' => 'Presentation',
								'primary_key' => 't_presentation_id',
								"query" => array(	 "from" 	=> "FROM v_presentation",
																	 "where"  => "WHERE t_prospect_id=$id",
																	 "params" => array()
													),
								'order' => array(
																	PAGER::COL1=>'desc',
																),
								'toolbarButton' => array(
																		'insert'  => array(	'title' => 'New Presentation', 'url' => $this->CI->koje_system->URLBuild(false,'presentation_insert',"t_prospect_id=$id")),
																),
								'columns' => array(
																'_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
																													 'button'=> array(
																																				'edit'   	=> array(	'title' => getLabel('BTN_EDIT'),
																																														'url' => $this->CI->koje_system->URLBuild(false,'presentation_edit',"t_prospect_id=$id"),
																																													),
																																			)
																													),
																'activity_date'=> array('format'=>'datetime'),
																'title' 						=> array(),
																'description' 			=> array(),
																'result_info' 			=> array(),
																'sys_created_date'	=> array('format'=>'datetime'),
																'attachment_1_info'	=> array('format'=>'file'),
																'attachment_2_info' => array('format'=>'file'),
																'attachment_3_info' => array('format'=>'file'),
													)
							)
						);
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
	?>
<script>
	$(document).ready(function(){
		showHide('#conn_1');showHide('#other_1');showHide('#mgsrv_1');showHide('#dc_1');showHide('#vicon_1');
		$("#conn_1,#other_1,#mgsrv_1,#dc_1,#vicon_1").change(function() {
				id = $(this).attr('id');
				showHide('#'+id);
		});
		function showHide(id)
		{
			if($(id).prop('checked')) {
					$(id.replace('_1','')).show();
			} else {
					$(id.replace('_1','')).hide();
			}
		}
	})
</script>
