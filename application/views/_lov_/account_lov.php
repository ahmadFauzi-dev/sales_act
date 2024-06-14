<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
  $CI =& get_instance();

  $pagerID = PAGER::P1;
  $loginID = $this->CI->koje_system->getLoginID();
  $provider = $this->CI->app_lib->getProviderNo();
  $pickList = $this->CI->koje_system->assignPickLOV($CI->input->get('assign'));
  $table = new koje_datatables();
  $str = $table->renderDataTable(
		array(
      'db'    => 'billing',
			'id' 		=> $pagerID,
			'search'=> '&nbsp;',
			'title' => "Account List",
			'primary_key' => 't_account_id',
			"query" => array(	 "from" 	=> "FROM v_account a",
												 "where"  => "WHERE d_provider_no=$provider",
												 "params" => array(),
								),
			'order' => array(
												PAGER::COL3=>'asc',
											),
      'toolbarButton' => array(),
			'columns' => array(
                      '_KOJE_PICK_'		          => array(),
                      'provider' 					      => array(),
											'sales_name' 							=> array(),
											'area_name' 							=> array(),
											'sub_area_name' 					=> array(),
											'account_no' 							=> array(),
                      'company_name' 				    => array(),
                      'npwp_company' 				    => array(),  
											'city_name' 							=> array(),
                      'd_segmentation_desc'     => array(), 
                      'd_customer_group_desc'   => array(),  
                      't_account_id' 					  => array(),
								),
      'summary'	=> array(),
      'pickList' => $pickList,
		)
	);
  print "<div class='content'>$str</div>";
  print "<script>function passBack(id,row) {var e;";
  foreach($pickList as $openerField => $pickCol) {
    print "e=window.opener.document.getElementById('$openerField');$(e).val(row['$pickCol']);$(e).change();$(e).removeClass('invalid');";
  }
  print "window.close();}</script>";