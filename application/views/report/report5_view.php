<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new report5_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class report5_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false) {
				$loginID = $this->CI->koje_system->getLoginID();
				$where ="and ($loginID=1 or $loginID=54 or c.sales_id=$loginID or utils_is_leader_from($loginID,c.sales_id)='Y')";
				$json = $this->CI->app_lib->kanbanGetProspect($where);
				$str =  $this->CI->app_lib->kanbanShow($json);
				print $str;
		}
	}
	?>
