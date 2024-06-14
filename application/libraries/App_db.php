<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  use PhpOffice\PhpSpreadsheet\IOFactory;

  class app_db {
    var $CI;
    function __construct() {
      $this->CI =& get_instance();
    }
  //------------------------------------------------DB TOOLS ----------------------------------------------------
    function generateResult($rs) {
      if ($rs===false) {
        $result[1]='01';
        $result[2]='FAILED [' . $this->CI->adodb->ErrorMsg().']';
      }
      else {
        $result[1]='00';
        $result[2]= 'DONE';
      }
      return $result;
    }

    function logActivity($action, $params=false, $result=false)
    {
  		$id = $this->CI->adodb->getOne("select reference_id from t_reference where val=? and group_reference='GLOBAL__LOG'", array($action));

  		if(!$id) {
  			$input['reference_id'] = $this->CI->adodb->genID();
  			$input['val'] = $action;
  			$input['group_reference'] = 'GLOBAL__LOG';
  			$input['description'] = $action;
  			$input['priority'] = 1;
  			$rs = $this->CI->adodb->AutoExecute('t_reference', $input, "INSERT");
  		}
      $par = array();
  		$par['action'] = $action;
  		$par['data']   = json_encode($params);
  		$par['create_by'] = $this->CI->koje_system->getLoginID();
  		$par['create_date'] = date("Y-m-d H:i:s");
  		$par['create_ip'] = $this->CI->koje_system->getRemoteAddr();
  		$par['result_code'] = $result[1];
  		$par['result_msg'] = $result[2];
  		$par['sys_logs_id'] = $this->CI->adodb->genID();
  		$this->CI->adodb->AutoExecute('sys_logs', $par, "INSERT");
  	}
    function createFile($input=false)
    {
      $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
      $input['sys_created_date']= NOW();
      $input['t_file_id']       = $this->CI->adodb->genID();
      $rs = $this->CI->adodb->AutoExecute('t_file', $input, "INSERT");
      $result = $this->generateResult($rs);
      $this->logActivity("FILE - INSERT", $input, $result);
      return $input['t_file_id'];
    }

    function uploadPict($input=false)
    {
      $id = $this->CI->koje_system->getLoginID();
      if (isset($_FILES['pict']) && $_FILES['pict']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath    = $_FILES['pict']['tmp_name'];
        $fileName       = $_FILES['pict']['name'];
        $fileSize       = $_FILES['pict']['size'];
        $fileType       = $_FILES['pict']['type'];
        $fileNameCmps   = explode(".", $fileName);
        $fileExtension  = strtolower(end($fileNameCmps));
        $newFileName    = $id . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'gif', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
          $uploadFileDir = './data/picture/';
          $dest_path = $uploadFileDir . $newFileName;
          if(move_uploaded_file($fileTmpPath, $dest_path))
          {
            $message ='File is successfully uploaded.';
          }
          else
          {
            $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
          }
        }
      }

      $result[1]='00';
      $result[2]= 'DONE';

      return $result;

    }

    function ReferenceInsert($input=false)
    {
      $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
      $input['sys_created_date']= NOW();
      $input['reference_id']   = $this->CI->adodb->genID();
      $rs = $this->CI->adodb->autoExecute('t_reference', $input, 'INSERT');
      $result = $this->generateResult($rs);
      $this->logActivity("REFERENCE - INSERT", $input, $result);
      return $result;
  	}

    function ReferenceEdit($input=false)
    {
      $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
      $input['sys_modified_date']= NOW();
      $rs = $this->CI->adodb->autoExecute('t_reference', $input, 'UPDATE','reference_id='.$input['reference_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("REFERENCE - UPDATE", $input, $result);
      return $result;
    }

    function ROInsert($input=false)
    {
      $input['r_ro_id']   = $input['ID'];
	  $input['ro_code'] = $input['code'];
      $rs = $this->CI->adodb->autoExecute('r_ro', $input, 'INSERT');
      $result = $this->generateResult($rs);
      $this->logActivity("RO - INSERT", $input, $result);
      return $result;
  	}

    function ROEdit($input=false)
    {
      $rs = $this->CI->adodb->autoExecute('r_ro', $input, 'UPDATE','r_ro_id='.$input['r_ro_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("RO - UPDATE", $input, $result);
      return $result;
    }

    function RODelete($input=false)
    {
	  $rs = $this->CI->adodb->execute("delete from r_ro where r_ro_id=?", array($input['r_ro_id']));
		
      $result = $this->generateResult($rs);
      $this->logActivity("RO - DELETE", $input, $result);
      return $result;
    }
	
	function LeadsInsert($input=false)
    {
		$input['t_account_id']   = $this->CI->adodb->genID();
		$input['account_status'] = 'CUST-01';
		$input['sales_id'] = $this->CI->koje_system->getLoginID();
		$input['company_name'] = $input['company_name'];
		$input['company_phone'] = $input['company_phone'];
		$input['company_address_info'] = $input['company_address_info'];
		$input['cp_1_salute'] = $input['cp_1_salute'];
		$input['cp_1_name'] = $input['cp_1_name'];
		$input['cp_1_mobile'] = $input['cp_1_mobile'];
		$input['cp_1_email'] = $input['cp_1_email'];
		$input['source'] = $input['source'];
		$input['region'] = $input['region'];
		$input['industry'] = $input['industry'];
		$input['sys_created_by'] = $this->CI->koje_system->getLoginID();

      $rs = $this->CI->adodb->autoExecute('t_accounts', $input, 'INSERT');
      $result = $this->generateResult($rs);
      $this->logActivity("Leads - INSERT", $input, $result);
      return $result;
  	}

    function LeadsEdit($input=false)
    {
		//$input['account_status'] = 'CUST-01';
		$input['sales_id'] = $this->CI->koje_system->getLoginID();
		$input['company_name'] = $input['company_name'];
		$input['company_phone'] = $input['company_phone'];
		$input['company_address_info'] = $input['company_address_info'];
		$input['cp_1_salute'] = $input['cp_1_salute'];
		$input['cp_1_name'] = $input['cp_1_name'];
		$input['cp_1_mobile'] = $input['cp_1_mobile'];
		$input['cp_1_email'] = $input['cp_1_email'];
		$input['source'] = $input['source'];
		$input['region'] = $input['region'];
		$input['industry'] = $input['industry'];
		$input['sys_updated_by'] = $this->CI->koje_system->getLoginID();

      $rs = $this->CI->adodb->autoExecute('t_accounts', $input, 'UPDATE','t_account_id='.$input['t_account_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("Leads - UPDATE", $input, $result);
      return $result;
    }
	
	function LeadsLevelUp($input=false)
    {
		$input['account_status'] = 'CUST-02';
		$input['sales_id'] = $this->CI->koje_system->getLoginID();
		$input['company_name'] = $input['company_name'];
		$input['company_phone'] = $input['company_phone'];
		$input['company_address_info'] = $input['company_address_info'];
		$input['company_anniversary'] = $input['company_anniversary'];
		$input['company_npwp'] = $input['company_npwp'];		
		$input['address_area'] = $input['address_area'];
		$input['address_street'] = $input['address_street'];
		$input['address_city_id'] = $input['address_city_id'];
		if(!empty($input['address_city_id']))
		{
		  $input['address_city']    = $this->CI->app_lib->getBillingCityName($input['address_city_id']);
		  $input['address_province']= $this->CI->app_lib->getBillingProvinceName($input['address_city_id']);
		}
		
		//$input['address_province'] = $input['address_province'];
		$input['address_zip'] = $input['address_zip'];
		
		$input['cp_1_salute'] = $input['cp_1_salute'];
		$input['cp_1_name'] = $input['cp_1_name'];
		$input['cp_1_mobile'] = $input['cp_1_mobile'];
		$input['cp_1_email'] = $input['cp_1_email'];
		$input['cp_1_info'] = $input['cp_1_info'];
		
		$input['cp_2_salute'] = $input['cp_2_salute'];
		$input['cp_2_name'] = $input['cp_2_name'];
		$input['cp_2_mobile'] = $input['cp_2_mobile'];
		$input['cp_2_email'] = $input['cp_2_email'];
		$input['cp_2_info'] = $input['cp_2_info'];
		$input['source'] = $input['source'];
		
		//$input['r_ro_id'] = $input['r_ro_id'];
		$input['industry'] = $input['industry'];
		$input['rating'] = $input['rating'];
		$input['sys_updated_by'] = $this->CI->koje_system->getLoginID();

      $rs = $this->CI->adodb->autoExecute('t_accounts', $input, 'UPDATE','t_account_id='.$input['t_account_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("Leads - LEVEL UP", $input, $result);
      return $result;
    }
	
	function AccountsEdit($input=false)
    {
		//$input['account_status'] = 'CUST-02';
		$input['sales_id'] = $this->CI->koje_system->getLoginID();
		$input['company_name'] = $input['company_name'];
		$input['company_phone'] = $input['company_phone'];
		$input['company_address_info'] = $input['company_address_info'];
		$input['company_anniversary'] = $input['company_anniversary'];
		$input['company_npwp'] = $input['company_npwp'];		
		$input['address_area'] = $input['address_area'];
		$input['address_street'] = $input['address_street'];
		$input['address_city_id'] = $input['address_city_id'];
		if(!empty($input['address_city_id']))
		{
		  $input['address_city']    = $this->CI->app_lib->getBillingCityName($input['address_city_id']);
		  $input['address_province']= $this->CI->app_lib->getBillingProvinceName($input['address_city_id']);
		}
		
		//$input['address_province'] = $input['address_province'];
		$input['address_zip'] = $input['address_zip'];
		
		$input['cp_1_salute'] = $input['cp_1_salute'];
		$input['cp_1_name'] = $input['cp_1_name'];
		$input['cp_1_mobile'] = $input['cp_1_mobile'];
		$input['cp_1_email'] = $input['cp_1_email'];
		$input['cp_1_info'] = $input['cp_1_info'];
		
		$input['cp_2_salute'] = $input['cp_2_salute'];
		$input['cp_2_name'] = $input['cp_2_name'];
		$input['cp_2_mobile'] = $input['cp_2_mobile'];
		$input['cp_2_email'] = $input['cp_2_email'];
		$input['cp_2_info'] = $input['cp_2_info'];
		$input['source'] = $input['source'];
		
		$input['r_ro_id'] = $input['r_ro_id'];
		$input['industry'] = $input['industry'];
		$input['rating'] = $input['rating'];
		$input['sys_updated_by'] = $this->CI->koje_system->getLoginID();

      $rs = $this->CI->adodb->autoExecute('t_accounts', $input, 'UPDATE','t_account_id='.$input['t_account_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("Leads - LEVEL UP", $input, $result);
      return $result;
    }

    function AccountDelete($input=false)
    {
	  $rs = $this->CI->adodb->execute("delete from r_ro where r_ro_id=?", array($input['r_ro_id']));
		
      $result = $this->generateResult($rs);
      $this->logActivity("RO - DELETE", $input, $result);
      return $result;
    }


	
//*************************** FLOW **********************************//

	function FLOWInsert($input=false)
    {
	  // $sys_users_id = $this->CI->adodb->getOne("select max(substr(prospect_no,5)) from t_prospect where prospect_no like '$ym%'");
      //$input['r_flow_id']   = $input['ID'];
	  // $input['discount'] = $input['discount'];
	  $input['parent_id'] =  $input['parent_id'] > "" ? $input['parent_id'] : 0;
	  if(!empty($input['sys_users_id']))
		{
		  $input['val']    = $this->CI->app_lib->getPICName($input['sys_users_id']);		  
		}
      $rs = $this->CI->adodb->autoExecute('r_flow', $input, 'INSERT');
      $result = $this->generateResult($rs);
      $this->logActivity("WORKFLOW - INSERT", $input, $result);
      return $result;
  	}

    function FLOWEdit($input=false)
    {
		if(!empty($input['sys_users_id']))
		{
		  $input['val']    = $this->CI->app_lib->getPICName($input['sys_users_id']);		  
		}

      $rs = $this->CI->adodb->autoExecute('r_flow', $input, 'UPDATE','r_flow_id='.$input['r_flow_id']);
      $result = $this->generateResult($rs);
      $this->logActivity("WORKFLOW - UPDATE", $input, $result);
      return $result;
    }

    function FLOWDelete($input=false)
    {
	  $rs = $this->CI->adodb->execute("delete from r_flow where r_flow_id=?", array($input['r_flow_id']));
		
      $result = $this->generateResult($rs);
      $this->logActivity("WORKFLOW - DELETE", $input, $result);
      return $result;
    }


//*************************** WORKFLOW **********************************//
  function genProspectNo() {
    $ym = date("ym");
    $no = $this->CI->adodb->getOne("select max(substr(prospect_no,5)) from t_prospect where prospect_no like '$ym%'");
    $no =  !$no ? 1 : ($no+1);
    $no = $ym.str_pad($no, 3, "0", STR_PAD_LEFT);
    return $no;
  }
  function ProspectStageInsert($input=false)
  {
    $id = false;
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_prospect_stage_id']   = $this->CI->adodb->genID();
    $this->CI->adodb->execute("delete from t_prospect_stage where t_prospect_id=? and t_stage_id=?", array($input['t_prospect_id'], $input['t_stage_id']));
    $rs = $this->CI->adodb->autoExecute('t_prospect_stage', $input, 'INSERT');
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT STAGE - INSERT", $input, $result);
  }
  function canvassingUpload($input=false)
  { // CompanyName	Phone	AddressInfo	ContactName	Mobile	Email	Description
    $this->CI->config->load('upload');
    $result = $this->CI->koje_system->uploadFile('file');
    if($result[1]!=='00') {
      return $result;
    }
    $fid = $result[2];
    $fn = $this->CI->adodb->getOne("select file_name from t_file where t_file_id=$fid");
    $inputFileName = $this->CI->config->item('upload_path').$fn;
    $spreadsheet = IOFactory::load($inputFileName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    foreach ($sheetData as $key => $value) {
      if($key==1) {
        continue;
      }
      $input['company_name'] = $sheetData[$key]['A'];
      $input['prospect_name'] = $sheetData[$key]['A'];
      $input['company_phone'] = $sheetData[$key]['B'];
      $input['company_address_info'] = $sheetData[$key]['C'];
      $input['cp_1_name'] = $sheetData[$key]['D'];
      $input['cp_1_mobile'] = $sheetData[$key]['E'];
      $input['cp_1_email'] = $sheetData[$key]['F'];
      $input['description'] = $sheetData[$key]['G'];
      $this->prospectInsert($input);
    }
    $this->logActivity("PROSPECT - UPLOAD", $input, $result);
    return $result;
  }
  function ProspectStageEdit($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $rs = $this->CI->adodb->autoExecute('t_prospect_stage', $input, 'UPDATE','t_prospect_id='.$input['t_prospect_id'].' and t_stage_id='.$input['t_stage_id']);
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT STAGE - UPDATE", $input, $result);
  }
  function prospectInsert($input=false) {
    $input['t_prospect_id']   = $this->CI->adodb->genID();
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['sales_id']        = $input['sys_created_by'];
	//new input
	$input['company_npwp'] = $input['company_npwp'];
	$input['industry'] = $input['industry'];
	$input['address_zip'] = $input['address_zip'];
	$input['address_city'] = $input['address_city'];
	
	$input['cp_1_info'] = $input['cp_1_info'];
	$input['cp_1_name'] = $input['cp_1_name'];
	$input['cp_1_mobile'] = $input['cp_1_mobile'];
	$input['cp_1_email'] = $input['cp_1_email'];
	$input['cp_1_salute'] = $input['cp_1_salute'];
	$input['cp_1_department'] = $input['cp_1_department'];
	
	$input['cp_2_info'] = $input['cp_2_info'];
	$input['cp_2_name'] = $input['cp_2_name'];
	$input['cp_2_mobile'] = $input['cp_2_mobile'];
	$input['cp_2_email'] = $input['cp_2_email'];
	$input['cp_2_salute'] = $input['cp_2_salute'];
	$input['cp_2_department'] = $input['cp_2_department'];
	
	$input['presales_status'] = 'PRESALES-01';
	if(!empty($input['address_city_id']))
    {
      $input['address_city']    = $this->CI->app_lib->getBillingCityName($input['address_city_id']);
      $input['address_province']= $this->CI->app_lib->getBillingProvinceName($input['address_city_id']);
    }
    $input['sales_id']        = $input['sys_created_by'];
    $input['prospect_no']     = $this->genProspectNo();
    if(isset($input['prospect_name']) && empty($input['prospect_name']))
    {
      $input['prospect_name'] = $input['company_name'];
    }
    $input['status']          = $this->CI->app_lib->getProspectStatus('ACTIVE');
    $input['start_date']      = $input['prospect_start_date'];
    $input['t_stage_id']      = $input['current_stage_id']= $this->CI->app_lib->getFirstStage();
    $this->ProspectStageInsert($input);
    $this->RequirementGenerate($input);
    $rs = $this->CI->adodb->AutoExecute('t_prospect', $input, "INSERT");
    $result = $this->generateResult($rs);

    $this->logActivity("PROSPECT - INSERT", $input, $result);
    //$cname = $this->CI->app_lib->getCompanySimilar($input['company_name'],$input['t_prospect_id']);
    //$result[2] .= $cname;
    return $result;
  }

  function ProspectEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
	$input['presales_status'] = $input['presales_status'];
    $input['presales_desc'] = $input['presales_desc'];
    if(!empty($input['address_city_id']))
    {
      $input['address_city']    = $this->CI->app_lib->getBillingCityName($input['address_city_id']);
      $input['address_province']= $this->CI->app_lib->getBillingProvinceName($input['address_city_id']);
    }
    if(isset($input['prospect_name']) && empty($input['prospect_name']))
    {
      $input['prospect_name'] = $input['company_name'];
    }
    $this->RequirementGenerate($input);
    $rs = $this->CI->adodb->autoExecute("t_prospect", $input, "UPDATE","t_prospect_id=".$input['t_prospect_id']);
    $result = $this->generateResult($rs);
    $this->CI->session->set_userdata('t_prospect_id',$input['t_prospect_id']);
    $this->logActivity("PROSPECT - UPDATE", $input, $result);
    if (isset($input['company_name'])) {
      $cname = $this->CI->app_lib->getCompanySimilar($input['company_name'],$input['t_prospect_id']);
      $result[2] .= $cname;
    }
    return $result;
  }
  
  function ProspectEditPresales($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['presales_status'] = $input['presales_status'];
    $input['presales_desc'] = $input['presales_desc'];
    $input['presales_update'] = NOW();
    $rs = $this->CI->adodb->autoExecute("t_prospect", $input, "UPDATE","t_prospect_id=".$input['t_prospect_id']);
    $result = $this->generateResult($rs);
    $this->CI->session->set_userdata('t_prospect_id',$input['t_prospect_id']);
    return $result;
  }

  function ProspectCancel($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['status'] = $this->CI->app_lib->getProspectStatus('CANCEL');
    $rs = $this->CI->adodb->autoExecute('t_prospect', $input, 'UPDATE', 't_prospect_id='.$input['t_prospect_id']);
    $result = $this->generateResult($rs);
    $input['status']          = $input['status'];
    $input['t_stage_id']      = $this->CI->app_lib->getIDStage('CANCEL');
    $this->ProspectStageInsert($input);
    $this->logActivity("PROSPECT - CANCEL", $input, $result);
    return $result;
  }

  /*function ProspectNext($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['end_date']           = $input['start_date'];
    $input['t_stage_id']      = $this->CI->app_lib->getNextStage($input['current_stage_id']);
    $this->ProspectStageEdit($input);
    $this->ProspectStageInsert($input);
    $input['current_stage_id'] = $input['t_stage_id'];
    $rs = $this->CI->adodb->autoExecute("t_prospect", $input, "UPDATE","t_prospect_id=".$input['t_prospect_id']);
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT - NEXT", $input, $result);
    return $result;
  }*/
  function ProspectNext($input=false)
  {  
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['end_date']           = $input['start_date'];
    $input['t_stage_id']      = $this->CI->app_lib->getNextStage($input['current_stage_id']);
    $this->ProspectStageEdit($input);
    $this->ProspectStageInsert($input);
    $input['current_stage_id'] = $input['t_stage_id'];
    $rs = $this->CI->adodb->autoExecute("t_prospect", $input, "UPDATE","t_prospect_id=".$input['t_prospect_id']);
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT - NEXT", $input, $result);
    // if (isset($input['company_npwp'])) {
      // $cname = $this->CI->app_lib->getProspectDuplicateByID($input['t_prospect_id']);
      // $result[2] .= $cname;
    // }
    return $result;
  }

  function ProspectDone($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['status']          = $this->CI->app_lib->getProspectStatus('DONE');
    $rs = $this->CI->adodb->autoExecute("t_prospect", $input, "UPDATE","t_prospect_id=".$input['t_prospect_id']);
    $result = $this->generateResult($rs);

    $this->logActivity("PROSPECT - DONE", $input, $result);
    return $result;
  }

  function telemarketingInsert($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_telemarketing_id'] = $this->CI->adodb->genID();
    $rs = $this->CI->adodb->autoExecute("t_telemarketing", $input, "INSERT");
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT TELEMARKETING - INSERT", $input, $result);
    return $result;
  }

  function telemarketingEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $rs = $this->CI->adodb->autoExecute("t_telemarketing", $input, "UPDATE","t_telemarketing_id=".$input['t_telemarketing_id']);
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT TELEMARKETING - UPDATE", $input, $result);
    return $result;
  }
  function ProposalInsert($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_proposal_id'] = $this->CI->adodb->genID();
		$fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
    }

    $rs = $this->CI->adodb->autoExecute('t_proposal', $input, 'INSERT');
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT PROPOSAL - INSERT", $input, $result);
    return $result;
  }
  function ProposalEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $upload_msg = "";
    $fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
      $upload_msg .= $fr1[1]!='10' ? $fr1[2] : "";
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
      $upload_msg .= $fr2[1]!='10' ? $fr2[2] : "";
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
      $upload_msg .= $fr3[1]!='10' ? $fr3[2] : "";
    }

    $rs = $this->CI->adodb->autoExecute("t_proposal", $input, "UPDATE","t_proposal_id=".$input['t_proposal_id']);
    $result = $this->generateResult($rs);
    $result[2] .= "<br/>".$upload_msg;
    $this->logActivity("PROSPECT PROPOSAL - UPDATE", $input, $result);
    return $result;
  }

  function PresentationInsert($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_presentation_id'] = $this->CI->adodb->genID();
    $upload_msg = "";
		$fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
      $upload_msg .= $fr1[1]!='10' ? $fr1[2] : "";
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
      $upload_msg .= $fr2[1]!='10' ? $fr2[2] : "";
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
      $upload_msg .= $fr3[1]!='10' ? $fr3[2] : "";
    }

    $rs = $this->CI->adodb->autoExecute('t_presentation', $input, 'INSERT');
    $result = $this->generateResult($rs);
    $result[2] .= "<br/>".$upload_msg;
    $this->logActivity("PROSPECT PRESENTATION - INSERT", $input, $result);
    return $result;
  }
  function PresentationEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $upload_msg = "";
    $fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
      $upload_msg .= $fr1[1]!='10' ? $fr1[2] : "";
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
      $upload_msg .= $fr2[1]!='10' ? $fr2[2] : "";
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
      $upload_msg .= $fr3[1]!='10' ? $fr3[2] : "";
    }

    $rs = $this->CI->adodb->autoExecute("t_presentation", $input, "UPDATE","t_presentation_id=".$input['t_presentation_id']);
    $result = $this->generateResult($rs);
    $result[2] .= "<br/>".$upload_msg;
    $this->logActivity("PROSPECT PRESENTATION - UPDATE", $input, $result);
    return $result;
  }
  function surveyDetailGenerate($input)
  {
    $service_lists = $this->CI->app_lib->getDataServiceGroupList();
    foreach ($service_lists as $key => $value)
    {
      $table    = 't_survey_'.$key;
      $table_id = 't_survey_'.$key.'_id';
      if(!empty($input[$key]))
      {
        $id = $this->CI->adodb->getOne("select t_survey_{$key}_id from $table where t_survey_id=?", array($input['t_survey_id']));
        if(!$id)
        {
          $input[$table_id] = $this->CI->adodb->genID();
          $rs = $this->CI->adodb->autoExecute($table, $input, 'INSERT');
        }
        else
        {
          $rs = $this->CI->adodb->autoExecute($table, $input, "UPDATE",$table_id."=".$id);
        }
      }
      else
      {
        $rs = $this->CI->adodb->execute("update t_survey set $key='N' where t_survey_id=?", array($input['t_survey_id']));
      }
    }
  }
  function surveyInsert($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_survey_id'] = $this->CI->adodb->genID();
    $upload_msg = "";
    $fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
      $upload_msg .= $fr1[1]!='10' ? $fr1[2] : "";
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
      $upload_msg .= $fr2[1]!='10' ? $fr2[2] : "";
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
      $upload_msg .= $fr3[1]!='10' ? $fr3[2] : "";
    }

    foreach ($input as $key => $value) {
      if(is_array($value))
      {
        $input[$key] = $value[0];
      }
    }
    $this->surveyDetailGenerate($input);

    $rs = $this->CI->adodb->autoExecute('t_survey', $input, 'INSERT');
    $result = $this->generateResult($rs);
    $result[2] .= "<br/>".$upload_msg;

    $this->logActivity("PROSPECT SURVEY - INSERT", $input, $result);
    $this->CI->app_lib->sendemail_survey_request($input);
    return $result;
  }
  function surveyEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $upload_msg = "";
    $fr1 = $this->CI->koje_system->uploadFile('attachment_1_id');
    if($fr1[1]=='00' && is_numeric($fr1[2])) {
      $input['attachment_1_id'] = $fr1[2];
    }
    else {
      unset($input['attachment_1_id']);
      $upload_msg .= $fr1[1]!='10' ? $fr1[2] : "";
    }

    $fr2 = $this->CI->koje_system->uploadFile('attachment_2_id');
    if($fr2[1]=='00' && is_numeric($fr2[2])) {
      $input['attachment_2_id'] = $fr2[2];
    }
    else {
      unset($input['attachment_2_id']);
      $upload_msg .= $fr2[1]!='10' ? $fr2[2] : "";
    }
    $fr3 = $this->CI->koje_system->uploadFile('attachment_3_id');
    if($fr3[1]=='00' && is_numeric($fr3[2])) {
      $input['attachment_3_id'] = $fr3[2];
    }
    else {
      unset($input['attachment_3_id']);
      $upload_msg .= $fr3[1]!='10' ? $fr3[2] : "";
    }

    foreach ($input as $key => $value) {
      if(is_array($value))
      {
        $input[$key] = $value[0];
      }
    }

    $this->surveyDetailGenerate($input);

    $rs = $this->CI->adodb->autoExecute("t_survey", $input, "UPDATE","t_survey_id=".$input['t_survey_id']);
    $result = $this->generateResult($rs);
    $result[2] .= "<br/>".$upload_msg;
    $this->logActivity("CUSTOMER SURVEY - UPDATE", $input, $result);
    $this->CI->app_lib->sendemail_survey_request($input);

    return $result;
  }

  function genQuotationNo() {
    $ym = "PGASCOM/QT/".date("ym");
    $len = strlen($ym);
    $no = $this->CI->adodb->getOne("select max(substr(quotation_no,$len+1)) from t_quotation where quotation_no like '$ym%'");
    $no =  !$no ? 1 : ($no+1);
    $no = $ym.str_pad($no, 3, "0", STR_PAD_LEFT);
    return $no;
  }

  function QuotationGenerate($input=false)
  {
    $t_quotation_id = $input['t_quotation_id'];
    if($input['quotation_no']=="")
    {
      $input['quotation_no']    = $this->genQuotationNo($input['t_prospect_id']);
    }
    if($t_quotation_id) {
      $this->CI->session->set_userdata('t_quotation_id',$t_quotation_id);
      $rowsBefore = $this->CI->adodb->getRow("select count(*) as cnt, sum(amount) as sum_amount, sum(total) as sum_total from t_quotation_item where t_quotation_id=$t_quotation_id");
    }
    else {
        $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
        $input['sys_created_date']= NOW();
        $input['t_quotation_id']  = $this->CI->adodb->genID();
        $this->CI->session->set_userdata('t_quotation_id',$input['t_quotation_id']);
        $input['status']          = $this->CI->app_lib->getQuotationStatus('PREPARATION');
        $rs = $this->CI->adodb->AutoExecute('t_quotation', $input, "INSERT");
        $input1['t_quotation_id'] = $input['t_quotation_id'];
        $input1['log_type'] = 'CREATED';
        $input1['notes'] = $input['description'];
        $this->QuotationLog($input1);
    }

    $this->ItemChargeGenerate($input);
    $this->ItemChargeAlternativeGenerate($input);
    $this->RequirementGenerate($input);

    if($t_quotation_id) {
      $rowsAfter = $this->CI->adodb->getRow("select count(*) as cnt, sum(amount) as sum_amount, sum(total) as sum_total from t_quotation_item where t_quotation_id=$t_quotation_id");
      $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
      $input['sys_modified_date']= NOW();
      unset($input['status']);
      if( $rowsBefore['cnt']!=$rowsAfter['cnt'] ||
          $rowsBefore['sum_amount']!=$rowsAfter['sum_amount'] ||
          $rowsBefore['sum_amount']!=$rowsAfter['sum_amount'])
      {
        $statusQuotation = $this->CI->adodb->getRow("select status from t_quotation where t_quotation_id=$t_quotation_id");
        if($statusQuotation!=$this->CI->app_lib->getQuotationStatus('PREPARATION') &&
           $statusQuotation!=$this->CI->app_lib->getQuotationStatus('CANCELLED')
        )
        {
          $input['status']       = $this->CI->app_lib->getQuotationStatus('PREPARATION');
          $input['approved_by']  = false;
          $input['approved_date']= false;
        }
      }

      $rs = $this->CI->adodb->AutoExecute('t_quotation', $input, "UPDATE", "t_quotation_id=$t_quotation_id");

      $this->NegotiationInsert($input);

      $input1['t_quotation_id'] = $t_quotation_id;
      $input1['log_type'] = 'UPDATE';
      $input1['notes'] = $input['description'];
      $this->QuotationLog($input1);
    }

    $result = $this->generateResult($rs);
    $this->logActivity("QUOTATION - GENERATE", $input, $result);
    return $result;
  }

  function QuotationCancel($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['status'] = $this->CI->app_lib->getQuotationStatus('CANCELLED');
    $rs = $this->CI->adodb->autoExecute('t_quotation', $input, 'UPDATE', 't_quotation_id='.$input['t_quotation_id']);
    $result = $this->generateResult($rs);

    $this->logActivity("QUOTATION - CANCEL", $input, $result);
    return $result;
  }
  function QuotationRequest($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['request_by']  = $input['sys_modified_by'];
    $input['request_date']= $input['sys_modified_date'];
    $input['status'] = $this->CI->app_lib->getQuotationStatus('REQUEST');
    $rs = $this->CI->adodb->autoExecute('t_quotation', $input, 'UPDATE', 't_quotation_id='.$input['t_quotation_id']);
    $result = $this->generateResult($rs);
    $input1['t_quotation_id'] = $input['t_quotation_id'];
    $input1['log_type'] = 'REQUEST_APPROVAL';
    $input1['notes'] = $input['request_notes'];
    $this->QuotationLog($input1);
    $this->logActivity("QUOTATION - REQUEST APPROVAL", $input, $result);
	$this->FlowDetailGenerate($input['discount'],$input['t_quotation_id']);	
    return $result;
  }
  
  function FlowDetailGenerate($parent_id,$t_quotation_id) { 
	$emp_names = array('parent_id'=> $parent_id );
	$listROdb = $this->CI->db	->select('*')
							->where($emp_names)
							->order_by('priority', 'ASC')
						->get('r_flow');
	$paramsceck = array('transaction_id' => $t_quotation_id , 'created_by' => $this->CI->koje_system->getLoginID());
	$listworkflow = $this->CI->db	->select('id')
							->where($paramsceck)
							->order_by('priority', 'ASC')
						->get('r_workflow');
	
	foreach ($listworkflow->result() as $key => $lw) {
		if(!empty($lw->id)) {
			$this->CI->adodb->execute("delete from r_workflow where id=?", $lw->id);
		}
	}
		$input2['transaction_id']= $t_quotation_id;
		$input2['priority']= 1;
		// $input2['sys_users_id']= $this->CI->koje_system->getLoginID();
		$input2['name_pic']= $this->CI->koje_system->getLoginID();
		$input2['created_by']= $this->CI->koje_system->getLoginID();
		$input2['created_date']= NOW();
		$input2['approved_date']= NOW();
		$rsww = $this->CI->adodb->autoExecute('r_workflow', $input2, 'INSERT');
	foreach ($listROdb->result() as $key => $RO) {

		$input['transaction_id']= $t_quotation_id;
		$input['priority']= $RO->priority+1;
		$input['sys_users_id']= $RO->sys_users_id;
		$input['name_pic']= $RO->val;
		$input['created_by']= $this->CI->koje_system->getLoginID();
		$input['created_date']= NOW();
		$rs = $this->CI->adodb->autoExecute('r_workflow', $input, 'INSERT');
	}
  
  
  }
  function QuotationLog($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_quotation_log_id'] = $this->CI->adodb->genID();
    $rs = $this->CI->adodb->autoExecute('t_quotation_log', $input, 'INSERT');
  }
  function QuotationApproval($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['approved_by']  = $input['sys_modified_by'];
    $input['approved_date']= $input['sys_modified_date'];
	
	
	$listworkflow = 	$this->CI->adodb->getOne("select get_workflow_id(? ,? )",
                                      array($this->CI->koje_system->getLoginID(), $input['t_quotation_id']));
	// var_dump($listworkflow);
	// die();
	$input3['approved_date'] = $input['approved_date'];
	$rsw = $this->CI->adodb->autoExecute('r_workflow', $input3, 'UPDATE', 'id='.$listworkflow);	
	$cekworkflow = 	$this->CI->adodb->getOne("select cek_finish(?)",
                                      array($input['t_quotation_id']));	
							  
	if ($cekworkflow == 'Y'){
		$input['status'] = 'QTST-03';	
		}
	
    $input['approved_status']= $input['status'];
    
    $rs = $this->CI->adodb->autoExecute('t_quotation', $input, 'UPDATE', 't_quotation_id='.$input['t_quotation_id']);
    $result = $this->generateResult($rs);
    $input1['t_quotation_id'] = $input['t_quotation_id'];
    $input1['log_type'] = 'APPROVAL';
    $input1['notes'] = $input['approved_notes'];
    $this->QuotationLog($input1);
    $this->logActivity("QUOTATION - APPROVAL", $input, $result);	

	return $result;
  }
  function QuotationEscalation($input=false)
  {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['escalation_by']  = $input['sys_modified_by'];
    $input['escalation_date']= $input['sys_modified_date'];
    $input['approved_status']= $input['status'];
    $rs = $this->CI->adodb->autoExecute('t_quotation', $input, 'UPDATE', 't_quotation_id='.$input['t_quotation_id']);
    $result = $this->generateResult($rs);
    $input1['t_quotation_id'] = $input['t_quotation_id'];
    $input1['log_type'] = 'ESCALATION';
    $input1['notes'] = $input['escalation_notes'];
    $this->QuotationLog($input1);
    $this->logActivity("QUOTATION - ESCALATION", $input, $result);
    return $result;
  }
  function RequirementGenerate($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    if(empty($input['requirement_cnt']))
    {
      return;
    }
    $cnt = $input['requirement_cnt'];
    $i = 1;
    $rs = false;
    while ($i <= $cnt) {
      $input['t_requirement_id'] = $input['t_requirement_id__'.$i];
      $input['t_product_service_id'] = $input['t_product_service_id__'.$i];
      $input['speed'] = $input['speed__'.$i];
      $input['uom'] = $input['uom__'.$i];
      $input['priority'] = $input['priority__'.$i];

      if(!empty($input['t_product_service_id']))
      {
        $input['product_code'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'product_code');
        $input['product_name'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'product_name');
        $input['service_code'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'service_code');
        $input['service_name'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'service_name');
      }
      if ($input['t_product_service_id']!=="") {
        if(!$input['t_requirement_id']) {
          $input['t_requirement_id']  = $this->CI->adodb->genID();
          $rs = $this->CI->adodb->autoExecute("t_requirement", $input, "INSERT");
        }
        else {
          $rs = $this->CI->adodb->autoExecute("t_requirement", $input, "UPDATE", "t_requirement_id=".$input['t_requirement_id']);
        }
      }
      else {
        if($input['t_requirement_id']) {
          $rs = $this->CI->adodb->execute("delete from t_requirement where t_requirement_id=?", array($input['t_requirement_id']));
        }
      }
      $i++;
    }
    $result = $this->generateResult($rs);
    $this->CI->session->set_userdata('t_prospect_id',$input['t_prospect_id']);
    $this->logActivity("REQUIREMENT - GENERATE", $input, $result);
    return $result;
  }
  function ItemChargeGenerate($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    if(empty($input['item_charge_cnt']))
    {
      return;
    }

    $cnt = $input['item_charge_cnt'];
    $i = 1;
    $rs = false;
    while ($i <= $cnt) {
      $t_quotation_item_id = getArrayVal($input,'t_quotation_item_id__'.$i);
      $item_pid = getArrayVal($input,'t_product_item_id__'.$i);
      $item_name = getArrayVal($input,'item_name__'.$i);
      $item_tax = $this->CI->koje_system->replaceCharacter(getArrayVal($input,'item_tax__'.$i),',');
      $item_amount = $this->CI->koje_system->replaceCharacter(getArrayVal($input,'item_amount__'.$i),',');
      $item_notes = getArrayVal($input,'item_notes__'.$i);
      if(!empty($item_pid))
      {
        $input1['item_type'] = $this->CI->app_lib->getDataProductItemType($item_pid);
        $input1['item_type_desc'] = $this->CI->app_lib->getDataProductItemTypeDesc($item_pid);
      }

      if($item_pid) {
        $input1['t_quotation_id']         = $input['t_quotation_id'];
        $input1['t_product_item_id']      = $item_pid;
        $input1['name']                   = $item_name;
        $input1['amount']                 = $item_amount;
        $input1['tax']                    = $item_tax;
        $input1['total']                  = $item_amount * (100+$item_tax)/100;
        $input1['notes']                  = $item_notes;
        if(!$t_quotation_item_id) {
          $input1['t_quotation_item_id']  = $this->CI->adodb->genID();
          $rs = $this->CI->adodb->autoExecute("t_quotation_item", $input1, "INSERT");
        }
        else {
          $rs = $this->CI->adodb->autoExecute("t_quotation_item", $input1, "UPDATE", "t_quotation_item_id=".$t_quotation_item_id);
        }
      }
      else {
        if($t_quotation_item_id) {
          $rs = $this->CI->adodb->execute("delete from t_quotation_item where t_quotation_item_id=?", array($t_quotation_item_id));
        }
      }
      $i++;
    }
    $result = $this->generateResult($rs);
    $this->CI->session->set_userdata('t_prospect_id',$input['t_prospect_id']);
    $this->logActivity("QUOTATION ITEM - GENERATE", $input, $result);
    return $result;
  }
  function ItemChargeAlternativeGenerate($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    if(empty($input['item_charge_alt_cnt']))
    {
      return;
    }
    $cnt = $input['item_charge_alt_cnt'];
    $i = 1;
    $rs = false;
    while ($i <= $cnt) {
      $input['t_quotation_item_alt_id'] = $input['t_quotation_item_alt_id__'.$i];
      $input['t_product_service_id'] = $input['t_product_service_id__'.$i];
      $input['speed'] = $input['speed__'.$i];
      $input['uom'] = $input['uom__'.$i];
      $input['otc_charge'] = $this->CI->koje_system->replaceCharacter($input['otc_charge__'.$i],',');
      $input['mtce_charge'] = $this->CI->koje_system->replaceCharacter($input['mtce_charge__'.$i],',');

      if(!empty($input['t_product_service_id']))
      {
        $input['product_code'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'product_code');
        $input['product_name'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'product_name');
        $input['service_code'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'service_code');
        $input['service_name'] = $this->CI->app_lib->getDataProductServiceDesc($input['t_product_service_id'],'service_name');
      }
      if ($input['t_product_service_id']!=="") {
        if(!$input['t_quotation_item_alt_id']) {
          $input['t_quotation_item_alt_id']  = $this->CI->adodb->genID();
          $rs = $this->CI->adodb->autoExecute("t_quotation_item_alt", $input, "INSERT");
        }
        else {
          $rs = $this->CI->adodb->autoExecute("t_quotation_item_alt", $input, "UPDATE", "t_quotation_item_alt_id=".$input['t_quotation_item_alt_id']);
        }
      }
      else {
        if($input['t_quotation_item_alt_id']) {
          $rs = $this->CI->adodb->execute("delete from t_quotation_item_alt where t_quotation_item_alt_id=?", array($input['t_quotation_item_alt_id']));
        }
      }
      $i++;
    }
    $result = $this->generateResult($rs);
    $this->CI->session->set_userdata('t_prospect_id',$input['t_prospect_id']);
    $this->logActivity("ITEM CHARGE ALTERNATIVE - GENERATE", $input, $result);
    return $result;
  }
  function NegotiationInsert($input=false)
  {
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_negotiation_id'] = $this->CI->adodb->genID();
    if(!isset($input['activity_date']))
    {
      $input['activity_date'] = NOW();
    }
    $rs = $this->CI->adodb->autoExecute('t_negotiation', $input, 'INSERT');
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT NEGOTIATION - INSERT", $input, $result);
    return $result;
  }
  function NegotiationEdit($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    foreach ($input as $key => $value) {
      if(substr($key,0,4)=='qid_')
      {
        $id = substr($key,4);
        $input['status'] = $value;
        $rs = $this->CI->adodb->autoExecute("t_quotation", $input, "UPDATE","t_quotation_id=".$id);
      }
    }

    $rs = $this->CI->adodb->autoExecute("t_negotiation", $input, "UPDATE","t_negotiation_id=".$input['t_negotiation_id']);
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT NEGOTIATION - UPDATE", $input, $result);
    return $result;
  }
  function NegotiationDeal($input=false) {
    $input['sys_modified_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_modified_date']= NOW();
    $input['description']      = 'NEGOTIATION DEAL';

    foreach ($input as $key => $value) {
      if(substr($key,0,4)=='qid_')
      {
        $id = substr($key,4);
        $input['status'] = $value;
        $rs = $this->CI->adodb->autoExecute("t_quotation", $input, "UPDATE","t_quotation_id=".$id);
      }
    }
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['t_negotiation_id'] = $this->CI->adodb->genID();
    $rs = $this->CI->adodb->autoExecute("t_negotiation", $input, "INSERT");
    $result = $this->generateResult($rs);
    $this->logActivity("PROSPECT NEGOTIATION DEAL - UPDATE", $input, $result);
    return $result;
  }
  function GenerateBilling($input=false)
  {
    $rowProspect = $this->CI->app_lib->getDataProspect($input['t_prospect_id']);
    $input['sales_id'] = $rowProspect['sales_id'];
    $status = $this->CI->app_lib->getQuotationStatus('DEAL');
    $rows1 = $this->CI->adodb->getAll("select t_quotation_item_id, t_quotation_id,t_product_item_id, name, amount, tax, total, quotation_no
                                       from v_quotation_item
                                       where t_prospect_id=? and
                                             status='$status'",
                                      array($input['t_prospect_id'])
                                      );
    $input['product_item_list'] = $rows1;
    foreach ($input['product_item_list'] as $key => $value) {
      foreach ($value as $key1 => $value1) {
        if(is_numeric($key1))
        {
          unset($input['product_item_list'][$key][$key1]);
        }
      }
    }

    $rows1 = $this->CI->adodb->getAll("select t_product_service_id, speed, uom
                                       from t_requirement
                                       where t_prospect_id=?",
                                       array($input['t_prospect_id'])
                                      );
    $input['product_service_list'] = $rows1;
    foreach ($input['product_service_list'] as $key => $value) {
      foreach ($value as $key1 => $value1) {
        if(is_numeric($key1))
        {
          unset($input['product_service_list'][$key][$key1]);
        }
      }
    }

    $input['data'] = json_encode($input);
    //$response = http_curl_json('localhost/api/insert_customer.php?',$input['data']);
    $response = json_encode(array('result' => "00", 'data' => array("id"=>135,"code"=>"BI01")));
    $input['result'] = $response;
    $response_arr = json_decode($response,true);
    $result[1] = $response_arr['result'];
    $result[2] = $response_arr['data']['code'];

    if($result[1]=='00')
    {
      $input['t_billing_order_id'] 				= $this->CI->adodb->genID();
      $rs = $this->CI->adodb->autoExecute("t_billing_order", $input, "INSERT");

      $input4['t_prospect_id'] = $input['t_prospect_id'];
      $input4['status']        = $this->CI->app_lib->getProspectStatus('DONE');

      $result1 = $this->ProspectEdit($input4);
    /*TODO: handle prospect_type
        if prospect_type=PSTY-01 'INSTALL 
           prospect_type=PSTY-03 'PROJECT
           prospect_type=PSTY-05 'UPGRADE' 
           prospect_type=PSTY-06 'DOWNGRADE'
    */

    // start submit to billing
    $this->CI->app_lib->submitBillingOrder($input['t_billing_order_id']);
	  // end submit to billing
	  $this->logActivity("CUSTOMER GENERATE - INSERT", $input, $result1);
    }
    return $result;
  }

  function targetInsert($input=array())
  {
    $cnt = $this->CI->adodb->getOne("select count(*) from t_target where year=? and sales_id=?",
                                    array($input['year'],$input['sales_id']));
    if($cnt>0) {
      $result[1] = '90';
      $result[2] = 'Target Already Exists';
    }
    else {
      $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
      $input['sys_created_date']= NOW();
      $input['t_target_id'] 				= $this->CI->adodb->genID();
      $rs = $this->CI->adodb->autoExecute("t_target", $input, "INSERT");
      $result = $this->generateResult($rs);
      $this->logActivity("TARGET - INSERT", $input, $result);
    }
    return $result;
  }

  function targetEdit($input=array())
  {
    $t_target_id = $this->CI->input->get_post('t_target_id');
    $cnt = $this->CI->adodb->getOne("select count(*) from t_target where year=? and sales_id=? and t_target_id<>?",
                                    array($input['year'],$input['sales_id'],$t_target_id));
    if($cnt>0) {
      $result[1] = '90';
      $result[2] = 'Target Already Exists';
    }
    else {
      $input['sys_modified_by'] 	= $this->CI->koje_system->getLoginID();
      $input['sys_modified_date'] = NOW();
      $id = $this->CI->input->get_post('t_target_id');
      $rs = $this->CI->adodb->autoExecute("t_target", $input, "UPDATE", "t_target_id=$t_target_id");
      $result = $this->generateResult($rs);
      $this->logActivity("TARGET - EDIT", $input, $result);
    }
    return $result;
  }
  function targetUpload($input=false)
  { // EMAIL	TYPE	JAN	FEB	MAR	APR	MAY	JUN	JUL	AUG	SEP	OCT	NOV	DEC
    $this->CI->config->load('upload');
    $result = $this->CI->koje_system->uploadFile('file');
    if($result[1]!=='00') {
      return $result;
    }
    $fid = $result[2];
    $fn = $this->CI->adodb->getOne("select file_name from t_file where t_file_id=$fid");
    $inputFileName = $this->CI->config->item('upload_path').$fn;
    $spreadsheet = IOFactory::load($inputFileName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    foreach ($sheetData as $key => $value) {
      if($key<=2) {
        continue;
      }
      $input['email']           = $sheetData[$key]['A'];
      $input['sales_id']        = $this->CI->app_lib->getSalesIDByEmail($input['email']);
      if(!$input['sales_id']) continue;
      $input['t_target_id']     = $this->CI->adodb->genID();
      $input['opportunity_jan_01'] = $sheetData[$key]['B'];
      $input['opportunity_feb_02'] = $sheetData[$key]['C'];
      $input['opportunity_mar_03'] = $sheetData[$key]['D'];
      $input['opportunity_apr_04'] = $sheetData[$key]['E'];
      $input['opportunity_may_05'] = $sheetData[$key]['F'];
      $input['opportunity_jun_06'] = $sheetData[$key]['G'];
      $input['opportunity_jul_07'] = $sheetData[$key]['H'];
      $input['opportunity_aug_08'] = $sheetData[$key]['I'];
      $input['opportunity_sep_09'] = $sheetData[$key]['J'];
      $input['opportunity_oct_10'] = $sheetData[$key]['K'];
      $input['opportunity_nov_11'] = $sheetData[$key]['L'];
      $input['opportunity_dec_12'] = $sheetData[$key]['M'];

      $input['proposal_jan_01'] = $sheetData[$key]['N'];
      $input['proposal_feb_02'] = $sheetData[$key]['O'];
      $input['proposal_mar_03'] = $sheetData[$key]['P'];
      $input['proposal_apr_04'] = $sheetData[$key]['Q'];
      $input['proposal_may_05'] = $sheetData[$key]['R'];
      $input['proposal_jun_06'] = $sheetData[$key]['S'];
      $input['proposal_jul_07'] = $sheetData[$key]['T'];
      $input['proposal_aug_08'] = $sheetData[$key]['U'];
      $input['proposal_sep_09'] = $sheetData[$key]['V'];
      $input['proposal_oct_10'] = $sheetData[$key]['W'];
      $input['proposal_nov_11'] = $sheetData[$key]['X'];
      $input['proposal_dec_12'] = $sheetData[$key]['Y'];

      $input['negotiation_jan_01'] = $sheetData[$key]['Z'];
      $input['negotiation_feb_02'] = $sheetData[$key]['AA'];
      $input['negotiation_mar_03'] = $sheetData[$key]['AB'];
      $input['negotiation_apr_04'] = $sheetData[$key]['AC'];
      $input['negotiation_may_05'] = $sheetData[$key]['AD'];
      $input['negotiation_jun_06'] = $sheetData[$key]['AE'];
      $input['negotiation_jul_07'] = $sheetData[$key]['AF'];
      $input['negotiation_aug_08'] = $sheetData[$key]['AG'];
      $input['negotiation_sep_09'] = $sheetData[$key]['AH'];
      $input['negotiation_oct_10'] = $sheetData[$key]['AI'];
      $input['negotiation_nov_11'] = $sheetData[$key]['AJ'];
      $input['negotiation_dec_12'] = $sheetData[$key]['AK'];

      $input['closing_jan_01'] = $sheetData[$key]['AL'];
      $input['closing_feb_02'] = $sheetData[$key]['AM'];
      $input['closing_mar_03'] = $sheetData[$key]['AN'];
      $input['closing_apr_04'] = $sheetData[$key]['AO'];
      $input['closing_may_05'] = $sheetData[$key]['AP'];
      $input['closing_jun_06'] = $sheetData[$key]['AQ'];
      $input['closing_jul_07'] = $sheetData[$key]['AR'];
      $input['closing_aug_08'] = $sheetData[$key]['AS'];
      $input['closing_sep_09'] = $sheetData[$key]['AT'];
      $input['closing_oct_10'] = $sheetData[$key]['AU'];
      $input['closing_nov_11'] = $sheetData[$key]['AV'];
      $input['closing_dec_12'] = $sheetData[$key]['AW'];

      $rs = $this->CI->adodb->autoExecute("t_target", $input, "INSERT");
    }
    $this->logActivity("PROSPECT - UPLOAD", $input, $result);
    return $result;
  }
  function AccountChangeProduct($input=false) {
    $acc    = $this->CI->adodb_billing->getRow("select * from t_account where t_account_id=?", array($input['t_account_id']));
    $accprd = $this->CI->adodb_billing->getRow("select * from t_account_product where t_account_product_id=?", array($input['t_account_product_id']));

    $input['t_prospect_id']   = $this->CI->adodb->genID();
    $input['sys_created_by']  = $this->CI->koje_system->getLoginID();
    $input['sys_created_date']= NOW();
    $input['sales_id']        = $input['sys_created_by'];
    $input['prospect_no']     = $this->genProspectNo();
    $input['status']          = $this->CI->app_lib->getProspectStatus('ACTIVE');
    $input['start_date']      = $input['prospect_start_date'] = date("Y-m-d H:i:s");
    $input['t_stage_id']      = $input['current_stage_id']= $this->CI->app_lib->getFirstStage();
    $input['company_name']    = $acc['company_name'];
    $input['prospect_name']   = $input['name'] = $accprd['name'];
    $input['company_npwp']    = $acc['npwp_company'];
    $input['cp_1_salute']     = 'PSSL-01';
    $input['cp_1_name']       = $acc['bill_cp_name'];
    $input['cp_1_department'] = $acc['d_bill_cp_dept'];
    $input['d_segmentation']  = $acc['d_segmentation'];
    $input['d_customer_group']= $acc['d_customer_group'];
    
    
    //company address
    $addr    = $this->CI->adodb_billing->getRow("select * from t_account_address where t_account_id=? and d_type='ADDTY-04'", array($input['t_account_id']));
    $input['address_street'] = $addr['address_1']!='' ? $addr['address_1'] : $addr['name'];
    $input['address_city_id']= $addr['r_city_id'];
    $input['address_zip']    = $addr['zip'];
    $input['company_phone']  = $addr['telp'];
    

    //technical / install address
    $addr    = $this->CI->adodb_billing->getRow("select * from t_account_address where t_account_id=? and d_type='ADDTY-02'", array($input['t_account_id']));
    //print_r($addr);die;  
    $input['install_address_street']  = $addr['address_1']!='' ? $addr['address_1'] : $addr['name'];
    $input['install_address_city_id'] = $addr['r_city_id'];
    $input['install_address_zip']     = $addr['zip'];
	$input['cp_2_salute']     = 'PSSL-01';
    $input['cp_2_name']       = $acc['tech_cp_name'];;
	$input['cp_2_mobile']  =    $addr['telp'];
	$input['cp_2_email']  =    $addr['email'];
	
	//technical / install billing
    $addrbil    = $this->CI->adodb_billing->getRow("select * from t_account_address where t_account_id=? and d_type='ADDTY-01'", array($input['t_account_id']));
	$input['cp_1_email']     = $addrbil['email'];
	$input['cp_1_mobile']     = $addrbil['telp'];

    $this->ProspectStageInsert($input);
    $rs = $this->CI->adodb->AutoExecute('t_prospect', $input, 'INSERT');

    $input['t_quotation_id']  = $this->CI->adodb->genID();
    $input['status']          = $this->CI->app_lib->getQuotationStatus('PREPARATION');
    $input['expired_date']    = date('Y-m-d', strtotime('+1 month'));
    $input['quotation_no']    = $this->genQuotationNo($input['t_prospect_id']);
    $rs = $this->CI->adodb->AutoExecute('t_quotation', $input, "INSERT");

    $rsaccproditem = $this->CI->adodb_billing->execute("select * from v_account_product_item where t_account_product_id=?", array($input['t_account_product_id']));

    while ($row = $rsaccproditem->fetchRow()) {
      $input1['sys_created_by']  = $input['sys_created_by'];
      $input1['sys_created_date']= $input['sys_created_date'];
      $input1['t_quotation_id']  = $input['t_quotation_id'];
      $input1['t_quotation_item_id'] = $this->CI->adodb->genID();
      $input1['name'] = $row['name'];
      $input1['amount'] = $row['amount'];
      $input1['tax'] = $row['tax'];
      $input1['total'] = $row['amount']+$row['tax'];
      $input1['item_type'] = $row['d_type'];
      $input1['item_type_desc'] = $row['d_type_desc'];
      $input1['t_account_product_item_id']  = $row['t_account_product_item_id'];
      //Diisi dengan default product --> case khusus data tidak lengkap dari billing
      if(!$row['t_product_item_id']) {
        $input1['t_product_item_id']   = $input1['item_type']=='PITTY-01' ? 211 : 212;
      }
      $rs = $this->CI->adodb->AutoExecute('t_quotation_item', $input1, "INSERT");
    }
 
    $result = $this->generateResult($rs);

    $this->logActivity("PROSPECT - INSERT", $input, $result);
    return $result;


  }
  function CalcItemChargeTotal($input=false)
  {
    if(empty($input['item_charge_cnt']))
    {
      return 0;
    }

    $cnt = $input['item_charge_cnt'];
    $i = 1;
    $total = 0;
    while ($i <= $cnt) {
      $item_amount = $this->CI->koje_system->replaceCharacter(getArrayVal($input,'item_amount__'.$i),',');
      $total += (double) $item_amount;
      $i++;
    }
    return $total;
  }
  
  function ProspectDocInsert($params=false) {
    $sysop = $this->CI->koje_system->getLoginID();
    $input = $this->CI->koje_system->nullArrayEmpty($params); 
  
    $input['t_prospect_doc_id'] = $this->CI->adodb->genID('seq_billing');      
    $input['t_file_id'] = $this->uploadFileDB('t_file_id', $input['t_prospect_id'], 'DOCUMENT');
    
    $input['create_by']  = $this->CI->koje_system->getLoginID();
    $input['create_date']= date_now();

    $rs       = $this->CI->adodb->AutoExecute('t_prospect_doc', $input , 'INSERT');
    $result   = $this->generateResult($rs);
    $this->logActivity("PROSPECT DOC INSERT", $input, $result);
    return $result;
  }
  function ProspectDocEdit($params=false) {
	
	$input    = $this->CI->koje_system->nullArrayEmpty($params);
	$input['modify_by']  = $this->CI->koje_system->getLoginID();
	$input['modify_date'] = date_now();
	$input['t_file_id'] = $this->uploadFileDB('t_file_id', $input['t_prospect_id'], 'DOCUMENT');
	
	$rs = $this->CI->adodb->AutoExecute('t_prospect_doc',$input,'UPDATE','t_prospect_doc_id'.'='.$input['t_prospect_doc_id']);
	$result   = $this->generateResult($rs);
		$this->logActivity("PROSPECT DOC EDIT", $input, $result);
		return $result;
  }	

  function ProspectDocDelete($params=false) {
    $input    = $this->CI->koje_system->nullArrayEmpty($params);
    $input['modify_by']  = $this->CI->koje_system->getLoginID();
    $input['modify_date']= date_now();
    $rs       = $this->CI->adodb->execute('delete from t_prospect_doc where t_prospect_doc_id'.'='.$input['t_prospect_doc_id']);
    $result   = $this->generateResult($rs);
    $rs       = $this->CI->adodb->execute('delete from t_file where t_file_id'.'='.$input['t_file_id']);
    $result   = $this->generateResult($rs);
    $this->logActivity("PROSPECT DOC DELETE", $input, $result);
    return $result;
   }
   
   function uploadFileDB($id, $name, $type) {
    if($_FILES[$id]['name']) {
      $ext = pathinfo($_FILES[$id]['name'], PATHINFO_EXTENSION);
      $fname = rand().'.'.$ext;
      $folder = $this->CI->koje_config['KOJE_FOLDER_UPLOAD'];
      $dest = $folder.$fname; 
      $input = array();
      $input['t_file_id'] = $this->CI->adodb->genID();
	  
	  $input['file_type'] = $_FILES[$id]['type'];
      $input['filename'] = $fname;
      $input['file_name'] = $fname;
      $input['file_ext'] = '.'.$ext;
      $input['file_path'] = $fname;
      $input['full_path'] = $fname;
	  
	  
      $rs = $this->CI->adodb->AutoExecute("t_file", $input, "INSERT");
      
      $content = file_get_contents($_FILES[$id]['tmp_name']);
      $rs1=$this->CI->adodb->UpdateBlob('t_file', 'content', $content, 't_file_id='.$input['t_file_id']);
      $this->CI->adodb->execute("update t_file set filename=? where t_file_id=?", array($dest,$input['t_file_id']));
      rename($_FILES[$id]['tmp_name'],$dest);
      return $input['t_file_id'];
    }
    return false;   	
  }
}
