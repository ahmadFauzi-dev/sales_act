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
  $pickList = $this->CI->koje_system->assignPickLOV($CI->input->get('assign'));
  $table = new koje_datatables();
  $str = $table->renderDataTable(
		array(
			'id' 		=> $pagerID,
			'search'=> '&nbsp;',
			'title' => "Account List",
			'primary_key' => 't_account_id',
			"query" => array(	 "from" 	=> "FROM v_account a",
												 "where"  => "WHERE account_status='CUST-02'",
												 "params" => array(),
								),
			'order' => array(
												PAGER::COL3=>'asc',
											),
      'toolbarButton' => array(),
			'columns' => array(
                      '_KOJE_PICK_' => array(),
									'company_name' => array(),
									'company_phone' => array(),									
									'source' => array(),
									'source_desc' => array(),									
									'industry_desc' => array(),
									'r_ro_id' => array(),
									'region_desc' => array(),
									'company_address_info' => array(),
									'sales_name' => array(),
									'sys_created_date' => array('format'=>'datetime'),
									't_account_id' => array(),
									'company_npwp' => array(),
									'industry' => array(),
									
									'address_zip' => array(),
									'address_city' => array(),
									'address_city_id' => array(),
									'address_street' => array(),
									'address_province' => array(),
									'address_area' => array(),
    								
									//CP1
									'cp_1_department' => array(),
									'cp_1_info' => array(),
									'cp_1_name' => array(),
									'cp_1_mobile' => array(),
									'cp_1_email' => array(),
									'cp_1_salute' => array(),
									'cp_1_salute_desc' => array(),
									//CP2
									'cp_2_department' => array(),
									'cp_2_info' => array(),
									'cp_2_name' => array(),
									'cp_2_mobile' => array(),
									'cp_2_email' => array(),
									'cp_2_salute' => array(),
									'cp_2_salute_desc' => array(),
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