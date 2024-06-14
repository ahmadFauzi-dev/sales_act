<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class profile_model {
	var $forms;
	var $tableName  = 'sys_operator';
	var $primaryKey = 'sys_operator_id';

	function __construct() {
		$CI =& get_instance();

		$this->forms = array(
			"index" => array(
						 'title' => 'Edit Operator',
						 'item'	=>
							array(
								array(
										"field" => "username",
										"type"	=> "text",
										"label"	=> "username",
										"readonly" => true,
										"rules" => "trim|required|max_length[500]"
									),
								array(	"field" => "password",
										"type"	=> "password",
										"label" => "Password",
										'size'	=> 50,
										"rules" => "trim|min_length[3]|max_length[100]|matches[passconf]"
									),
								array(	"field" => "passconf",
										"type"	=> "password",
										"label" => "Confirm Password",
										'size'	=> 50,
										"rules" => "trim|min_length[3]|max_length[100]|matches[password]"
									),
								array(
										"field" => "name",
										"type"	=> "text",
										"label"	=> "Nama Lengkap",
										"rules" => "trim|required|max_length[500]"
									),
								array(
										"field" => "email",
										"type"	=> "text",
										"label"	=> "Email",
										"rules" => "trim|max_length[500]"
									),
								array(
										"field" => "file_id",
										"type"	=> "file",
										"label"	=> "Photo",
										"rules" => "trim"
									),
								array(
										"field" => "submit",
										"type"	=> "submit",
										"label"	=> "Simpan"
					 				),
								array(
										"field" => "reset",
										"type" 	=> "reset",
										"label" => "Reset",
									),
								array(
										"field" => "cancel",
										"type" 	=> "cancel",
										"label" => "Cancel",
									),
							),
							'display'=>
								array(
									array("username"),
									array("name"),
									array('password'),
									array('passconf'),
									array('email'),
									array("file_id"),
									"_BUTTON_"
								),
						'result' =>
							array(
								"username", "name", "email"
							)
					),
		);
	}
	function _removeImage($fn) {
		array_map('unlink', glob($fn.'.*'));
	}
	function index($params=false) {
		$CI =& get_instance();
		$input = $params['INPUT'];
		$input['sys_operator_id'] = $CI->utils->getLoginID();

		if($_FILES['file_id']['name']) {
			$this->_removeImage(KOJE_CONFIG::folder_picture.$input['sys_operator_id']);
		}
		if(isset($input['password']) && $input['password'] == "") {
			unset($input['password']);
		}
		$CI->utils->uploadInputFile('file_id', KOJE_CONFIG::folder_picture, $input['sys_operator_id']);
		$rs = $CI->adodb->AutoExecute($this->tableName, $input, "UPDATE", $this->primaryKey."=".$input[$this->primaryKey]);

		if(isset($input['password']) && $input['password'] !="") {
			$CI->adodb->execute("update sys_operator set password=crypt(?,gen_salt(sys_operator_id)) where sys_operator_id=?", array($input['password'], $input['sys_operator_id']));
		}

		if ($rs===false) {
			$result[1]='01';
			$result[2]='FAILED [' . $CI->adodb->ErrorMsg().']';
		}
		else {
			$result[1]='00';
			$result[2]='BERHASIL MENGUBAH DATA OPERATOR.';
		}
		return $result;
	}
}
