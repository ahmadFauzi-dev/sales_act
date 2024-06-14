<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


class App_lib {
  var $CI;
  var $arrMonth = array('jan_01','feb_02','mar_03','apr_04','may_05','jun_06','jul_07','aug_08','sep_09','oct_10','nov_11','dec_12');
  var $arrMonthLabel = array('January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December');
  var $arrStage = array('opportunity','proposal','negotiation','closing');
  function __construct() {
    $this->CI =& get_instance();
  }
/* -------------------------------- table record ---------------------------*/
function getReferenceList($str, $parent_id=false, $order=2, $value=false) {
    $where = $parent_id ? " AND parent_id=$parent_id" : "";
    $where.= $value ? " AND upper(value)=upper('$value')" : "";
    $rows  = $this->CI->adodb->getAssoc("select val, description from t_reference where group_reference=? $where order by priority, ?",
                                  array($str, $order));
    return $rows;
  }

function getReferenceDoc($str,$docType) {
    switch($docType){
		case 'PRESALES' :
			$rows  = $this->CI->adodb->getAssoc("select val, description from t_reference where val in ('DOCC-05','DOCC-07') order by priority");
		break;
		
		default :
			$rows  = $this->CI->adodb->getAssoc("select val, description from t_reference where val in ('DOCC-01','DOCC-02','DOCC-03','DOCC-04','DOCC-06') order by priority");
		break;
	}
	return $rows;
  }
function getProviderNo() {  
  return 1; 
}
function getFirstStage($label=false)
  {
    $col = $label ? "name" : "t_stage_id";
    $id = $this->CI->adodb->getOne("select $col from t_stage where t_prev_stage_id is null");
    return $id;
  }
  function getNextStage($t_stage_id,$label=false)
  {
    $col = $label ? "name" : "t_stage_id";
    $id = $this->CI->adodb->getOne("select $col from t_stage where t_prev_stage_id=$t_stage_id");
    return $id;
  }
  function getSalesIDByEmail($email=false) {
    $id = $this->CI->adodb->getOne("select id from sys_users where lower(email)=lower('$email')");
    return $id;
  }
/********************************** REFERENCE ******************************/
function getCompanySimilar($company_name, $t_prospect_id=-1) {
    $cname = $this->CI->adodb->getOne(
    " select company_name from t_prospect
      where status='PSST-01' and t_prospect_id <> $t_prospect_id and
            regexp_replace(regexp_replace(upper(company_name), '[^A-Z0-9]', '', 'gi'), '^(PT)|(PT)$','','gi')=
            regexp_replace(regexp_replace(upper(?), '[^A-Z0-9]', '', 'gi'), '^(PT)|(PT)$','','gi')
    ", array($company_name));
    if($cname) {
      $cname = '<table style="background-color:white"><tr><td><font color=red><i class="fa fa-exclamation-triangle"></i> FOUND COMPANY NAME SIMILAR : ' . $cname.'</font></td></tr></table>';
    }
    return $cname;
  }
  function getProspectStatus($status)
  {
    switch ($status) {
      case 'ACTIVE':
        $status = 'PSST-01';
        break;
      case 'DONE':
        $status = 'PSST-02';
        break;
      case 'DROP':
        $status = 'PSST-03';
        break;
      case 'CANCEL':
        $status = 'PSST-04';
        break;
    }
    return $status;
  }

  function getIDStage($str)
  {
    switch ($str) {
      case 'OPPORTUNITY':
        return 1;
      case 'PROPOSAL':
        return 3;
      case 'NEGOTIATION':
        return 5;
      case 'CLOSING':
        return 7;
      case 'CANCEL':
        return 9;
    }
  }
  function getQuotationStatus($status)
  {
    switch ($status) {
      case 'PREPARATION':
        return 'QTST-01';
      case 'REQUEST':
        return 'QTST-02';
      case 'APPROVED':
        return 'QTST-03';
      case 'ESCALATION':
        return 'QTST-04';
      case 'REJECTED':
        return 'QTST-05';
      case 'CANCELLED':
        return 'QTST-06';
      case 'DEAL':
        return 'QTST-07';
    }
  }
  function getSurveyStatus($status)
  {
    switch ($status) {
      case 'REQUEST':
        $status = 'SVST-01';
        break;
      case 'ONPROGRESS':
        $status = 'SVST-02';
        break;
      case 'FINISHED':
        $status = 'SVST-03';
        break;
      case 'CANCEL':
        $status = 'SVST-04';
        break;
    }
    return $status;
  }
	
  function getROList()
  {
    $result = $this->CI->adodb->getAssoc("select r_ro_id, name from r_ro order by r_ro_id");
    return $result;
  }
  
  function getaccountList()
  {
    $result = $this->CI->adodb->getAssoc("select t_account_id, company_name from t_accounts where account_status = 'CUST-02'order by company_name");
    return $result;
  }

/* -------------------------- START OF DB BILLING ----------------------------------*/
  function getBillingCity()
  {
    $result = $this->CI->adodb_billing->getAssoc("select r_city_id, r_state_name||' - '||r_city_name as name from v_city order by r_state_name, r_city_name");
    return $result;
  }
  function getBillingCityName($r_city_id)
  {
    $result = $this->CI->adodb_billing->getOne("select name from r_city where r_city_id=$r_city_id");
    return $result;
  }
  function getBillingProvinceName($r_city_id)
  {
    $result = $this->CI->adodb_billing->getAssoc("select r_state_name from v_city where r_city_id=$r_city_id");
    return $result;
  }
  function getBillingSegmentation()
  {
    $result = $this->CI->adodb_billing->getAssoc("select r_customer_segment_id, name from r_customer_segment");
    return $result;
  }
  function getBillingGroup()
  {
    $result = $this->CI->adodb_billing->getAssoc("select r_customer_group_id, name from r_customer_group");
    return $result;
  }
  function getDataStageAssoc($cols="t_stage_id, name")
  {
    $row = $this->CI->adodb->GetAssoc("select $cols from t_stage order by t_prev_stage_id nulls first");
    return $row;
  }
  function getDataLeads($t_account_id)
  {
    $row = $this->CI->adodb->GetRow("select * from v_account where t_account_id = $t_account_id");
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }
  function getDataAccount($t_account_id)
  {
    $row = $this->CI->adodb->GetRow("select * from v_account where t_account_id = $t_account_id");
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }
  function getDataProductAssoc($cols="*")
  {
    $row = $this->CI->adodb_billing->GetAssoc("select t_product_id, name from t_product where status='A' order by name");
    return $row;
  }
  function getDataProductServiceAssoc($cols="*")
  {
    $row = $this->CI->adodb->GetAssoc("select t_product_service_id, concat_ws(' - ',code,name) as name from billing_product_service order by name");
    return $row;
  }

  function getDataProductServiceDesc($t_product_service_id, $col)
  {
    $row = $this->CI->adodb_billing->getOne("select $col from t_product_service where t_product_service_id=$t_product_service_id");
    return $row;
  }
  function getDataProductItemType($t_product_item_id)
  {
    $row = $this->CI->adodb_billing->getOne("select d_type from t_product_item where t_product_item_id=$t_product_item_id");
    return $row;
  }
  function getDataProductItemTypeDesc($val)
  {
    $row = $this->CI->adodb_billing->getOne("select val from ref_domain where group_name='PRODUCT_ITEM__TYPE' and val='$val'");
    return $row;
  }
  /* -------------------------- END OF DB BILLING ----------------------------------*/
  function getDataServiceGroupList()
  {
    $rs = $this->CI->adodb->execute("select * from t_reference where group_reference=? order by priority",array('SERVICE_GROUP__LIST'));
    $data = array();
    while($row = $rs->fetchRow())
    {
      $data[$row['val']] = array($row['description'],false);
    }
    return $data;
  }
  function getDataProspect($t_prospect_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_prospect where t_prospect_id=?",array($t_prospect_id));
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }
  function getDataProspectCurrent($t_prospect_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_prospect_current where t_prospect_id=?",array($t_prospect_id));
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }
  
  function getDataLeadsCurrent($t_account_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_account where t_account_id=?",array($t_account_id));
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }
  
  function getDataAccountCurrent($t_account_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_account where t_account_id=?",array($t_account_id));
    foreach ($row as $key => $value) {
      $row[$key] = $this->CI->koje_system->removeZeroTime($row[$key]);
    }
    return $row;
  }

  function getDataTelemarketing($t_telemarketing_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_telemarketing where t_telemarketing_id=?",array($t_telemarketing_id));
    return $row;
  }

  function getDataProspectNextStage($t_prospect_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select * from t_prospect where t_prospect_id=?",array($t_prospect_id));
    if(isset($row['current_stage_id'])){
      $row1 = $this->CI->adodb->getRow("select * from v_stage where t_stage_id=?", $this->getNextStage($row['current_stage_id']));
    }
    else {
      $row1 = array();
    }
    return $row1;
  }

  function getDataProposal($t_proposal_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_proposal where t_proposal_id=?",array($t_proposal_id));
    return $row;
  }

  function getFileInfo($t_file_id, $field="*") {
    $row = $this->CI->getRow("select $field from t_file where t_file_id=?",array($t_file_id));
    return $row;
  }

  function getDataPresentation($t_presentation_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_presentation where t_presentation_id=?",array($t_presentation_id));
    return $row;
  }
  function getDataProspectMeeting($t_prospect_meeting_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_prospect_meeting where t_prospect_meeting_id=?",array($t_prospect_meeting_id));
    return $row;
  }
  function getDataRequirement($t_requirement_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_requirement where t_requirement_id=?",array($t_requirement_id));
    return $row;
  }
  function getDataQuotation($t_quotation_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_quotation where t_quotation_id=?",array($t_quotation_id));
    return $row;
  }
  function getDataQuotationDetail($t_quotation_id, $cols="*")
  {
    $rows = $this->CI->adodb->GetAssoc("select $cols from v_quotation_detail where t_quotation_id=?",array($t_quotation_id));
    return $rows;
  }
  function getDataNegotiation($t_negotiation_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_negotiation where t_negotiation_id=?",array($t_negotiation_id));
    return $row;
  }
  function getDataSurvey($t_survey_id, $cols="*")
  {
    $arr = $this->CI->adodb->GetRow("select $cols from t_survey where t_survey_id=?",array($t_survey_id));
    $rs = $this->CI->adodb->execute("select * from t_reference where group_reference=? order by priority",array('SERVICE_GROUP__LIST'));
    while($row = $rs->fetchRow())
    {
      $row1 = $this->CI->adodb->GetRow("select * from t_survey_{$row['val']} where t_survey_id=?",array($t_survey_id));
      $arr  = array_merge($arr, $row1);
    }
    return $arr;
  }

  function getDataTarget($t_target_id, $cols="*")
  {
    $row = $this->CI->adodb->GetRow("select $cols from v_target where t_target_id=?",array($t_target_id));
    return $row;
  }
  function getTotalProspectActive($sales_id)
  {
    $cnt = $this->CI->adodb->getOne("select count(*) from t_prospect where sales_id=$sales_id and status='PSST-01' and current_stage_id in(1,3,5)");
    return $cnt;
  }
  function getTotalOpportunityActive($sales_id)
  {
    $cnt = $this->CI->adodb->getOne("select count(*) from t_prospect where sales_id=$sales_id and status='PSST-01'  and current_stage_id=1");
    return $cnt;
  }
  function getTotalProposalActive($sales_id)
  {
    $cnt = $this->CI->adodb->getOne("select count(*) from t_prospect where sales_id=$sales_id and status='PSST-01'  and current_stage_id=3");
    return $cnt;
  }
  function getTotalNegotiationActive($sales_id)
  {
    $cnt = $this->CI->adodb->getOne("select count(*) from t_prospect where sales_id=$sales_id and status='PSST-01'  and current_stage_id=5");
    return $cnt;
  }
/********************************** Info Table ******************************/
  function getInfoLead($t_prospect_id) {
    $row = $this->getDataProspectCurrent($t_prospect_id);
    $str = html_table_form(
                array(
                  'data' => $row,
                  'title' => 'Account Info',
                  'cols' => array(
                                array(
                                      'company_name',
        															'company_phone',
                                      'company_address_info',
                                ),
                                array(
                                      'cp_1_salute_desc',
                                      'cp_1_name',
                                      'cp_1_mobile',
                                      'cp_1_email',
                                ),
                                array(
                                      'source',
                                      'description',
                                      'stage_name',
                                ),
                        )
                  )
            );
      return $str;
  }

  function getInfoProspect($t_prospect_id) {
    $row = $this->getDataProspectCurrent($t_prospect_id);
    $str = html_table_form(
                array(
                  'id' => 'tableProspect',
                  'data' => $row,
                  'title' => 'Prospect Info',
                  'cols' => array(
                              array('<b><u>Prospect Info</u></b>',
                                    'prospect_no',
                                    'prospect_name',
                                    'stage_name',
                                    'probability_pcts',
                                    'target_amount',
                                    'target_closed_date',
                                    'source_desc',
                                    'description',
                                    '<b><u>Current Service Info</u></b>',
                                    'company_curr_service',
                                    'company_curr_provider',
                                    'company_curr_expense'
                              ),
                              array('<b><u>Company Info</u></b>',
                                    'company_name',
                                    'company_npwp',
                                    'company_phone',
                                    'company_address_info',
                                    'segmentation_desc',
                                    'customer_group_desc',
                                    '<b><u>Address Info</u></b>',
                                    'address_area',
                                    'address_street',
                                    'address_city',
                                    'address_province',
                                    'address_zip'
                              ),
                              array('<b><u>Contact 1</u></b>',
                                    'cp_1_salute_desc',
                                    'cp_1_name',
                                    'cp_1_department_desc',
                                    'cp_1_mobile',
                                    'cp_1_email',
                                    'cp_1_info',
                                    '<b><u>Contact 2</u></b>',
                                    'cp_2_salute_desc',
                                    'cp_2_name',
                                    'cp_2_department_desc',
                                    'cp_2_mobile',
                                    'cp_2_email',
                                    'cp_2_info'
                              ),
                      )
                  )
            );
      return $str;
    }
  
  function getInfoLeads($t_account_id) {
    $row = $this->getDataLeadsCurrent($t_account_id);
    $str = html_table_form(
                array(
                  'id' => 'tableLeads',
                  'data' => $row,
                  'title' => 'Leads Info',
                  'cols' => array(
							  array('<b><u>General Information</u></b>',
                                    'source_desc',
                                    'prospect_desc',
                                    'industry_desc',
                                    'region_desc',
									'<b><u>Company Info</u></b>',
                                    'company_name',
                                    'company_phone',
                                    'company_address_info',
                              ),
                              array('<b><u>Contact Personal 1</u></b>',
                                    'cp_1_salute_desc',
                                    'cp_1_name',
                                    'cp_1_mobile',
                                    'cp_1_email'
                              )
                      )
                  )
            );
      return $str;
    }
	
	function getInfoAccount($t_account_id) {
    $row = $this->getDataLeadsCurrent($t_account_id);
    $str = html_table_form(
                array(
                  'id' => 'tableLeads',
                  'data' => $row,
                  'title' => 'Accounts Info',
                  'cols' => array(
							  array('<b><u>General Information</u></b>',
                                    'source_desc',
                                    'prospect_desc',
                                    'industry_desc',
                                    'region_desc',
									'<b><u>Company Info</u></b>',
                                    'company_name',
                                    'company_npwp',
                                    'company_phone',
                                    'company_address_info',
									'address_area',
									'address_street',
									'address_city',
									'address_province',
									'address_zip',
                              ),
                              array('<b><u>Contact Personal 1</u></b>',
                                    'cp_1_salute_desc',
                                    'cp_1_department',
                                    'cp_1_name',
                                    'cp_1_mobile',
                                    'cp_1_email',
                                    'cp_1_info',
									'<b><u>Contact Personal 2</u></b>',
                                    'cp_2_salute_desc',
                                    'cp_2_department',
                                    'cp_2_name',
                                    'cp_2_mobile',
                                    'cp_2_email',
                                    'cp_2_info'
                              )
                      )
                  )
            );
      return $str;
    }

  function listQuotationItem($params,$status=false) {
    $t_prospect_id    = getArrayVal($params,'t_prospect_id');
    $t_quotation_id   = getArrayVal($params,'t_quotation_id');
    $wstatus = $status ? "and status='".$this->CI->app_lib->getQuotationStatus($status)."'" : "";
    if($t_quotation_id) {
      $sum_total = $this->CI->adodb->getOne("select sum_total from v_quotation where t_quotation_id=?", array($t_quotation_id));
      $input['data']    = $this->CI->adodb->getAll("select quotation_no, name, status_desc, item_type_desc, utils_number_fmt(amount) amount, utils_number_fmt(tax)tax, utils_number_fmt(total)total from v_quotation_item where t_quotation_id=? order by t_quotation_item_id", array($t_quotation_id));
    }
    else {
      $sum_total = $this->CI->adodb->getOne("select sum(sum_total) from v_quotation where t_prospect_id=? $wstatus", array($t_prospect_id));
      $input['data']    = $this->CI->adodb->getAll("select quotation_no, name, status_desc, item_type_desc, utils_number_fmt(amount) amount, utils_number_fmt(tax)tax, utils_number_fmt(total)total from v_quotation_item where t_prospect_id=? $wstatus order by t_quotation_item_id", array($t_prospect_id));
    }
    $input['cols']    = array('quotation_no','name', 'status_desc', 'item_type_desc','amount','tax','total');
    $input['heading'] = array('Quotation No','Item Name', 'Status', 'Type','Amount','Tax','Total');
    $input['data'][]  = array('quotation_no'=>'', 'name'=>'','status_desc'=>'','item_type_desc'=>'<b>T O T A L</b>','amount'=> '<b>'.$this->CI->koje_system->number_format($sum_total).'</b>','tax'=>'','total'=>'<b>'.$this->CI->koje_system->number_format($sum_total).'</b>');
    $input['caption'] = 'ITEM CHARGE';
    return html_table($input);
  }
  function listRequirement($params)
  {
    $t_prospect_id    = getArrayVal($params,'t_prospect_id');
    $t_quotation_id   = getArrayVal($params,'t_quotation_id');
    if(!$t_prospect_id && $t_quotation_id)
    {
      $t_prospect_id = $this->CI->adodb->getOne("select t_prospect_id from t_quotation where t_quotation_id=$t_quotation_id");
    }
    $input['data']    = $this->CI->adodb->getAll("select product_code, product_name, speed, uom_desc, priority_desc from v_requirement where t_prospect_id=? order by product_code", array($t_prospect_id));
    $input['cols']    = array('product_code','product_name', 'speed', 'uom_desc','priority_desc');
    $input['heading'] = array('Code','Name', 'Speed', 'UoM','priority');
    $input['caption'] = 'SERVICE LIST';
    return html_table($input);
  }
  function listQuotationNegotiation($params=false) {
    $t_prospect_id = getArrayVal($params,'t_prospect_id');
    $approved = $this->CI->app_lib->getQuotationStatus('APPROVED');
    $deal = $this->CI->app_lib->getQuotationStatus('DEAL');
    $sum = $this->CI->adodb->getRow("select sum(sum_total) sum_total, sum(sum_amount) sum_amount from v_quotation where status in('$deal','$approved') and t_prospect_id=?", array($t_prospect_id));
    $input['data']    = $this->CI->adodb->getAll("select t_quotation_id, quotation_no, name, status, utils_number_fmt(sum_amount) amount, utils_number_fmt(sum_total) total, description from v_quotation where status in('$deal','$approved') and t_prospect_id=? order by quotation_no, name", array($t_prospect_id));

    $smr_qty = 0;
    $smr_deal  = 0;
    $smr_no_deal = 0;
    foreach ($input['data'] as $key => $value) {
      $id = $value['t_quotation_id'];
      $v = str_replace(',','',$value['amount']);
      if($value['status']==$deal)
      {
        $stY = 'checked';
        $stN = '';
        $smr_deal = $smr_deal + $v;
      }
      else {
        $stY = '';
        $stN = 'checked';
        $smr_no_deal = $smr_no_deal + $v;
      }

      $input['data'][$key]['t_quotation_id'] =
        "<input type=hidden id=amn_{$id} value={$value['amount']}> <input type='radio' name='qid_{$id}' $stY value='$deal'> Yes
          <input type='radio' name='qid_{$id}' $stN value='$approved'> Not";
    }

    $input['cols']    = array('t_quotation_id','quotation_no','name', 'description','amount','total');
    $input['heading'] = array('Deal','Quotation No','Name', 'Description','Amount','Total');
    $input['data'][]  = array('t_quotation_id'=>'',
                              'quotation_no'=>'',
                              'name'=>'',
                              'description'=>'<b>G R A N D - T O T A L</b>',
                              'amount'=>'<b>'.$this->CI->koje_system->number_format($sum['sum_amount']).'</b>',
                              'total' =>'<b>'.$this->CI->koje_system->number_format($sum['sum_total']).'</b>'
                        );
    $summary = <<<EOF
    <table class='table table-bordered'>
      <tr><th colspan=2>SUMMARY</th></tr>
      <tr><td width=10%>DEAL</td><td><b><span id=deal>{$smr_deal}</span></b></td></tr>
      <tr><td>NOT-DEAL</td><td><b><span id=not_deal>{$smr_no_deal}</span></b></td></tr>
    </table>
    <script>
      function calc()
      {
        deal = 0;
        not_deal = 0;
        total = 0;
        $(":radio[name^='qid_']:checked").each(function(){
            id = this.name.replace('qid_','amn_');
            val = intVal($("#"+id).val());
            if(this.value=='$deal')
            {
              deal = deal + val
            }
            else {
              not_deal = not_deal + val;
            }
            total = total + val;
        });
        $("#deal").html(stringToCurrency(deal));
        $("#not_deal").html(stringToCurrency(not_deal));
      }
      $(document).ready(function(){
        calc();
        $(":radio[name^='qid_']").change(function(){
          calc();
        });
      });
    </script>
EOF;
    return html_table($input).$summary;
  }
  function generateRequirement($input, $addRow) {
     $AddRowCnt = 5;
     $cnt = 0;
     $data = array();
     $js = "";
     $js1= "";
     $t_prospect_id = $this->CI->koje_system->getArrayVal($input,'t_prospect_id',false);
     if($t_prospect_id) {
       $rs = $this->CI->adodb->execute("select * from v_requirement where t_prospect_id=? order by product_code", array($input['t_prospect_id']));
       $i=1;
       while($row = $rs->fetchRow()) {
         $data[] = $row;
         $js1 .= '$("#t_product_service_id__'.$i.'").val('.$row['t_product_service_id'].');';
         $i++;
       }
      $cnt = $i-1;
     }
     $cnt = $cnt+$AddRowCnt;
     $str = "<div id='list_requirement' class='block box box-body table-responsive koje-no-border'>
 							<input type='hidden' id='requirement_cnt' name='requirement_cnt' value='$cnt' />
 							<table class='table table-sm'>
 						<tr>
                 <th>No.</th>
                 <th>Product</th>
                 <th>Name</th>
                 <th>Speed/Quantity</th>
                 <th>UoM</th>
                 <th>Priority</th>
               </tr>
 					 ";
    $rs = $this->CI->adodb_billing->execute("select t_product_service_id, concat_ws('*',product_code,product_name, service_name) as name from t_product_service order by product_code");

    $arr[] = array(
                'id' => "",
                'text' => "PRD-CODE*PRODUCT NAME*SERVICE NAME"
            );
    $arr[] = array(
                    'id' => "",
                    'text' => "",
                );
    while($row = $rs->fetchRow())
    {
      $arr[] = array(
                  'id' => $row['t_product_service_id'],
                  'text' => $row['name']
              );
    }
    $options = json_encode($arr);
    $js .= <<<EOF
           var el = $('select[id^=t_product_service_id__');
           el.select2({
                             dropdownAutoWidth : true,
                             width: '100px',
                             templateResult: function(data) {
                                     var r = data.text.split('*');
                                     var s = '';
                                     var w = ['10%','45%','45%'];
                                     for(i=0;i<r.length;i++) {
                                       s = s + '<td nowrap width=\"'+w[i]+'\">' + r[i] + '</td>';
                                     }
                                     var \$result = $('<table width=\"700px\" border=0><tr>' + s + '</tr></table>');
                                     return \$result;
                                 }
                         });
           var data = $options;
           var i, l, html, opt;
           html = '';
           var disabled='disabled';
           for(i = 0, l = data.length; i < l; i++) {
               opt = data[i];
               html += '<option '+disabled+' value=\"'+ opt.id+'\">'+ opt.text + '</option>';
               disabled='';
           }
           el.html(html);
           $js1
           el.trigger('change');

          $('.select2-selection').addClass('dt-select2-filter').addClass('col-sm-12');
          el.on('change', function(){
              var id = $(this).attr('id');
              var nm = id.replace('t_product_service_id','name');
              var sp = id.replace('t_product_service_id','speed');
              var uo = id.replace('t_product_service_id','uom');
              var pr = id.replace('t_product_service_id','priority');
              var data = $('#'+id+' option:selected').text().split('*');
              if(data.length<2) {
                $('#'+nm).val('');
                $('#'+sp).val('');
                $('#'+uo).val('');
                $('#'+pr).val('');
              }
              else {
                $('#'+nm).val(data[1]+' - ' +data[2]);
                $('#'+sp).val('');
                $('#'+uo).val('UOM-01');
                $('#'+pr).val('RQPR-02');
              }
          });
EOF;

    $priority = array_merge(array(""=>getlabel('select_none')),$this->CI->koje_system->getRefDomainList('REQUIREMENT__PRIORITY'));
    $uom      = array_merge(array(""=>getlabel('select_none')),$this->CI->koje_system->getRefDomainList('REQUIREMENT__UOM'));

    for($i=1;$i<=$cnt;$i++) {
      $ri      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_requirement_id');
      $pi      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_product_service_id');
      $inm     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'product_name');
      $isp     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'speed');
      $iuo     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'uom');
      $ipr     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'priority');

      $fh_qd   = form_hidden("t_requirement_id__$i",$ri);
      $dd_pi   = form_dropdown("t_product_service_id__$i", array(), $pi, "id='t_product_service_id__$i' class='form-control'");
      $fi_nm   = form_input(array("name"=>"name__$i","id"=>"name__$i","class"=>"form-control","readonly"=>"true"),$inm);
      $fi_sp   = form_input(array("name"=>"speed__$i","id"=>"speed__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$isp);
      $dd_uo   = form_dropdown("uom__$i",$uom,$iuo,"id='uom__$i' class='form-control'");
      $dd_pr   = form_dropdown("priority__$i",$priority,$ipr,"id='priority__$i' class='form-control'");

			$str.=
				"<tr><td width='3%'><label class='control-label'>$i.</label>$fh_qd</td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$dd_pi</div></td>
            <td width='40%'><div class='col-sm-12 form-group input-group'>$fi_nm</div></td>
            <td width='15%'><div class='col-sm-12 form-group input-group'>$fi_sp</div></td>
            <td width='17%'><div class='col-sm-12 form-group input-group'>$dd_uo</div></td>
						<td width='15%'><div class='col-sm-12 form-group input-group'>$dd_pr</div></td>
				</tr>
			";
		}
		$str .= "</table>";
    if($addRow)
    {
      $str .= "<button name=submitAddRow value=1 class='btn btn-raised btn-sm btn-round btn-primary'>".getLabel('BTN_SAVE_ADDROW')."</button>";
    }
    $str .="</div>";

    $str .="
		<script>
      $(document).ready(function(){
        $js
      });
		</script>
		";
		return $str;
	}
	
	function generatePreRequirement($input, $addRow) {
     $AddRowCnt = 5;
     $cnt = 0;
     $data = array();
     $js = "";
     $js1= "";
     $t_prospect_id = $this->CI->koje_system->getArrayVal($input,'t_prospect_id',false);
     if($t_prospect_id) {
       $rs = $this->CI->adodb->execute("select * from v_requirement where t_prospect_id=? order by product_code", array($input['t_prospect_id']));
       $i=1;
       while($row = $rs->fetchRow()) {
         $data[] = $row;
         $js1 .= '$("#t_product_service_id__'.$i.'").val('.$row['t_product_service_id'].');';
         $i++;
       }
      $cnt = $i-1;
     }
     $cnt = $cnt+$AddRowCnt;
     $str = "<div id='list_requirement' class='block box box-body table-responsive koje-no-border'>
 							<input type='hidden' id='requirement_cnt' name='requirement_cnt' value='$cnt' />
 							<table class='table table-sm'>
 						<tr>
                 <th>No.</th>
                 <th>Product</th>
                 <th>Name</th>
                 <th>Speed/Quantity</th>
                 <th>UoM</th>
                 <th>Priority</th>
               </tr>
 					 ";
    $rs = $this->CI->adodb_billing->execute("select t_product_service_id, concat_ws('*',product_code,product_name, service_name) as name from t_product_service order by product_code");

    $arr[] = array(
                'id' => "",
                'text' => "PRD-CODE*PRODUCT NAME*SERVICE NAME"
            );
    $arr[] = array(
                    'id' => "",
                    'text' => "",
                );
    while($row = $rs->fetchRow())
    {
      $arr[] = array(
                  'id' => $row['t_product_service_id'],
                  'text' => $row['name']
              );
    }
    $options = json_encode($arr);
    $js .= <<<EOF
           var el = $('select[id^=t_product_service_id__');
           el.select2({
                             dropdownAutoWidth : true,
                             width: '100px',
                             templateResult: function(data) {
                                     var r = data.text.split('*');
                                     var s = '';
                                     var w = ['10%','45%','45%'];
                                     for(i=0;i<r.length;i++) {
                                       s = s + '<td nowrap width=\"'+w[i]+'\">' + r[i] + '</td>';
                                     }
                                     var \$result = $('<table width=\"700px\" border=0><tr>' + s + '</tr></table>');
                                     return \$result;
                                 }
                         });
           var data = $options;
           var i, l, html, opt;
           html = '';
           var disabled='disabled';
           for(i = 0, l = data.length; i < l; i++) {
               opt = data[i];
               html += '<option '+disabled+' value=\"'+ opt.id+'\">'+ opt.text + '</option>';
               disabled='';
           }
           el.html(html);
           $js1
           el.trigger('change');

          $('.select2-selection').addClass('dt-select2-filter').addClass('col-sm-12');
          el.on('change', function(){
              var id = $(this).attr('id');
              var nm = id.replace('t_product_service_id','name');
              var sp = id.replace('t_product_service_id','speed');
              var uo = id.replace('t_product_service_id','uom');
              var pr = id.replace('t_product_service_id','priority');
              var data = $('#'+id+' option:selected').text().split('*');
              if(data.length<2) {
                $('#'+nm).val('');
                $('#'+sp).val('');
                $('#'+uo).val('');
                $('#'+pr).val('');
              }
              else {
                $('#'+nm).val(data[1]+' - ' +data[2]);
                $('#'+sp).val('');
                $('#'+uo).val('UOM-01');
                $('#'+pr).val('RQPR-02');
              }
          });
EOF;

    $priority = array_merge(array(""=>getlabel('select_none')),$this->CI->koje_system->getRefDomainList('REQUIREMENT__PRIORITY'));
    $uom      = array_merge(array(""=>getlabel('select_none')),$this->CI->koje_system->getRefDomainList('REQUIREMENT__UOM'));

    for($i=1;$i<=$cnt;$i++) {
      $ri      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_requirement_id');
      $pi      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_product_service_id');
      $inm     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'product_name');
      $isp     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'speed');
      $iuo     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'uom');
      $ipr     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'priority');

      $fh_qd   = form_hidden("t_requirement_id__$i",$ri);
      $dd_pi   = form_dropdown("t_product_service_id__$i", array(), $pi, "id='t_product_service_id__$i' class='form-control'");
      $fi_nm   = form_input(array("name"=>"name__$i","id"=>"name__$i","class"=>"form-control","readonly"=>"true"),$inm);
      $fi_sp   = form_input(array("name"=>"speed__$i","id"=>"speed__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$isp);
      $dd_uo   = form_dropdown("uom__$i",$uom,$iuo,"id='uom__$i' class='form-control'");
      $dd_pr   = form_dropdown("priority__$i",$priority,$ipr,"id='priority__$i' class='form-control'");

			$str.=
				"<tr><td width='3%'><label class='control-label'>$i.</label>$fh_qd</td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$dd_pi</div></td>
            <td width='40%'><div class='col-sm-12 form-group input-group'>$fi_nm</div></td>
            <td width='15%'><div class='col-sm-12 form-group input-group'>$fi_sp</div></td>
            <td width='17%'><div class='col-sm-12 form-group input-group'>$dd_uo</div></td>
						<td width='15%'><div class='col-sm-12 form-group input-group'>$dd_pr</div></td>
				</tr>
			";
		}
		$str .= "</table>";
    if($addRow)
    {
      $str .= "<button name=submitAddRow value=1 class='btn btn-raised btn-sm btn-round btn-primary'>".getLabel('BTN_SAVE_ADDROW')."</button>";
    }
    $str .="</div>";

    $str .="
		<script>
      $(document).ready(function(){
        $js
      });
		</script>
		";
		return $str;
	}
	
  function getOportunityDoc($input) 
	{
		$acid = $input['t_prospect_id'];

		$table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTable(
                  array(
                    'id' 		=> PAGER::P2,
                    'search'=> '',
                    'title' => "Document List",
                    'primary_key' => 't_prospect_doc_id',
                    "query" => array( "from" 	=> "FROM v_prospect_documents",
                                      "where"  => "WHERE t_prospect_id=$acid",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => array('docUpload' 	=> array(	'title' => getLabel('BTN_UPLOAD_DOC'),'url' => $this->CI->koje_system->URLBuild(false,'insertDoc',"t_prospect_id=$acid"))),
                    'columns' => array(
                                    '_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
                                                            'button'=> array(
                                                            'editDoc' 	=> array(	'title' => getLabel('BTN_EDIT'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'editDoc',"t_prospect_id=$acid"),
                                                                            ),
                                                            'deleteDoc' 	=> array(	'title' => getLabel('BTN_DELETE'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'deleteDoc',"t_prospect_id=$acid"),
                                                                          ),
                                                      )
                                                  ),

                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
	}
	
	function getOportunityDoc1($input) 
	{
		$acid = $input;
		$table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTableView(
                  array(
                    'id' 		=> PAGER::P4,
                    'search'=> '',
                    'title' => "Document Attachment",
                    'primary_key' => 't_prospect_doc_id',
                    "query" => array( "from" 	=> "FROM v_prospect_documents",
                                      "where"  => "WHERE t_prospect_id=$acid ",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => array(),
                    'columns' => array(
                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
	}
	
	function getOportunityPreDoc($input) 
	{
		$acid = $input['t_prospect_id'];
		$table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTable(
                  array(
                    'id' 		=> PAGER::P3,
                    'search'=> '',
                    'title' => "Presales Document Attachment",
                    'primary_key' => 't_prospect_doc_id',
                    "query" => array( "from" 	=> "FROM v_prospect_documents",
                                      "where"  => "WHERE t_prospect_id=$acid",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => array('docUpload' 	=> array(	'title' => getLabel('BTN_UPLOAD_DOC'),'url' => $this->CI->koje_system->URLBuild(false,'insertDoc',"t_prospect_id=$acid"))),
                    'columns' => array(
                                    '_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
                                                            'button'=> array(
                                                            'editDoc' 	=> array(	'title' => getLabel('BTN_EDIT'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'editDoc',"t_prospect_id=$acid"),
                                                                            ),
                                                            'deleteDoc' 	=> array(	'title' => getLabel('BTN_DELETE'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'deleteDoc',"t_prospect_id=$acid"),
                                                                          ),
                                                      )
                                                  ),

                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
	}
	
	function getOportunityDocView($id) 
	{
		
		$acid = $id;
		$table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTable(
                  array(
                    'id' 		=> PAGER::P3,
                    'search'=> '',
                    'title' => "Document Attachment",
                    'primary_key' => 't_prospect_doc_id',
                    "query" => array( "from" 	=> "FROM v_prospect_documents",
                                      "where"  => "WHERE t_prospect_id=$acid",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => false,
                    'columns' => array(
                               

                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
	}
	
	function getSurveyDoc($input) 
	{
		$rs = $this->CI->adodb->execute("select t_survey_id, t_prospect_id from t_survey where t_survey_id=?", array($input['t_survey_id']));
		$acid = $rs->fields['t_prospect_id'];
		$table = new koje_datatables('MY_SEARCH');
        $s = $table->renderDataTable(
                  array(
                    'id' 		=> PAGER::P2,
                    'search'=> '',
                    'title' => "Document List",
                    'primary_key' => 't_prospect_doc_id',
                    "query" => array( "from" 	=> "FROM v_prospect_documents",
                                      "where"  => "WHERE t_prospect_id=$acid",
                                      "params" => array()
                              ),
                    'order' => array(
                                      PAGER::COL1=>'desc',
                                    ),
                    'toolbarButton' => array('docUpload' 	=> array(	'title' => getLabel('BTN_UPLOAD_DOC'),'url' => $this->CI->koje_system->URLBuild(false,'insertDoc',"t_prospect_id=$acid"))),
                    'columns' => array(
                                    '_KOJE_BUTTON_'		=> array('title' => TITLE_MSG::LABEL_ADMIN,
                                                            'button'=> array(
                                                            'editDoc' 	=> array(	'title' => getLabel('BTN_EDIT'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'editDoc',"t_prospect_id=$acid"),
                                                                            ),
                                                            'deleteDoc' 	=> array(	'title' => getLabel('BTN_DELETE'),
                                                                              'url' => $this->CI->koje_system->URLBuild(false,'deleteDoc',"t_prospect_id=$acid"),
                                                                          ),
                                                      )
                                                  ),

                                    'doc_type_desc' 		  => array(),
                                    'doc_no' 		    => array(),
                                    'description' => array(),
                                    't_file_id' => array('format'=> 'file'),       
                                ),
                    'summary' => array(),
                  )
              );
        $str = $s;
        return $str;
	}

  function generateItemCharge($input, $addRow) {
     $cnt = 3;
     $data = array();
     $js = "";
     $js1= "";
     $t_quotation_id = $this->CI->koje_system->getArrayVal($input,'t_quotation_id',false);
     $i=1;
     if($t_quotation_id) {
       $rs = $this->CI->adodb->execute("select * from v_quotation_item where t_quotation_id=?", array($input['t_quotation_id']));
       while($row = $rs->fetchRow()) {
         $data[] = $row;
         $js1 .= '$("#t_product_item_id__'.$i.'").val('.$row['t_product_item_id'].');';
         $i++;
       }
       $cnt = $cnt+ $i;
     }
     $str = "<div id='list_quotation' class='block box box-body table-responsive koje-no-border'>
 							<input type='hidden' id='item_charge_cnt' name='item_charge_cnt' value='$cnt' />
 							<table class='table table-sm'>
 						<tr>
                 <th>No.</th>
                 <th>Item</th>
                 <th>Name</th>
                 <th>Type</th>
                 <th>Amount</th>
                 <th>Tax (%)</th>
                 <th>Total</th>
                 <th>Notes</th>
               </tr>
 					 ";
    $rs = $this->CI->adodb_billing->execute("select t_product_item_id id, concat_ws('*',product_name,name, amount, item_type) as text from v_product_item order by product_name");
    $arr[] = array(
                'id' => "",
                'text' => "PRODUCT*ITEM*AMOUNT*TYPE",
            );
    $arr[] = array(
                'id' => "",
                'text' => "",
            );
    while($row = $rs->fetchRow())
    {
      $arr[] = array(
                  'id' => $row['id'],
                  'text' => $row['text']
              );
    }
    $options = json_encode($arr);
    $js .= "
           var el = $('select[id^=t_product_item_id__');
           el.select2({
                             dropdownAutoWidth : true,
                             width: '100px',
                             templateResult: function(data) {
                                     var r = data.text.split('*');
                                     var s = '';
                                     var w = ['40%','30%','10%','30%'];
                                     for(i=0;i<r.length;i++) {
                                       s = s + '<td nowrap width=\"'+w[i]+'\">' + r[i] + '</td>';
                                     }
                                     var \$result = $('<table width=\"700px\" border=0><tr>' + s + '</tr></table>');
                                     return \$result;
                                 }
                         });
           var data = $options;
           var i, l, html, opt;
           var disabled='disabled';
           html = '';
           for(i = 0, l = data.length; i < l; i++) {
               opt = data[i];
               html += '<option '+disabled+' value=\"'+ opt.id+'\">'+ opt.text + '</option>';
               disabled = '';
           }
           el.html(html);
           $js1
           el.trigger('change');

          $('.select2-selection').addClass('dt-select2-filter').addClass('col-sm-12');
          el.on('change', function(){
              var id = $(this).attr('id');
              var inm = id.replace('t_product_item_id','item_name');
              var it = id.replace('t_product_item_id','item_type');
              var ia = id.replace('t_product_item_id','item_amount');
              var itx = id.replace('t_product_item_id','item_tax');
              var data = $('#'+id+' option:selected').text().split('*');
              if(data.length<3) {
                $('#'+inm).val('');
                $('#'+it).val('');
                $('#'+ia).val('');
                $('#'+itx).val('');
              }
              else {
                $('#'+inm).val(data[1]);
                $('#'+ia).val(data[2]);
                $('#'+it).val(data[3]);
                $('#'+itx).val('10');
              }
          });
          ";

    for($i=1;$i<=$cnt;$i++) {
      $qdd      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_quotation_item_id');
      $ind      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'name');
      $ity      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'item_type_desc');
      $iad      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'amount');
      $ixd      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'tax');
      $isd      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'total');
      $iod      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'notes');

      $fh_qd   = form_hidden("t_quotation_item_id__$i",$qdd);
      $dd_prod = form_dropdown("t_product_item_id__$i", array(), false, "id='t_product_item_id__$i' class='form-control'");
      $fin     = form_input(array("name"=>"item_name__$i","id"=>"item_name__$i","class"=>"form-control"),$ind);
      $fty     = form_input(array("name"=>"item_type__$i","id"=>"item_type__$i","class"=>"form-control"),$ity,"readonly=true");
      $fia     = form_input(array("name"=>"item_amount__$i","id"=>"item_amount__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$iad);
      $dd_tax  = form_dropdown("item_tax__$i",array('0' => '0%','10' => '10%'),$ixd,"id='item_tax__$i' class='form-control'");
      $fis     = form_input(array("name"=>"item_subtotal__$i","id"=>"item_subtotal__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$isd);
      $fio     = form_input(array("name"=>"item_notes__$i","id"=>"item_notes__$i","class"=>"form-control"),$iod);

			$str.=
				"<tr><td><label class='control-label'>$i.</label>$fh_qd</td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$dd_prod</div></td>
						<td width='35%'><div class='col-sm-12'>$fin</div></td>
						<td width='15%'><div class='col-sm-12 form-group input-group'>$fty</div></td>
						<td width='8%'><div class='col-sm-12 form-group input-group'>$fia</div></td>
						<td width='4%'><div class='col-sm-12 form-group input-group'>$dd_tax</div></td>
						<td width='8%'><div class='col-sm-12 form-group input-group'>$fis</div></td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$fio</div></td>
				</tr>
			";
		}
		$str .= "<tr><td></td><td></td><td></td><td>T O T A L</td><td><b><span id=item__amount></span></b></td><td></td><td><b><span id=item__total></span></b></td></tr>
            </table>";
    if($addRow)
    {
      $str .= "<button name=submitAddRow value=1 class='btn btn-raised btn-sm btn-round btn-primary'>".getLabel('BTN_SAVE_ADDROW')."</button>";
    }
    $str .= "</div>";

    $str .="
		<script>
      $(document).ready(function(){
        calcCharge();
        $js
  			$(\"input[id^='item_'],select[id^='item_'],select[id^='t_product_item_id_'] \").change(function() {
          calcCharge();
        });
    });

    function calcCharge()
    {
      total_amount = 0;
      total        = 0;
      for(i=1;i<=$cnt;i++) {
        amount = intVal($(\"#item_amount__\"+i).val());
        tax    = intVal($(\"#item_tax__\"+i).val());
        if(isNaN(amount)) {
          amount  = 0;
        }
        if(isNaN(tax)) {
          tax  = 0;
        }

        subtotal = amount + (amount*tax/100);
        total_amount = total_amount + amount;
        total = total + subtotal;
        $(\"#item_subtotal__\"+i).val(subtotal);
      }
      $(\"#item__amount\").html(stringToCurrency(total_amount));
      $(\"#item__total\").html(stringToCurrency(total));

    }
		</script>
		";
		return $str;
	}
  function generateItemChargeAlternative($input, $addRow) {
     $AddRowCnt = 5;
     $cnt = 0;
     $data = array();
     $js = "";
     $js1= "";
     $t_quotation_id = $this->CI->koje_system->getArrayVal($input,'t_quotation_id',false);
     $i=1;
     if($t_quotation_id) {
       $rs = $this->CI->adodb->execute("select * from t_quotation_item_alt where t_quotation_id=?", array($input['t_quotation_id']));
       while($row = $rs->fetchRow()) {
         $data[] = $row;
         $js1 .= '$("#t_product_service_id__'.$i.'").val('.$row['t_product_service_id'].');';
         $i++;
       }
       $cnt = $cnt+ $i;
     }
     $cnt = $cnt+$AddRowCnt;
     $str = "<div id='list_requirement' class='block box box-body table-responsive koje-no-border'>
 							<input type='hidden' id='item_charge_alt_cnt' name='item_charge_alt_cnt' value='$cnt' />
 							<table class='table table-sm'>
 						<tr>
                 <th>No.</th>
                 <th>Service</th>
                 <th>Code</th>
                 <th>Name</th>
                 <th>Speed</th>
                 <th>UoM</th>
                 <th>Monthly Charge</th>
                 <th>Installation Charge</th>
               </tr>
 					 ";
    $rs = $this->CI->adodb_billing->execute("select t_product_service_id, concat_ws('*',product_code,product_name, service_code, service_name) as name from t_product_service order by product_code");

    $arr[] = array(
                'id' => "",
                'text' => "PRD-CODE*PRODUCT NAME*SRV-CODE*SERVICE NAME"
            );
    $arr[] = array(
                    'id' => "",
                    'text' => "",
                );
    while($row = $rs->fetchRow())
    {
      $arr[] = array(
                  'id' => $row['t_product_service_id'],
                  'text' => $row['name']
              );
    }
    $options = json_encode($arr);
    $js .= <<<EOF
           var el = $('select[id^=t_product_service_id__');
           el.select2({
                             dropdownAutoWidth : true,
                             width: '100px',
                             templateResult: function(data) {
                                     var r = data.text.split('*');
                                     var s = '';
                                     var w = ['10%','40%','10%','40%'];
                                     for(i=0;i<r.length;i++) {
                                       s = s + '<td nowrap width=\"'+w[i]+'\">' + r[i] + '</td>';
                                     }
                                     var \$result = $('<table width=\"700px\" border=0><tr>' + s + '</tr></table>');
                                     return \$result;
                                 }
                         });
           var data = $options;
           var i, l, html, opt;
           html = '';
           var disabled='disabled';
           for(i = 0, l = data.length; i < l; i++) {
               opt = data[i];
               html += '<option '+disabled+' value=\"'+ opt.id+'\">'+ opt.text + '</option>';
               disabled='';
           }
           el.html(html);
           $js1
           el.trigger('change');

          $('.select2-selection').addClass('dt-select2-filter').addClass('col-sm-12');
          el.on('change', function(){
              var id = $(this).attr('id');
              var cd = id.replace('t_product_service_id','code');
              var nm = id.replace('t_product_service_id','name');
              var sp = id.replace('t_product_service_id','speed');
              var uo = id.replace('t_product_service_id','uom');
              var ot = id.replace('t_product_service_id','otc_charge');
              var mt = id.replace('t_product_service_id','mtce_charge');
              var data = $('#'+id+' option:selected').text().split('*');
              if(data.length<2) {
                $('#'+cd).val('');
                $('#'+nm).val('');
                $('#'+sp).val('');
                $('#'+uo).val('');
                $('#'+ot).val('');
                $('#'+mt).val('');
              }
              else {
                $('#'+cd).val(data[0]);
                $('#'+nm).val(data[1]+' - ' +data[3]);
                $('#'+sp).val('');
                $('#'+uo).val('UOM-01');
                $('#'+ot).val('');
                $('#'+mt).val('');
              }
          });
EOF;

    $uom      = array_merge(array(""=>getlabel('select_none')),$this->CI->koje_system->getRefDomainList('REQUIREMENT__UOM'));

    for($i=1;$i<=$cnt;$i++) {
      $ri      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_quotation_item_alt_id');
      $pi      = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'t_product_service_id');
      $icd     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'product_code');
      $inm     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'product_name');
      $isp     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'speed');
      $iuo     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'uom');
      $iot     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'otc_charge');
      $imt     = $this->CI->koje_system->getArrayVal($this->CI->koje_system->getArrayVal($data,$i-1),'mtce_charge');

      $fh_qd   = form_hidden("t_quotation_item_alt_id__$i",$ri);
      $dd_pi   = form_dropdown("t_product_service_id__$i", array(), $pi, "id='t_product_service_id__$i' class='form-control'");
      $fi_cd   = form_input(array("name"=>"code__$i","id"=>"code__$i","class"=>"form-control","readonly"=>"true"),$icd);
      $fi_nm   = form_input(array("name"=>"name__$i","id"=>"name__$i","class"=>"form-control","readonly"=>"true"),$inm);
      $fi_sp   = form_input(array("name"=>"speed__$i","id"=>"speed__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$isp);
      $dd_uo   = form_dropdown("uom__$i",$uom,$iuo,"id='uom__$i' class='form-control'");
      $fi_ot   = form_input(array("name"=>"otc_charge__$i","id"=>"otc_charge__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$iot);
      $fi_mt   = form_input(array("name"=>"mtce_charge__$i","id"=>"mtce_charge__$i","class"=>"form-control","data-inputmask" => "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true"),$imt);

			$str.=
				"<tr><td width='3%'><label class='control-label'>$i.</label>$fh_qd</td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$dd_pi</div></td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$fi_cd</div></td>
            <td width='40%'><div class='col-sm-12 form-group input-group'>$fi_nm</div></td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$fi_sp</div></td>
            <td width='10%'><div class='col-sm-12 form-group input-group'>$dd_uo</div></td>
						<td width='10%'><div class='col-sm-12 form-group input-group'>$fi_ot</div></td>
						<td width='10%'><div class='col-sm-12 form-group input-group'>$fi_mt</div></td>
				</tr>
			";
		}
		$str .= "</table>";
    if($addRow)
    {
      $str .= "<button name=submitAddRow value=1 class='btn btn-raised btn-sm btn-round btn-primary'>".getLabel('BTN_SAVE_ADDROW')."</button>";
    }
    $str .="</div>";

    $str .="
		<script>
      $(document).ready(function(){
        $js
      });
		</script>
		";
		return $str;
	}

  function prospectlistButton($id=-1,$act="", $btnLeft=array(), $btnCenter=array(), $btnRight=array())
  {
    $class = 'class="btn btn-raised bg-red btn-sm btn-round"';
    $str   = '<div class="row"><div class="col-sm-10">';
    $str .= '<div class="btn-group">';
    foreach ($btnLeft as $key => $value) {
      if(is_array($value))
      {
        $url = $value['url'];
        $str .= HTML_link("$url?t_prospect_id=$id", getLabel('BTN_'.strtoupper($key)),getValueEqual($act,$key,$class));
      }
      else
      {
        $str .= HTML_link("detail?t_prospect_id=$id&act=$value", getLabel('BTN_'.strtoupper($value)),getValueEqual($act,$value,$class));
      }
    }
    $btnGroup = array('title' => 'LOGS', 'list'=>array());
    foreach ($btnCenter as $key => $value) {
      $btnGroup['list'][] = array('url'=> "detail?t_prospect_id=$id&act=$value", 'label'=>getLabel('BTN_'.strtoupper($value)));
    }
    $str .= html_button_group($btnGroup);
    $str .= '</div>';
    foreach ($btnRight as $key => $value) {
      if(is_array($value))
      {
        $url = $value['url'];
        $str .= HTML_link("$url?t_prospect_id=$id", getLabel('BTN_'.strtoupper($key)),getValueEqual($act,$key,$class));
      }
      else
      {
        $str .= HTML_link("detail?t_prospect_id=$id&act=$value", getLabel('BTN_'.strtoupper($value)),getValueEqual($act,$value,$class));
      }
    }
    $str .= HTML_link("index?t_prospect_id=$id", getLabel('BTN_BACK_TO_LIST'));
    $str .= '</div></div>';
    return $str;
  }

  function getListStage($id, $readonly)
  {
    $pagerID = PAGER::P1;
    $this->CI->load->model('prospect/proposal_model');
    $params['FORMS'] = $this->CI->proposal_model->getForm('form_search',array());
    $myForm = new KOJE_Form('MY_SEARCH');
    $myForm->linkSearchForm($pagerID);
    $myForm->setTitle($params['FORMS']['title']);
    $myForm->setItem($params['FORMS']['item']);
    $myForm->setDisplay($params['FORMS']['display']);
    $loginID = $this->CI->koje_system->getLoginID();
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=> '&nbsp;',
        'title' => "Stage History",
        'primary_key' => 't_prospect_stage_id',
        "query" => array(	 "from" 	=> "FROM v_prospect_stage",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array()
                  ),
        'order' => array(
                          PAGER::COL1=>'desc',
                        ),
        'toolbarButton' => array(
                        ),
        'columns' => array(
                        't_stage_id'             => array('visible' => false),
                        'start_date' 					=> array('format'=>'date'),
                        'end_date' 					  => array('format'=>'date'),
                        'status_desc' 				=> array(),
                        'stage_name' 					=> array(),
                        'probability' 				=> array('format'=>'percentage'),
                        'description' 				=> array(),
                        'sys_created_date' 		=> array('format'=>'datetime'),
                  )
      )
    );
    return $str;
  }
  function getListTelemarketing($id, $readonly)
  {
    $pagerID = PAGER::P1;
    $this->CI->load->model('prospect/prospect_model');
    $params['FORMS'] = $this->CI->prospect_model->getForm('form_search_telemarketing',array());
    $myForm = new KOJE_Form('MY_SEARCH');
    $myForm->linkSearchForm($pagerID);
    $myForm->setTitle($params['FORMS']['title']);
    $myForm->setItem($params['FORMS']['item']);
    $myForm->setDisplay($params['FORMS']['display']);
    $loginID = $this->CI->koje_system->getLoginID();
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=> $strSearch,
        'title' => "Telemarketing Activity",
        'primary_key' => 't_telemarketing_id',
        "query" => array(	 "from" 	=> "FROM v_telemarketing",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array()
                  ),
        'order' => array(
                          PAGER::COL4=>'desc',
                        ),
        'toolbarButton' => array(),
        'columns' => array(
                      'activity_date' 		=> array('format'=>'datetime'),
                      'type_desc' 		=> array(),
                      'description' 	=> array(),
                      'result'				=> array(),
                      'sys_created_date' 		=> array('format'=>'datetime')
                  )
      )
    );
    return $str;
  }
  function getListPresentation($id, $readonly)
  {
    $pagerID = PAGER::P1;
    $this->CI->load->model('prospect/proposal_model');
    $params['FORMS'] = $this->CI->proposal_model->getForm('form_search',array());
    $myForm = new KOJE_Form('MY_SEARCH');
    $myForm->linkSearchForm($pagerID);
    $myForm->setTitle($params['FORMS']['title']);
    $myForm->setItem($params['FORMS']['item']);
    $myForm->setDisplay($params['FORMS']['display']);
    $loginID = $this->CI->koje_system->getLoginID();
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=> '&nbsp;',
        'title' => 'Presentation',
        'primary_key' => 't_presentation_id',
        "query" => array(	 "from" 	=> "FROM v_presentation",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array()
                  ),
        'order' => array(
                          PAGER::COL1=>'desc',
                        ),
        'toolbarButton' => array(),
        'columns' => array(
                        'activity_date'=> array('format'=>'datetime'),
                        'title' 						=> array(),
                        'description' 			=> array(),
                        'result_info' 		=> array(),
                        'sys_created_date'	=> array('format'=>'datetime'),
                        'attachment_1_info'	=> array('format'=>'file'),
                        'attachment_2_info' => array('format'=>'file'),
                        'attachment_3_info' => array('format'=>'file'),
                  )
      )
    );
    return $str;
  }
  function getListSurvey($id, $readonly)
  {
    $pagerID = PAGER::P1;
    $this->CI->load->model('prospect/proposal_model');
    $params['FORMS'] = $this->CI->proposal_model->getForm('form_search_survey',array());
    $myForm = new KOJE_Form('MY_SEARCH');
    $myForm->linkSearchForm($pagerID);
    $myForm->setTitle($params['FORMS']['title']);
    $myForm->setItem($params['FORMS']['item']);
    $myForm->setDisplay($params['FORMS']['display']);
    $loginID = $this->CI->koje_system->getLoginID();
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
                    array(
                      'id' 		=> $pagerID,
                      'search'=> '&nbsp;',
                      'title' => 'Survey',
                      'primary_key' => 't_survey_id',
                      "query" => array(	 "from" 	=> "FROM v_survey",
                                         "where"  => "WHERE t_prospect_id=$id",
                                         "params" => array()
                                ),
                      'order' => array(
                                        PAGER::COL1=>'desc',
                                      ),
                      'toolbarButton' => array( ),
                      'columns' => array(
                                        'title' 					=> array(),
                                        'activity_date' 		=> array('format'=>'date'),
                                        'target_date' 		=> array('format'=>'date'),
                                        'description' 		=> array(),
                                        'service_lists_info'		=> array(),
                                        'status_desc' 					=> array(),
                                        'survey_date' 		=> array('format'=>'date'),
                                        'survey_notes' 		=> array(),
                                        'attachment_1_info'	=> array('format'=>'file'),
                                        'attachment_2_info' => array('format'=>'file'),
                                        'attachment_3_info' => array('format'=>'file'),
                                        'sys_created_date'=> array('format'=>'datetime'),
                                )
                    )
          );
    return $str;
  }
  function getListProposal($id, $readonly)
  {
    $pagerID = PAGER::P1;
    $this->CI->load->model('prospect/proposal_model');
    $params['FORMS'] = $this->CI->proposal_model->getForm('form_search_proposal',array());
    $myForm = new KOJE_Form('MY_SEARCH');
    $myForm->linkSearchForm($pagerID);
    $myForm->setTitle($params['FORMS']['title']);
    $myForm->setItem($params['FORMS']['item']);
    $myForm->setDisplay($params['FORMS']['display']);
    $loginID = $this->CI->koje_system->getLoginID();
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
              array(
                'id' 		=> $pagerID,
                'search'=> '&nbsp;',
                'title' => "Proposal",
                'primary_key' => 't_proposal_id',
                "query" => array(	 "from" 	=> "FROM v_proposal",
                                   "where"  => "WHERE t_prospect_id=$id",
                                   "params" => array()
                          ),
                'order' => array(
                                  PAGER::COL1=>'desc',
                                ),
                'toolbarButton' => array( ),
                'columns' => array(
                                'title' 					=> array(),
                                'activity_date' 		=> array('format'=>'date'),
                                'service_lists_desc' 		=> array(),
                                'description' 		=> array(),
                                'attachment_1_info'	=> array('format'=>'file'),
                                'attachment_2_info' => array('format'=>'file'),
                                'attachment_3_info' => array('format'=>'file'),
                                'sys_created_date'=> array('format'=>'datetime'),
                          )
              )
            );
    return $str;
  }
  function getListRequirement($id, $readonly)
  {
    $this->CI->load->model('prospect/negotiation_model');
    $pagerID = PAGER::P2;
    $params['FORMS'] = $this->CI->negotiation_model->getForm('form_search',array());
    $myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str  = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=>  '&nbsp;',
        'title' => "Customer Requirement",
        'primary_key' => 't_requirement_id',
        "query" => array(	 "from" 	=> "FROM v_requirement",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array()
                  ),
        'order' => array(
                          PAGER::COL1=>'asc',
                        ),
        'toolbarButton' => array( ),
        'columns' => array(
                        'product_name' 	=> array(),
                        'priority_desc'	=> array(),
                        'sys_created_date'=> array('format'=>'datetime'),
                  ),
          'summary'	=> array(),
      )
    );
    return $str;
  }
  function getListQuotation($id,$readonly,$status=false)
  {
    $ws = $status ? "and status='$status'" : "";
    $this->CI->load->model('prospect/negotiation_model');
    $pagerID = PAGER::P2;
    $params['FORMS'] = $this->CI->negotiation_model->getForm('form_search_quotation',array());
    $myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);

    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=> $strSearch,
        'title' => "Quotation",
        'primary_key' => 't_quotation_id',
        "query" => array(	 "from" 	=> "FROM v_quotation a",
                           "where"  => "WHERE t_prospect_id=$id $ws",
                           "params" => array(),
                  ),
        'order' => array(
                    PAGER::COL0=>'asc',
                  ),
        'toolbarButton' => array( ),
        'columns' => array(
                          'quotation_no' 					=> array(),
                          'name' 									=> array(),
                          'status_desc' 					=> array(),
                          'sum_amount' 						=> array('format'=>'number'),
                          'expired_date' 					=> array('format'=>'date'),
                          'approved_date' 	  		=> array('format'=>'datetime'),
                          'approved_notes'				=> array(),
                          'submitted_date' 	  		=> array('format'=>'date'),
                          'sys_created_date' 	  	=> array('format'=>'datetime'),
                  ),
            'summary'	=> array(3),
          )
    );
    return $str;
  }
  function getListQuotationItem($id,$readonly)
  {
    $this->CI->load->model('prospect/negotiation_model');
    $pagerID = PAGER::P2;
    $params['FORMS'] = $this->CI->negotiation_model->getForm('form_search_quotation_item',array());
    $myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);

    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=> $strSearch,
        'title' => "Quotation",
        'primary_key' => 't_quotation_id',
        "query" => array(	 "from" 	=> "FROM v_quotation_item a",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array(),
                  ),
        'order' => array(
                    PAGER::COL0=>'asc',
                    PAGER::COL1=>'asc',
                  ),
        'toolbarButton' => array( ),
        'columns' => array(
                        'quotation_no' 					=> array(),
                        'quotation_name'        => array(),
                        'status_desc' 					=> array(),
                        'name' 					        => array(),
                        'item_type_desc' 				=> array(),
                        'amount' 								=> array('format'=>'number'),
                        'tax' 									=> array('format'=>'number'),
                        'total' 								=> array('format'=>'number'),
                        'notes' 					      => array('format'=>'date'),
                        'approved_notes'				=> array(),
                  ),
            'summary'	=> array(5,7),
          )
    );
    return $str;
  }
  function getListNegotiation($id,$readonly)
  {
    $this->CI->load->model('prospect/negotiation_model');
    $pagerID = PAGER::P2;
    $params['FORMS'] = $this->CI->negotiation_model->getForm('form_search',array());
    $myForm = new KOJE_Form('MY_SEARCH', $params, $pagerID);
    $strSearch = $myForm->renderSearchDataTables();
    $table = new koje_datatables($myForm->id);
    $str = $table->renderDataTable(
      array(
        'id' 		=> $pagerID,
        'search'=>  '&nbsp;',
        'title' => "Negotiation",
        'primary_key' => 't_negotiation_id',
        "query" => array(	 "from" 	=> "FROM v_negotiation",
                           "where"  => "WHERE t_prospect_id=$id",
                           "params" => array()
                  ),
        'order' => array(
                          PAGER::COL1=>'asc',
                        ),
        'toolbarButton' => array( ),
        'columns' => array(
                        'activity_date'=> array('format'=>'date'),
                        'contact_name' 		=> array(),
                        'description' 	 	=> array(),
                        'sys_created_date'=> array('format'=>'datetime'),
                  ),
          'summary'	=> array(),
      )
    );
    return $str;
  }

/*---------------------------- Kanban --------------------------------------*/
  function kanbanGetStages($params=false) {
    $arrColor = array('label-success','label-primary','label-info','label-warning','label-danger','label-inverse','label-default');
    $rs = $this->CI->adodb->execute("select * from t_stage where t_stage_id<>9 order by code");
    $arrData = array();
    $i=0;
    while ($row = $rs->fetchRow()) {
        $arrData[] = array(
                        'val' => $row['code'],
                        'description' => $row['description'],
                        'color' => $arrColor[$i],
                      );
          $i++;
      }
      $json = json_encode($arrData);
      return $json;
    }
    function kanbanGetProspect($where="")
    {
			$sql = "select c.*
              from v_prospect_current c
							where status='PSST-01' $where
              order by c.prospect_no";

			$rs = $this->CI->adodb->execute($sql);
			$arrData = array();
			$i=0;
			while ($row = $rs->fetchRow()) {
					$sales_name = $row['sales_name'];
					$name = $row['prospect_name'];
					$target_amount =  $row['target_amount'];
					$prospect_start_date = $row['prospect_start_date'];
					$target_closed_date = $row['target_closed_date'];
					$company_name = $row['company_name'];
					$prospect_no = $row['prospect_no'];
					$status_desc =  $row['status_desc'];
					$probability =  $row['probability_pcts'];
					$stageInfo =  $row['stage_name'] ;
          $quotation_info = $row['quotation_info'];
          $quotation_deal_info = $row['quotation_deal_info'];
					$arrData[] = array(
												'x' => $row['stage_code'],
												'y' => $i,
												'amount' => $target_amount,
												'id' => $row['t_prospect_id'],
												'content' =>
																	'<div>
                                      <div>
                                        <i class="fa fa-user text-warning"></i>
                                        <span title="Sales Name : '.$sales_name.'">Sales : <b>'.character_limiter($sales_name,20).' </b></span>
                                      </div>
                                      <div>
                                        <i class="fa fa-cloud text-info"></i>
                                        <span title="Prospect No. : '.$prospect_no.' Probability : '.$probability.']">Prospect No. <b>'.character_limiter($prospect_no,20).' ['.$probability.']</b></span>
                                      </div>
																			<div>
																				<i class="fa fa-desktop text-warning"></i>
                                        <span title="Step Info : '.$stageInfo.'">Stage : <b>'.$stageInfo.'</b></span>
																			</div>
																			<div>
																				<i class="fa fa-bookmark text-primary"></i>
                                        <span title="'.$name.'">Prospec Name : <b>'.character_limiter($name,20).'</b></span>
																			</div>
																			<div>
																				<i class="fa fa-credit-card text-alert"></i>
                                        <span title="Company Name : '.$company_name.'">Company : <b>'.character_limiter($company_name,20).'</b></span>
																			</div>
																			<div>
																				<i class="fa fa-briefcase text-danger"></i>
                                        <span title="Target Amount : '.$target_amount.'">Target : <b>'.$this->CI->koje_system->number_format($target_amount).'</b></span>
																			</div>
																			<div>
																				<i class="fa fa-calendar text-success"></i>
                                        <span title="Quotation : '.$quotation_info.'">Quotation<br><b>'.$quotation_info.'</b></span>
																			</div>
																			<div>
																				<i class="fa fa-calendar text-success"></i>
                                        <span title="Quotation Deal : '.$quotation_deal_info.'">Deal<br><b>'.$quotation_deal_info.'</b></span>
																			</div>
																	</div>'
											);
					$i++;
			}
			$json = json_encode($arrData);
			return $json;
		}

    function kanbanShow($data=false)
    {
      $base = base_url();
      $totalAmount = array();
      $totalCnt = array();
      $smry = json_decode($data, true);
      foreach($smry as $key => $value) {
        if(!isset($totalAmount[$value['x']])) {
          $totalAmount[$value['x']] = 0;
          $totalCnt[$value['x']] = 0;
        }
        $totalAmount[$value['x']] += $value['amount'];
        $totalCnt[$value['x']] ++;
      }
    ?>
      <link  href="<?php echo $base?>/assets/components/gridstack/css/gridstack.min.css" rel="stylesheet" />
      <link  href="<?php echo $base?>/assets/components/gridstack/css/gridstack-extra.min.css" rel="stylesheet" />
      <script src="<?php echo $base?>/assets/components/gridstack/js/gridstack.min.js"></script>
      <script src="<?php echo $base?>/assets/components/gridstack/js/gridstack.jQueryUI.min.js"></script>

        <?php
          $stages = json_decode($this->CI->app_lib->kanbanGetStages());
          $str  = '<div class="row"><div class="col-sm-11">S T A G E - M A P<div class="stage">';
          $str1 = '<div class="row"><div class="col-sm-11"><div class="stage">';
          foreach ($stages as $key => $value) {
              $str .= "<span class='stage-item label label-xlg ".$stages[$key]->color." arrowed-in arrowed-right'>".$stages[$key]->description.' (<b id=total_cnt_'.$key.'>'.$this->CI->koje_system->getArrayVal($totalCnt,$key)."</b>)</span>";
              $str1 .= "<span class='total stage-item label label-xlg'><b>Rp. </b><b id=total_amount_".$key.">".$this->CI->koje_system->number_format($this->CI->koje_system->getArrayVal($totalAmount,$key),0)."</b></span>";
          }
          $str .= '</div></div>
                   </div>';
          $str1 .='</div></div>
                   <div class="col-sm-1"><div class="stage"></div>
               </div></div>';

          print $str;
          print $str1;
        ?>
      <div class="row">
        <div class="col-sm-11">
          <div id='gs1' class="grid-stack"></div>
        </div>
        <div class="col-sm-1"></div>
      </div>

      <script type="text/javascript">
          $(function () {
              var options = {
                width : 4,
                cellHeight  : 240,
                verticalMargin : 4,
                disableOneColumnMode : true,
              };

              $('#gs1').gridstack(options);

              new function () {
                  this.serializedData = <?php echo $data?>;
                  this.grid = $('#gs1').data('gridstack');
                  this.grid.setGridWidth(options.width, false);

                  this.loadGrid = function () {
                      this.grid.removeAll();
                      this.grid.batchUpdate();
                      var items = GridStackUI.Utils.sort(this.serializedData);
                      _.each(items, function (node) {
                          this.grid.addWidget($("<div><div class='thumbnail search-thumbnail grid-stack-item-content'>"+node.content+'</div></div>'),
                          node.x, node.y, node.width, node.height, false, 1, 100, 1, 100, node.id);
                      }.bind(this));
                      this.grid.commit();
                      return false;
                  }.bind(this);
                  this.loadGrid();

                  $('#gs1').on('dragstop', function(event, ui) {
                    var element = $(event.target);
                    var node = element.data('_gridstack_node');
                    if(node.x != node._beforeDragX) {
                      $.ajax({method: "POST",
                              dataType: "json",
                              url: "<?php print base_url()?>index.php/_system_/getJSON/prospectSetData",
                              data: {'id' : node.id, 'status' : node.x }
                              })
                        .done(function(msg) {
                          jQuery(function($) {
                            oldCnt = intVal($("#total_cnt_"+node.x).html());
                            $("#total_cnt_"+node.x).html(oldCnt+1);
                            oldCnt = intVal($("#total_cnt_"+node._beforeDragX).html());
                            $("#total_cnt_"+node._beforeDragX).html(oldCnt-1);

                            amount = intVal(msg.amount);
                            oldAmount = intVal($("#total_amount_"+node.x).html());
                            $("#total_amount_"+node.x).html(stringToCurrency(oldAmount+amount));
                            oldAmount = intVal($("#total_amount_"+node._beforeDragX).html());
                            $("#total_amount_"+node._beforeDragX).html(stringToCurrency(oldAmount-amount));

                            return false;

                          });
                        });
                      }
                  });
              };
          });
      </script>
    <?php
  }

/***********************************************************************/
	function showStage($stage=false, $t_opportunity_id=false)
  {
		$stages = json_decode($this->CI->app_libs->getStages());
		$str = "<div class='row'>";
		$i=0;
		foreach ($stages as $key => $value) {
				$str .= "<span class='col-xs-1 label label-xlg ".$stages[$key]->color." arrowed-in arrowed-right'> ".
				($stage==$stages[$key]->val ? "<b><u>":"").
				$stages[$key]->description.
				($stage==$stages[$key]->val ? "</u></b>":"").
				" </span>";
				$i++;
		}
		$str .= "</div>";
		return $str;
	}
	function ToolTips($msg=false)
  {
		if(!$msg) {
			$msg = $this->CI->utils->base64decode($this->CI->input->get_post("_sysmsg"));
		}
		if($msg) {
			?>
				<script type="text/javascript">
					jQuery(function($) {
						$.gritter.add({
							title: 'Update Order.',
							text: '<?php echo $msg?>',
							image: '',
							sticky: false,
							time: '',
							class_name: 'gritter-success'
						});
						return false;
					});
				</script>
			<?php
		}
	}

/* --------------------- Reporting -------------------------------------*/
  function getDataSumTargetbyYear($year,$where="")
  {
    $sql = "select $year as year";
    foreach ($this->arrMonth as $key => $value) {
      $value = strtolower($value);
      $sql .= ",sum(c.opportunity_{$value}) as opportunity_{$value}
               ,sum(c.proposal_{$value}) as proposal_{$value}
               ,sum(c.negotiation_{$value}) as negotiation_{$value}
               ,sum(c.closing_{$value}) as closing_{$value}";
    }
    $sql .= " from t_target c where year=$year $where";
    $row = $this->CI->adodb->GetRow($sql);
    foreach ($this->arrStage as $key => $value) {
      $row1=array();
      foreach ($this->arrMonth as $key1 => $value1) {
        $value1 = strtolower($value1);
        $row1[] = $row[$value.'_'.$value1];
      }
      $row[$value] = $row1;
    }
    return $row;
  }

  function getDataSumRevenueByYear($year,$where)
  {
    $sql = "select
              '$year'||b.r_month_id as ym,b.short_name, b.full_name,
              sum(case when t_stage_id=1 then 1 else 0 end) as stage_opportunity,
              sum(case when t_stage_id=3 then 1 else 0 end) as stage_proposal,
              sum(case when t_stage_id=5 then 1 else 0 end) as stage_negotiation,
              sum(case when t_stage_id=7 then 1 else 0 end) as stage_closing_cnt,
              sum(case when t_stage_id=7 then getquotationitemdealbyprospect(a.t_prospect_id,'$year'||b.r_month_id) else 0 end) as stage_closing,
              sum(case when t_stage_id=9 then 1 else 0 end) as stage_cancel
            from t_prospect_stage a join t_prospect c on c.t_prospect_id=a.t_prospect_id $where
            right join r_month b on to_char(start_date,'YYYYMM') like '$year'||b.r_month_id
            group by '$year'||b.r_month_id,b.short_name, b.full_name
            order by '$year'||b.r_month_id";
    $rows = $this->CI->adodb->getAll($sql);
    foreach ($rows as $key => $value) {
      foreach ($this->arrStage as $key1 => $value1) {
        if(!isset($rows[$value1]))
        {
          $rows[$value1] = array();
          if($value1=='closing')
          {
            $rows[$value1.'_cnt'] = array();
          }
        }
        $rows[$value1][$key] = $value['stage_'.$value1];
        if($value1=='closing')
        {
          $rows[$value1.'_cnt'][$key] = $value['stage_'.$value1.'_cnt'];
        }
      }
    }
    return $rows;
  }
  function getDataRevenueByMonth($year, $month,$where="")
  {
    $sql = "select
              to_char(a.start_date,'DD') dy,
              sum(case when a.t_stage_id=1 then 1 else 0 end) as stage_opportunity,
              sum(case when a.t_stage_id=3 then 1 else 0 end) as stage_proposal,
              sum(case when a.t_stage_id=5 then 1 else 0 end) as stage_negotiation,
              sum(case when a.t_stage_id=7 then 1 else 0 end) as stage_closing_cnt,
              sum(case when a.t_stage_id=7 then getquotationitemdealbyprospect(a.t_prospect_id,'{$year}{$month}'||to_char(a.start_date,'DD')) else 0 end) as stage_closing
            from t_prospect_stage a, t_prospect c
            where c.t_prospect_id=a.t_prospect_id and
                  to_char(a.start_date,'YYYYMM') like '{$year}{$month}'
                  $where
            group by to_char(a.start_date,'DD')";
    $rows1 = $this->CI->adodb->getAll($sql);
    $rows=array();
    foreach ($rows1 as $key => $value) {
      foreach ($this->arrStage as $key1 => $value1) {
        for($i=0;$i<$this->CI->koje_system->totalDaysInMonth($year, $month);$i++)
        {
          if(!isset($rows[$value1][$i])) $rows[$value1][$i] = 0;
          if($value1=='closing')
          {
            $rows[$value1.'_cnt'][$i] = 0;
          }
        }
        $rows[$value1][intval($value['dy'])-1] = $value['stage_'.$value1];
        if($value1=='closing')
        {
          $rows[$value1.'_cnt'][intval($value['dy'])-1] = $value['stage_'.$value1.'_cnt'];
        }
      }
    }
    return $rows;
  }
  function getDataSumRevenueSalesByYear($year)
  {
    $sql = "select
              c.sales_id,'$year'||b.r_month_id as ym,b.short_name, b.full_name,
              sum(case when t_stage_id=1 then 1 else 0 end) as stage_opportunity,
              sum(case when t_stage_id=3 then 1 else 0 end) as stage_proposal,
              sum(case when t_stage_id=5 then 1 else 0 end) as stage_negotiation,
              sum(case when t_stage_id=7 then 1 else 0 end) as stage_closing_cnt,
              sum(case when t_stage_id=7 then getquotationitemdealbyprospect(a.t_prospect_id,'$year'||b.r_month_id) else 0 end) as stage_closing,
              sum(case when t_stage_id=9 then 1 else 0 end) as stage_cancel
            from t_prospect_stage a join t_prospect c on c.t_prospect_id=a.t_prospect_id
              right join r_month b on to_char(a.start_date,'YYYYMM') like '$year'||b.r_month_id
            group by c.sales_id, '$year'||b.r_month_id,b.short_name, b.full_name
            order by c.sales_id,'$year'||b.r_month_id";
    $rows = $this->CI->adodb->getAll($sql);
    foreach ($rows as $key => $value) {
      foreach ($this->arrStage as $key1 => $value1) {
        if(!isset($rows[$value1]))
        {
          $rows[$value1] = array();
          if($value1=='closing')
          {
            $rows[$value1.'_cnt'] = array();
          }
        }
        $rows[$value1][$key] = $value['stage_'.$value1];
        if($value1=='closing')
        {
          $rows[$value1.'_cnt'][$key] = $value['stage_'.$value1.'_cnt'];
        }
      }
    }
    return $rows;
  }
  function getDataNotifEmail($group)
  { $group_ref = 'NOTIF_EMAIL_'.$group;
    $email = $this->CI->adodb->getOne("select val from t_reference where group_reference=?", array($group_ref));
    return $email;
  }
/* ------------------------- Email Notification --------------------------*/
  function sendemail_survey_request($input) {
    $t_survey_id = $input['t_survey_id'];
    $email = new Koje_email();
    $email->data  = $this->CI->adodb->getRow("select * from v_survey where t_survey_id=?",array($t_survey_id));

    $email->data['target_date'] = dateFormat($email->data['target_date']);
    $services  ="<ul>";
    $services .= $email->data['conn'] =='Y' ? "<li>CONNECTIVITY</li>":"";
    $services .= $email->data['dc']   =='Y' ? "<li>DATA-CENTER</li>":"";
    $services .= $email->data['mgsrv']=='Y' ? "<li>MANAGE SERVICE</li>":"";
    $services .= $email->data['vicon']=='Y' ? "<li>VIDEO CONFERENCE</li>":"";
    $services .= $email->data['other']=='Y' ? "<li>OTHERS : ".$email->data['other_name']."</li>":"";
    $services .="</ul>";
    $email->data['services'] = $services;

    switch($email->data['status'])
    {
      case $this->getSurveyStatus('REQUEST') :
            $template_code='NOTIFY__SURVEY_REQUEST';
            $tpl 					= $this->CI->adodb->getRow("select * from t_template where code=?",array($template_code));
            $mail_to 	    = $this->getDataNotifEmail('PROVISIONING');
            break;
      case $this->getSurveyStatus('ONPROGRESS') :
            $template_code='NOTIFY__SURVEY_ONPROGRESS';
            $tpl 					= $this->CI->adodb->getRow("select * from t_template where code=?",array($template_code));
            $mail_to 	    = $this->CI->koje_system->getLoginEmail();
            break;
      case $this->getSurveyStatus('FINISHED') :
            $template_code='NOTIFY__SURVEY_FINISHED';
            $tpl 					= $this->CI->adodb->getRow("select * from t_template where code=?",array($template_code));
            $mail_to 	    = $this->CI->koje_system->getLoginEmail();
            break;
      case $this->getSurveyStatus('CANCEL') :
            $template_code='NOTIFY__SURVEY_CANCEL';
            $tpl 					= $this->CI->adodb->getRow("select * from t_template where code=?",array($template_code));
            $mail_to 	    = $this->getDataNotifEmail('PROVISIONING');
            break;
    }

    $subject = $email->replaceTag($tpl['subject']);
    $content = $email->replaceTag($tpl['content']);

    $email->clearAddress();
    $email->addAddress($mail_to);
    $email->Subject($subject);
    $email->msgHTML($content);
    $rslt = $email->send();
    $result[0] = '00';
    $result[1] = $rslt;
    return $result;
  }

  //------------------------- A P I -------------------------------------------
  function submitBillingOrder($billing_order)
  {
//    $this->CI->adodb_billing->debug = true;
//    $this->CI->adodb->debug = true;
    $response[1]='99';
    $acclist = $this->CI->adodb->execute("select a.* from t_billing_order a
    					where a.t_billing_order_id=$billing_order"
    				  );							
		//print_r($acclist);die;	
		while ($acc = $acclist->fetchrow()) {
			$company = json_decode($acc["data"],true);
			$product_service_list = $company["product_service_list"];
			$item_list = $company["product_item_list"];

			$bill_cp = $company["cp_1_name"];
			$d_bill_cp_dept = $company["cp_1_department"];
			$d_bill_cp_pos = null;
			$tech_cp = ["cp_2_name"];
			$d_tech_cp_dept = null;
			$d_tech_cp_pos = null;
			$com = $company["company_name"];
			$com_code = null;
			$d_business_type = null;
			$reg_method = null;
			$cust_group = $company["d_customer_group"];
			$seg = $company["d_segmentation"];
			$npwp = $company["company_npwp"];
			$website = null;
			$ct_com = $company["address_city_id"];
			$z_com = $company["address_zip"];
			$ad_com = $company["address_street"];
			$ad1_com = null;
			$ad2_com = null;
			$telp_com = $company["company_phone"];
			$fax_com = null;
			$email_com = ["cp_1_email"];
			$ct_bill = $company["address_city_id"];
			$z_bill = $company["address_zip"];
			$ad_bill = $company["address_street"];
			$ad1_bill = null;
			$ad2_bill = null;
			$telp_bill = $company["company_phone"];
			$fax_bill = null;
			$email_bill = $company["cp_1_email"];
			//di sini masalahnya
			$ct_tech = null;
			$z_tech = $company["address_zip"];
			$ad_tech = null;
			$ad1_tech = null;
			$ad2_tech = null;
			$telp_tech = $company["cp_2_mobile"];
			$fax_tech = null;
			$email_tech = $company["cp_2_email"];
			$provider = 1;
			$sales = $company["sales_id"];
			$area = null; //$input ["r_area_id"];
			$subarea = null; //$input ["r_sub_area_id"];
			$bank =  null; //$input ["r_bank_id"];
			$materai = null; //$input ["d_materai_charge"];
			$sof_no = null;
			$ba_no = null;
			$r_ro_id = $company["r_ro_id"];
			
			//Query Prospect_type -- set order type
			$rowProspect = $this->CI->adodb->getRow("select * from t_prospect where t_prospect_id=".$company["t_prospect_id"]);
			switch ($rowProspect["prospect_type"])
			{ 
				case 'PSTY-01':  //Installation
					$orty='ORTY-01';
					$pid=21;
					break;
				case 'PSTY-07':  //Installation
					$orty='ORTY-01';
					$pid=21;
					break;	
				case 'PSTY-03': //Project
					$orty='ORTY-09';
					$pid=23;
					break;
				case 'PSTY-05': //UPGRADE
					$orty='ORTY-02';
					$pid=21;
					break;
				case 'PSTY-06': //DOWNGRADE
					$orty='ORTY-03';
					$pid=21;
					break;
				default:
					$orty='ORTY-01';
					$pid=21;
			}
			

			if ($rowProspect["prospect_type"]=='PSTY-01'||$rowProspect['prospect_type']=='PSTY-03'||$rowProspect['prospect_type']=='PSTY-07') 
			{
				$result = $this->CI->adodb_billing->getRow ( "SELECT * FROM f_sam_insertaccount(?, current_date,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array (
					$company["t_prospect_id"],
					$bill_cp,
					$d_bill_cp_dept,
					$d_bill_cp_pos,
					$tech_cp,
					$d_tech_cp_dept,
					$d_tech_cp_pos,
					$com,
					$com_code,
					$d_business_type,
					$reg_method,
					$cust_group,
					$seg,
					$npwp,
					$website,
					$ct_com,
					$z_com,
					$ad_com,
					$ad1_com,
					$ad2_com,
					$telp_com,
					$fax_com,
					$email_com,
					$ct_bill,
					$z_bill,
					$ad_bill,
					$ad1_bill,
					$ad2_bill,
					$telp_bill,
					$fax_bill,
					$email_bill,
					$ct_tech,
					$z_tech,
					$ad_tech,
					$ad1_tech,
					$ad2_tech,
					$telp_tech,
					$fax_tech,
					$email_tech,
					$provider,
					$sales,
					$area,
					$subarea,
					$bank,
					$materai,
					$sof_no,
					$ba_no,
					$r_ro_id
				) );

				// insert Order Account Product
				$acid=$result[1];
				$acpid=$this->CI->adodb_billing->genID('seq_billing');


				$result1 = $this->CI->adodb_billing->getRow("SELECT * FROM f_sam_insertorderaccountproduct(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
					array(2,$acid, $pid, $acpid, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,$orty));

				// insert account product item
				foreach ($item_list as $item) {
					$pi_id = $item["t_product_item_id"];
					$pi_nm = $item["name"];
					$pi_amount = $item["amount"];
					$pi_tax = $item["tax"];
					$result2 = $this->CI->adodb_billing->getRow("SELECT * FROM f_sam_insertorderproductitem(?,?,?,?,?)",
						array($acpid, $pi_id, $pi_nm, $pi_amount, $pi_tax));
				}

				// insert circuit / product service
				foreach ($product_service_list as $product) {
					$pcode = $product["t_product_service_id"];
					$cap = $product["speed"];
					$uom = $product["uom"];
					$result3 = $this->CI->adodb_billing->getRow("SELECT * FROM f_sam_insertcircuit(?,?,?,?,?)",
						array($acpid, $pcode, $cap, $uom, $cust_group));
				}
				
				$rowProduct = $this->CI->adodb_billing->getRow("select * from v_order_account_product_bk  where t_account_product_id=".$acpid);
				$this->CI->adodb_billing->execute("update t_order_account_product set name='".$rowProduct['string_agg']."' where t_account_product_id=".$acpid);
				$response[1] = '00';
				$response[2] = 'DONE';
				$rst = $this->CI->adodb->execute("update t_billing_order set status='SUBMIT', result=? where t_billing_order_id=?", array(json_encode($response), $acc["t_billing_order_id"]));
			} // End if prospectType==PSTY-01, PSTY-03
			// UPGRADE AND DOWNGRADE
			else if ($rowProspect["prospect_type"]=='PSTY-05'||$rowProspect['prospect_type']=='PSTY-06') 
			{
				$acid = $rowProspect['t_account_id'];
				$acpid = $rowProspect['t_account_product_id']; // old acpid
				$nacpid=$this->CI->adodb_billing->genID('seq_billing'); // new acpid
				$speed = $rowProspect['speed'];
				$uom = $rowProspect['d_uom'];
				$newName = $rowProspect['prospect_name'];
				
				// copy order account product from old product
				$result1 = $this->CI->adodb_billing->getRow("SELECT * FROM f_sam_copyorderaccountproduct(?,?,?,?,?,?,?)",
					array(2,$acid, $acpid, $nacpid, $orty, $speed, $uom));
					
				// insert account product item
				foreach ($item_list as $item) {
					$pi_id = $item["t_product_item_id"];
					$pi_nm = $item["name"];
					$pi_amount = $item["amount"];
					$pi_tax = $item["tax"];
					$result2 = $this->CI->adodb_billing->getRow("SELECT * FROM f_sam_insertorderproductitem(?,?,?,?,?)",
						array($nacpid, $pi_id, $pi_nm, $pi_amount, $pi_tax));
				}
				$this->CI->adodb_billing->execute("update t_order_account_product set name='".$newName."' where t_account_product_id=".$nacpid);	
			}
		}

		return $response;
		//print(json_encode($response));
  }
  function getProspectDuplicate($t_quotation_id) {
    $pagerID = PAGER::P3;
    $str = '';
    $t_prospect_id = $this->CI->adodb->getOne("select t_prospect_id from t_quotation where t_quotation_id=?", array($t_quotation_id));
    $company_npwp = $this->CI->adodb->getOne("select company_npwp from v_prospect where t_prospect_id=?", array($t_prospect_id));
	$company_phone = $this->CI->adodb->getOne("select company_phone from v_prospect where t_prospect_id=?", array($t_prospect_id));
	$company_address_info = $this->CI->adodb->getOne("select company_address_info from v_prospect where t_prospect_id=?", array($t_prospect_id));

    if($t_prospect_id) {
      $sql  = "select * from v_prospect where t_prospect_id <> $t_prospect_id and (company_npwp = '$company_npwp' or company_phone = '$company_phone' or company_address_info = '$company_address_info')";
      $table = new koje_datatables($pagerID);
      $str .= '<font color=red>'.$table->renderDataTable(
        array(
          'id' 		=> $pagerID,
          'search'=> '',
          'title' => "Duplicated Prospect by Company NPWP",
          'primary_key' => 't_prospect_id',
          "query" => array(	 "from" 	=> "FROM ($sql) a",
                             "where"  => "WHERE 1=1",
                             "params" => array(),
                    ),
          'order' => array(
                      PAGER::COL6=>'asc',
                      PAGER::COL1=>'asc',
                    ),
          'toolbarButton' => array( ),
          'columns' => array(
                          'sales_name' 					=> array(),
                          'prospect_no'         => array(),
                          'prospect_name' 			=> array(),
                          'status_desc' 			  => array(),
                          'prospect_start_date' => array('format'=>'date'),
                          'prospect_end_date' 	=> array('format'=>'date'),
                          'company_name' 				=> array(),
                          'company_npwp' 				=> array(),
                          'company_address_info'=> array(),
                          'description'				  => array(),
                          'sys_created_date' 		=> array('format'=>'date'),
                    ),
              'summary'	=> array(),
            )
      ).'</font>';
      }
      return $str;
  }
  function getProspectDuplicateByID($t_prospect_id) {
    $pagerID = PAGER::P3;
    $str = '';
    $company_npwp = $this->CI->adodb->getOne("select company_npwp from v_prospect where t_prospect_id=?", array($t_prospect_id));
    $cnt = $this->CI->adodb->getOne("select count(*) from v_prospect where t_prospect_id <> $t_prospect_id and company_npwp = '$company_npwp'");
    if ($cnt==0) {
      return $str;
    }
    if($t_prospect_id) {
      $sql  = "select * from v_prospect where t_prospect_id <> $t_prospect_id and company_npwp = '$company_npwp'";
      $table = new koje_datatables($pagerID);
      $str .= '<font color=red>'.$table->renderDataTable(
        array(
          'id' 		=> $pagerID,
          'search'=> '',
          'title' => "Duplicated Company NPWP",
          'primary_key' => 't_prospect_id',
          "query" => array(	 "from" 	=> "FROM ($sql) a",
                             "where"  => "WHERE 1=1",
                             "params" => array(),
                    ),
          'order' => array(
                      PAGER::COL1=>'asc',
                    ),
          'toolbarButton' => array( ),
          'columns' => array(
                          'sales_name' 					=> array(),
                          'company_name' 				=> array(),
                          'company_npwp' 				=> array(),
                    ),
              'summary'	=> array(),
            )
      ).'</font>';
      }
      return $str;
  }
  
   function getPICName($sys_users_id)
  {
    $result = $this->CI->adodb->getRow("select first_name, last_name, emp_position from sys_users where id=$sys_users_id");
    // echo "<pre>";
	// var_dump("[".$result['emp_position']."] " .$result['first_name']." ". $result['last_name']);
	// echo "</pre>";
	// die();
	return "[".$result['emp_position']."] " .$result['first_name']." ". $result['last_name'];
  }
}
