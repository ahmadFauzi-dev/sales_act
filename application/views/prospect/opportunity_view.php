<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new opportunity_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class opportunity_view {
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
			$t_stage_id = $this->CI->app_lib->getIDStage('OPPORTUNITY');
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
					'title' => 'Opportunity List',
					'primary_key' => 't_prospect_id',
					"query" => $query,
					'order' => array(
														PAGER::COL1=>'desc',
													),
					'toolbarButton' => array(
														'canvassing_insert' => array('title' => 'New Canvassing', 'url' => $this->CI->koje_system->URLBuild(false,'canvassing_insert','')),
														'canvassing_upload' => array('title' => 'Upload Canvassing', 'url' => $this->CI->koje_system->URLBuild(false,'canvassing_upload','')),
														'profiling_insert' => array('title' => 'New Profile', 'url' => $this->CI->koje_system->URLBuild(false,'profiling_insert','')),
														'manage_customer' => array('title' => 'Manage Customer', 'url' => $this->CI->koje_system->URLBuild(false,'manage_customer','')),
													),
					'columns' => array(
												'_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
																									 'button'=> array(
																													'detail'   	=> array(	'title' => getLabel('BTN_TELEMARKETING'),
																																								'url' => $this->CI->koje_system->URLBuild(false,'detail','act=telemarketing'),
																																							),
																													'next' 	=> array(	'title' => getLabel('BTN_NEXT_STAGE'),
																																								'url' => $this->CI->koje_system->URLBuild(false,'next',''),
																																							),
																													'_DIVIDER_',
																													'edit' 			=> array(	'title' => getLabel('BTN_EDIT_PROFILE'),
																																								'url' => $this->CI->koje_system->URLBuild(false,'profiling_edit',''),
																																							),
																													'cancel' 			=> array(	'title' => getLabel('BTN_CANCEL'),
																																								'url' => $this->CI->koje_system->URLBuild(false,'cancel',''),
																																							),
																												)
																									),
													'prospect_name'					=> array(),
													'company_name'					=> array(),
													'cp_1_name'					=> array('title'=>'Contact Peson'),
													'ro_name'					=> array('title'=>'RO'),
													'prospect_type_desc' 		=> array(),
													'prospect_start_date'   => array('format'=>'date'),
													'duration_days'					=> array(),
													'target_amount'					=> array('format'=>'number'),
													'probability' 		    	=> array('format'=>'percentage'),
													'telemarketing_info'		=> array(),
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

			$btnLeft   = array('telemarketing');
			$btnCenter = array('requirement','stage');
			$btnRight  = array('next_stage'=>array('url' => 'next'));
			$str = $this->CI->app_lib->prospectlistButton($id,$act,$btnLeft, $btnCenter, $btnRight);

			switch ($act) {
				case 'telemarketing':
						$pagerID = PAGER::P1;
				    $this->CI->load->model('prospect/prospect_model');
				    $params['FORMS'] = $this->CI->prospect_model->getForm('form_search_telemarketing',array());
				    $myForm = new KOJE_Form('MY_SEARCH');
				    $myForm->linkSearchForm($pagerID);
				    $myForm->setTitle($params['FORMS']['title']);
				    $myForm->setItem($params['FORMS']['item']);
				    $myForm->setDisplay($params['FORMS']['display']);
				    $loginID = $this->CI->koje_system->getLoginID();
				    $strSearch = $myForm->renderSearchDataTables();
				    $table = new koje_datatables($myForm->id);
				    $str .= $table->renderDataTable(
				      array(
				        'id' 		=> $pagerID,
				        'search'=> $strSearch,
				        'title' => "Telemarketing List",
				        'primary_key' => 't_telemarketing_id',
				        "query" => array(	 "from" 	=> "FROM v_telemarketing",
				                           "where"  => "WHERE t_prospect_id=$id",
				                           "params" => array()
				                  ),
				        'order' => array(
				                          PAGER::COL4=>'desc',
				                        ),
				        'toolbarButton' => array(
				                          'insert'  => array(	'title' => 'New Telemarketing', 'url' => $this->CI->koje_system->URLBuild(false,'telemarketing_insert',"t_prospect_id=$id")),
				                        ),
				        'columns' => array(
				                      '_KOJE_BUTTON_'		=> array('title' => getLabel('LBL_ADMIN'),
				                                                 'button'=> array(
				                                                              'edit'   	=> array(	'title' => getLabel('BTN_EDIT'),
				                                                                                    'url' => $this->CI->koje_system->URLBuild(false,'telemarketing_edit',"t_prospect_id=$id"),
				                                                                                  ),
				                                                            )
				                                                ),
				                        'activity_date' 		=> array('format'=>'datetime'),
				                        'type_desc' 		=> array(),
				                        'description' 	=> array(),
				                        'result'				=> array(),
				                        'sys_created_date' 		=> array('format'=>'datetime')
				                  )
				      )
				    );
						break;
				case 'requirement':
					$str .= $this->CI->app_lib->getListRequirement($id,true);
				case 'stage':
					$str .= $this->CI->app_lib->getListStage($id,true);
				break;
			}
			$str .= $this->CI->app_lib->getInfoLead($id);
			print $str;
		}
	
	function canvassing_insert($params=false)
    {
      $id = $this->CI->input->get_post('t_account_product_id');
      $act = getValueNull($this->CI->input->get_post('act'),'product');
      $str = $this->_btn($id,'BTN_'.strtoupper($act));
      switch ($act) {
        case 'canvasing':
            $str .= $this->_canvasing($id);
            break;      
        case 'document':
            $str .= $this->_document($id);
            break;
        }
      print $str;
    }
  
    function _btn($id,$act) {
        $arr = array(
                    'BTN_CANVASING'     => "manage?act=canvasing&t_account_product_id=$id",
                    'BTN_DOCUMENT'    => "manage?act=document&t_account_product_id=$id"
                  );
        $str = '';
        foreach ($arr as $key => $val) {
          if ($key==$act) 
            $str .= HTML_link_active($val, getLabel($key)); 
          else
            $str .= HTML_link($val, getLabel($key));
        } 
        return $str;
    }
	
	function _document($acid) {
        $table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTable(
                  array(
                    'id' 		=> PAGER::P2,
                    'search'=> '',
                    'title' => "Document List",
                    'primary_key' => 't_account_doc_id',
                    "query" => array( "from" 	=> "FROM v_account_doc",
                                      "where"  => "WHERE t_account_id=$acid",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => array('docUpload' 	=> array(	'title' => getLabel('BTN_UPLOAD_DOC'),'url' => $this->CI->koje_system->URLBuild(false,'insertDoc',"t_account_id=$acid"))),
                    'columns' => array(
                                    '_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
                                                            'button'=> array(
                                                            'editDoc' 	=> array(	'title' => getLabel('BTN_EDIT'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'editDoc',"t_account_id=$acid"),
                                                                            ),
                                                            'deleteDoc' 	=> array(	'title' => getLabel('BTN_DELETE'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'deleteDoc',"t_account_id=$acid"),
                                                                          ),
                                                      )
                                                  ),

                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'status_desc' => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
    }
}
