<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

// class showFile extends KOJE_Controller {
	// function __construct() {
		// parent::__construct();
	// }
	// function index() {
		// $file_info = explode(':',$this->input->get_post("file_info"));
		// if(isset($file_info[2])){
				// $content = file_get_contents($this->upload->upload_path.$file_info[1]);
				// header('Content-type: auto');
				// header('Content-disposition: filename="'.$file_info[2].'"');
				// print $content;
			// }
		// die;
	// }
// }
class showFile extends KOJE_Controller {
	protected $CI;
	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
	}
	function index() {
    $t_file_id = $this->input->get_post("t_file_id");
    $row = $this->CI->adodb->getRow("select * from t_file where t_file_id=?", array($t_file_id)); 
		if($row){ 
        $content = $row['content'];
        if($row['file_ext']=='application/pdf') {
          header("Content-type: application/pdf");
					header('Content-disposition: inline; filename="'.$row['name'].'"');
        }
        else {
          header('Content-type: '. $row['file_ext']);
					header('Content-disposition: attachment; filename="'.$row['file_name'].'"');
        }
				print $content;
      }
    else {
      print('Invalid File');
    } 
	}
}
?>
