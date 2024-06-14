<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


class Koje_validation extends CI_Form_validation {
	var $error_avail = array();
	public function __construct() {
		parent::__construct();
	}
	public function form_error($id,$prefix='', $sufix='') {
		if (isset($this->_error_array[$id])) return $prefix.$this->_error_array[$id].$sufix;
		return false;
	}
	public function form_error_count() {
		return count($this->_error_array);
	}
	public function form_error_remove($id) {
		unset($this->error_avail[$id]);
	}
	public function form_errors_avail() {
		return $this->error_avail;
	}
	public function form_error_copy() {
		$this->error_avail = $this->_error_array;
	}
/******************************* TAMBAHKAN FUNCTION CALLBACK untuk VALIDATION ************************************************/
	public function valid_zip($string) {
		if(length($string) < 5) {
			$this->set_message('valid_zip', 'Invalid ZIP Code');
			return FALSE;
		}
		return TRUE;
	}
}
