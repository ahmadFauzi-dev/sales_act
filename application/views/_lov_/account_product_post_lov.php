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
  $t_account_id = $this->CI->input->get_post('t_account_id');
  if(!$t_account_id) {
    print('<br/><br/><br/><br/><center><h2>Pilih Account terlebih dahulu</h2></center>');
    return;
  }
  $table = new koje_datatables(); 
  $str = $table->renderDataTable(
		array(
      'db'    => 'billing',
			'id' 		=> $pagerID,
			'search'=> '&nbsp;',
			'title' => "Account Product List",
			'primary_key' => 't_account_id',
			"query" => array(	 "from" 	=> "FROM v_account_product_bk a",
												 "where"  => "WHERE d_provider_no=$provider and d_status_desc='ACTIVE' and t_account_id=".$t_account_id,
												 "params" => array(),
								),
			'order' => array(
												PAGER::COL5=>'asc',
											),
      'toolbarButton' => array(),
			'columns' => array(
                      '_KOJE_PICK_'		          => array(),
											'account_no' 							=> array(),
                      'company_name' 				    => array(),
                      'product_name' 				    => array(), 
                      'd_status_desc' 				  => array(), 
                      'subscriber_no' 				  => array(), 
                      't_account_id' 					  => array(),
                      't_account_product_id' 		=> array(),
					  'speed' 		=> array(),
					  'd_uom' 		=> array(),
					  'contract_length' 		=> array(),
					  'agreed_sla' 		=> array(),
					  'contract_length' 		=> array(),
					  'd_trial_status' 		=> array(),
					  'trial_days' 		=> array(),
					  'delivery_days' 		=> array(),
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