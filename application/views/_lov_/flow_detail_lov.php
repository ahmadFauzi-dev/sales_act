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
			'primary_key' => 'r_flow_id',
			'title' => "Detail Workflow",			
			"query" => array(	 "from" 	=> "FROM r_flow a",
												 "where"  => "WHERE 1=1 and parent_id=$r_flow_id",
												 "params" => array(),
								),
			'order' => array(
												PAGER::COL0=>'asc',
											),
			'toolbarButton' => array(
					'insert'  => array('title' => 'New Record ',  'url' => $this->CI->koje_system->URLBuild(false,'flowdetail_insert')),
													),
					'columns' => array(
					'_KOJE_BUTTON_'		=> array('title' => '',
	'button'=> array(
																																'edit'   => array('title' => 'Edit',   'url' => $this->CI->koje_system->URLBuild(false,'edit','')),
																																'delete' => array('title' => 'Delete', 'url' => $this->CI->koje_system->URLBuild(false,'delete',''))			
			)),
									
								'priority' => array(),
								'discount' => array(),
								'description' 	=> array(),
								'val'		=> array()
								
			// 'summary'	=> array(),
		)
	));

	print "<div class='content'>$str</div>";
	print "<script>function passBack(id,row) {var e;";
  foreach($pickList as $openerField => $pickCol) {
    print "e=window.opener.document.getElementById('$openerField');$(e).val(row['$pickCol']);$(e).change();$(e).removeClass('invalid');";
  }
  print "window.close();}</script>";