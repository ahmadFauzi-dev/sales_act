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
	$table = new koje_datatables();
	$str = $table->renderDataTable(
		array(
			'id' 		=> $pagerID,
			'search'=> '&nbsp;',
			'title' => "Quotation Log Activity",
			'primary_key' => 't_quotation_log_id',
			"query" => array(	 "from" 	=> "FROM v_quotation_log a",
												 "where"  => "WHERE t_quotation_id=$t_quotation_id",
												 "params" => array(),
								),
			'order' => array(
												PAGER::COL0=>'desc',
											),
			'toolbarButton' => array(),
			'columns' => array(
											't_quotation_log_id' 					 => array(),
											'log_type' 					           => array(),
											'notes' 									     => array(),
                      'sys_created_name' 				     => array(),
											'sys_created_date' 	  	       => array('format'=>'datetime'),
								),
			'summary'	=> array(),
		)
	);

	print "<div class='content'>$str</div>";
