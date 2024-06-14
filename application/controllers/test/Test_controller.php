<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class Test_controller extends CI_Controller {

	function __construct() {
		parent::__construct();

	}
	function index()
	{
		echo "<pre>";
		var_dump( $this->ion_auth->hash_password("pgncom.co.id"));
		echo "</pre>";
	}
}
