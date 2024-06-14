<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class api_getdata extends KOJE_Controller {
	protected $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
	}
  function index() {
		$key = $this->CI->input->get_post('k');
		$t = $this->CI->input->get_post('t');
		$q = base64_decode($this->CI->input->get_post('q'));
		if($key!=='My5ECrEtKey') {
			return;
		}
		switch ($t)
		{
			case 'ASSOC' :
				$data = $this->CI->adodb->getAssoc($q);
				break;
			case 'ROW' :
					$data = $this->CI->adodb->getRows($q);
				break;
		}
		print json_encode($data);
	}
}
