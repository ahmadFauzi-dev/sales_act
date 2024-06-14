<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


Class koje_db{
	function __construct(){
		if (!class_exists('ADONewConnection') )
			require_once(APPPATH.'third_party/adodb/adodb.inc.php');
			require_once(APPPATH.'third_party/adodb/adodb-errorhandler.inc.php');
			$obj =& get_instance();
			$this->_init_adodb_library($obj);
	}

	function _init_adodb_library(&$ci) {
		global $active_group, $db, $ADODB_FORCE_TYPE;
		$ADODB_FORCE_TYPE = ADODB_FORCE_NULL;
		if (!isset($dsn)) {
			$dsn = $db[$active_group]['dbdriver_adodb'].'://'.$db[$active_group]['username']
			.':'.$db[$active_group]['password'].'@'.$db[$active_group]['hostname'].
			(isset($db[$active_group]['port'])?":".$db[$active_group]['port']:"")
			.'/'.$db[$active_group]['database'];
		}

			$dsn_billing = $db['billing']['dbdriver_adodb'].'://'.$db['billing']['username']
			.':'.$db['billing']['password'].'@'.$db['billing']['hostname'].
			(isset($db['billing']['port'])?":".$db['billing']['port']:"")
			.'/'.$db['billing']['database'];

		$ci->adodb = ADONewConnection($dsn);
		$ci->adodb->firstrows=false;
		$ci->adodb->setFetchMode(ADODB_FETCH_BOTH);

		$ci->adodb_billing = ADONewConnection($dsn_billing);
		$ci->adodb_billing->firstrows=false;
		$ci->adodb_billing->setFetchMode(ADODB_FETCH_BOTH);
	}
}
