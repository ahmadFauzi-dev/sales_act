<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

/*
		if(isset($_REQUEST['val__min']) && $_REQUEST['val__max']!="" && isset($_REQUEST['val__min']) && $_REQUEST['val__max']!="") {
			$ws .= ' AND reference_id between :val__min and :val__max';
			$wp['val__min'] =  $_REQUEST['val__min'];
			$wp['val__max'] =  $_REQUEST['val__max'];
		}

		$query = array(
        "from" => "FROM t_reference a",
        "where" => "WHERE operator_id=:operator_id and date between :start_date and :end_date",
        "params" => ("operator_id" => session['operator_id'] ,"start_date" => "2018-01-01", "end_date" => "2018-01-31"),
    );
*/

class getJSON extends KOJE_Controller {
	var $where = "";
	var $params = array();
	protected $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
	}

  function index() {
    if($this->CI->input->get_post('db')=='billing') {
      $this->CI->adodb = $this->CI->adodb_billing;
    }
		$from =$this->CI->koje_system->base64Decode($this->CI->input->get_post('f'));
		$where =$this->CI->koje_system->base64Decode($this->CI->input->get_post('w'));
		$query = array(
	        "from" 		=> $from,
	        "where" 	=> $where,
	        "params" 	=> array(),
			);
			$params = $this->generateQuery($query, $_REQUEST);

			$dataTable = new koje_datatables();
	    $dataTable->getDataTables($params);
	    error_log($dataTable->getRawSql());
	}

	function generateWhereOracle($input) {
		$pss = $input['pager_id'].'ss_';
		foreach ($input as $key => $value) {
			$pos = strpos($key,$pss);
			if ($pos !== false) {
				$col = substr($key,$pos+strlen($pss));
				$coln = str_replace('.','_',$col);
				$this->where .= " AND {$col}::text ilike :{$coln}";
				$this->params[$coln] =  trim("%" . $input[$key] . "%");
			}
		}
	}
	function generateWhere($input) {
		$pss = $input['pager_id'].'ss_';
		foreach ($input as $key => $value) {
			if($input[$key])
			{
				$pos = strpos($key,$pss);
				if ($pos !== false) {
					$col = substr($key,$pos+strlen($pss));
					$coln = str_replace('.','_',$col);
					$this->where .= " AND {$col}::text ilike ?";
					$this->params[] =  trim("%" . trim($input[$key]) . "%");
				}
			}
		}
	}
	function generateQuery($query, $input=false)
	{
		if(!$input) {$input = $_REQUEST;};

		$this->generateWhere($input);
		$params = array(
        "from" 		=> $query['from'],
        "where" 	=> $query['where'] . $this->where,
        "params" 	=> array_merge($query['params'], $this->params),
    );
		return $params;
	}

	function prospectSetData() {
		$row 				= $this->CI->adodb->getRow("select * from t_prospect where t_prospect_id=?", array($_REQUEST['id']));
    $params 		= array('result'=>'DONE','status' => $row['status'], 'amount'=>$row['target_amount']);
    print json_encode($params);
	}

}
