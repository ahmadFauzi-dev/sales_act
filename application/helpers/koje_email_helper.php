<?php

/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

	require APPPATH.'third_party/PHPMailer/src/Exception.php';
	require APPPATH.'third_party/PHPMailer/src/PHPMailer.php';
	require APPPATH.'third_party/PHPMailer/src/SMTP.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

class koje_email {
	var $mail;
	var $data_init = array();
	function __construct() {
		$this->CI =& get_instance();
		$this->init();
	}
	function init() {
		$this->mail = new PHPMailer;
		$this->mail->isSMTP();
		$this->mail->Host = $this->CI->config->item('smtp_host');
		$this->mail->Port = $this->CI->config->item('smtp_port');
		$this->mail->SMTPAuth = $this->CI->config->item('smtp_auth');
		$this->mail->Username = $this->CI->config->item('smtp_user');
		$this->mail->Password = $this->CI->config->item('smtp_pass');
		$this->mail->setFrom($this->CI->config->item('smtp_from_mail'), $this->CI->config->item('smtp_from_name'));
		$this->data_init['sys_date']					= date("d-M-Y");
		$this->data_init['sys_date_time']					= date("d-M-Y H:i:s");
		$this->data_init['X_TABLE_STYLE_X'] 	= 'style="background: #f9f9f5;font-family:Calibri,sans-serif;margin-left:0px; width:100%;"';
		$this->data_init['X_TITLE_STYLE_X'] 	= 'style="font-family:Calibri,sans-serif;color:#ffffff;background-color:#133959;padding-left:34px;padding-right:34px;padding-top:10px;width:100%;text-align:right;"';
		$this->data_init['X_TH_STYLE_X']     = 'style="border-bottom: solid 1px #e9e9e9; background: #f9f9f5;font-weight:bold;font-family:Calibri,sans-serif;"';
		$this->data_init['X_TD_STYLE_X']     = 'style="border-bottom: solid 1px #e9e9e9; background: #f9f9f5;font-family:Calibri,sans-serif; "';
		$this->data_init['X_FOOTER_STYLE_X'] = 'style="font-size:11px;font-family:Calibri,sans-serif;color:#ffffff;background-color:#133959;padding-left:34px;padding-right:34px;padding-top:1px;width:100%;text-align:right;font-style:italic;"';
	}
	function clearAddress() {
		$this->mail->ClearAddresses();
		$this->mail->ClearCCs();
		$this->mail->ClearBCCs();
	}
	function Subject($txt) {
		$this->mail->Subject = $txt;
	}
	function addAddress($txt) {
		$list = explode(";",$txt);
		foreach($list as $key) {
			$this->mail->addAddress(trim($key));
		}
	}
	function msgHTML($txt) {
		$this->mail->msgHTML($txt);
	}
	function addAttachment($txt) {
		$this->mail->addAttachment($txt);
	}
	function send() {
		$result = $this->mail->send();
		return $result;
	}
	function replaceTag($template) {
		$data = array_merge($this->data_init,$this->data);
		$str = $template;
		foreach ($data as $key => $value) {
			$str = str_replace('{'. $key.'}', $value, $str);
		}
		return $str;
	}
}
?>
