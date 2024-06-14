<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new leads_view();
	if (!isset($VIEW)) $VIEW = $this->router->method;
	$myView->$VIEW($VARS);

	class leads_view {
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
			//var_dump($myForm);
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$loginID = $this->CI->koje_system->getLoginID();
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);
			
			//tambah by VT			
			if ($loginID == 1) {
				$query = array(	 "from" 	=> "FROM v_account a",
							 "where"  => "WHERE a.account_status='CUST-01'",
							 'order' => array(
																	PAGER::COL5=>'desc',
																),
							 "params" => array()
							 );
			} else {
				$query = array(	 "from" 	=> "FROM v_account a",
							 "where"  => "WHERE sales_id=$loginID and a.account_status='CUST-01'",
							 "params" => array()
							 );
			}
			//
			
			$str = $table->renderDataTable(
				array(
					'id' 		=> $pagerID,
					'search'=> $strSearch,
					'title' => 'Leads',
					'primary_key' => 't_account_id',
					"query" => $query,
					'order' => array(PAGER::COL1=>'asc'),
					'toolbarButton' => array('leads_insert' => array('title' => 'New Leads', 'url' => $this->CI->koje_system->URLBuild(false,'leads_insert','')),
													),
					'columns' => array('_KOJE_BUTTON_' => array('title' => TITLE_MSG::LABEL_ADMIN,
																									 'button'=> array(
																													 'detail' => array(	'title' => getLabel('BTN_DETAIL'),
																																								 'url' => $this->CI->koje_system->URLBuild(false,'detail',''),
																																							 ),
 																													 'edit' => array( 'title' => getLabel('BTN_EDIT_PROFILE'),
 																																								 'url' => $this->CI->koje_system->URLBuild(false,'edit',''),
 																																							 ),
																													'level_up' => array(	'title' => getLabel('BTN_LEVEL_UP'),																																								'url' => $this->CI->koje_system->URLBuild(false,'level_up',''),
																																							),
																												)
																									),
													'company_name' => array(),
													'cp_1_name' => array(),
													'prospect_desc' => array(),
													'industry_desc' => array(),
													'region_desc' => array(),
													'sales_name' => array(),
													'sys_created_date' => array('format'=>'datetime'),
										),
										'summary' => array(),
				)
			);

			print $str;
		}

		function detail($params=false)
		{
			$id = $this->CI->input->get_post('t_account_id');
			
			$pagerID = PAGER::P1;
			$this->CI->load->model('customer/leads_model');
			$params['FORMS'] = $this->CI->leads_model->getForm('form_search',$params);
			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$loginID = $this->CI->koje_system->getLoginID();
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);
			print $this->CI->app_lib->getInfoLeads($id);
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
