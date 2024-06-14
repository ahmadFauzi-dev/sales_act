<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class koje_app_adapter extends KOJE_Controller {
	function __construct() {
		parent::__construct();
	}
	function changeEventPos() {
		$CI =& get_instance();
		$params = $_REQUEST;
		print_r($params);
		print json_encode($CI->db_libs->eventEdit($params));
	}
	function stage() {
		$CI =& get_instance();
    $t_opportunity_id = $_REQUEST['id'];
    $x = $_REQUEST['x'];
    $stage = 'OTST-'.str_pad($x+1,2,'0',STR_PAD_LEFT);
    $status = ($stage=='OTST-05' || $stage=='OTST-06' || $stage=='OTST-07') ? "C" : "A";
    $probability = 'OPPB-'.str_pad($x+1,2,'0',STR_PAD_LEFT);

    $amount = $CI->adodb->getOne("select amount from t_opportunity where t_opportunity_id=?", array($t_opportunity_id));
    $params = array('stage' => $stage, 'status' => $status, 'probability' => $probability, 'amount' => $amount);
    $result = $CI->adodb->autoExecute("t_opportunity", $params, "UPDATE", "t_opportunity_id=$t_opportunity_id");
    if($result===true) {
      $params['result'] = "Stage Changed";
    }
    else {
      $params['result'] = "Error";
    }
    print json_encode($params);
	}

	function getEventLead() {
		$CI =& get_instance();
    $start = $CI->input->get_post('start');
    $end  = $CI->input->get_post('end');
		$rs = $CI->adodb->execute("select * from t_event where
                               end_time >= ? and start_time < ?",
                                array($start, $end)
                              );
    $data = array();
    while ($row = $rs->fetchRow()) {
      $data[] = array(
        'id' => $row['t_event_id'],
	      'title' => $row['subject'],
        'start' => $row['start_time'],
        'end'   => $row['end_time'],
        'description' => $row['description'].' ',
        'content' =>
                  '<div class="">
                    <ul class="list-unstyled">
                      <li>
                        '.$row['subject'].'</b>
                      </li>
                      <li>
                        start : '.$row['start_time'].'
                      </li>
                      <li>
                        until : '.$row['end_time'].'
                      </li>
                      <li>
                        '.$row['description'].'
                      </li>
                    </ul>
                  </div>'
      );
    }
		print json_encode($data);
	}
}
?>
