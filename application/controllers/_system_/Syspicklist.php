<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class syspicklist extends KOJE_Controller { 
  function __construct() {
		parent::__construct();
		$this->CI =& get_instance(); 
  }

	function index() { 
		list($salt,$lov) = explode(":",$this->CI->koje_system->base64Decode($_REQUEST['_lov']));		
		$assign = "";
		foreach ($_REQUEST as $key => $val) {
			$assign .= $key!=='_lov' ? "&". $key."=".$val : "";
    }
		if ($salt==$_SESSION['_ACCESS_KEY']) {
			print $this->koje_system->load_asset();
      $this->load->template("_lov_/$lov", $assign, true, 'none');
		}
		else
			print "<span class='invalid'>ACCESS_NOT_ALLOWED</span>";		
	}
}
?>