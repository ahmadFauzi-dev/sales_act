<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new report3_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class report3_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function _ss($year)
		{
			$s= "";
			for($i=1;$i<=12;$i++)
			{
				$j = str_pad($i,2,'0',STR_PAD_LEFT);
				$s .= ",sum(utils_case(to_char(start_date,'MM'),'{$j}',utils_case(a.t_stage_id::text,'1','1','0'),'0')::numeric) as opportunity_{$j}";
				$s .= ",sum(utils_case(to_char(start_date,'MM'),'{$j}',utils_case(a.t_stage_id::text,'3','1','0'),'0')::numeric) as proposal_{$j}";
				$s .= ",sum(utils_case(to_char(start_date,'MM'),'{$j}',utils_case(a.t_stage_id::text,'5','1','0'),'0')::numeric) as negotiation_{$j}";
				$s .= ",sum(utils_case(to_char(start_date,'MM'),'{$j}',utils_case(a.t_stage_id::text,'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0'),'0')::numeric)  as closing_{$j}";
			}

			$s .= ",sum(utils_case(utils_case(b.current_stage_id::text,'1',a.t_stage_id::text,'0'),'1','1','0')::numeric) as opportunity_active";
			$s .= ",sum(utils_case(utils_case(b.current_stage_id::text,'1',a.t_stage_id::text,'0'),'3','1','0')::numeric) as proposal_active";
			$s .= ",sum(utils_case(utils_case(b.current_stage_id::text,'1',a.t_stage_id::text,'0'),'5','1','0')::numeric) as negotiation_active";
			$s .= ",sum(utils_case(utils_case(b.current_stage_id::text,'1',a.t_stage_id::text,'0'),'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0')::numeric)  as closing_active";

			$s .= ",sum(utils_case(a.t_stage_id::text,'1','1','0')::numeric) as opportunity_all";
			$s .= ",sum(utils_case(a.t_stage_id::text,'3','1','0')::numeric) as proposal_all";
			$s .= ",sum(utils_case(a.t_stage_id::text,'5','1','0')::numeric) as negotiation_all";
			$s .= ",sum(utils_case(a.t_stage_id::text,'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0')::numeric)  as closing_all";

			return $s;
		}
		function index($params=false) {
			$pagerID = PAGER::P1;
			$myForm = new KOJE_Form('MY_SEARCH');
			$myForm->linkSearchForm($pagerID);
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$loginID = $this->CI->koje_system->getLoginID();
			$strSearch = $myForm->renderSearchDataTables();
			$table = new koje_datatables($myForm->id);

			$year = ifNull($this->CI->input->get_post('year'),date("Y"));

			$s = $this->_ss($year);
			$sql = "
			select * from(
				select concat_ws(' ',first_name,last_name) as username, '$year' as year
				$s
				from sys_users c
				left join t_prospect b on b.sales_id=c.id
				left join t_prospect_stage a on a.t_prospect_id=b.t_prospect_id and to_char(start_date,'YYYY')='$year'
				where c.emp_position='AE' and c.active=1
							and ($loginID=1 or $loginID=54 or sales_id=$loginID or utils_is_leader_from($loginID,sales_id)='Y')
				 group by c.id
				 order by id
			) q1";
			$rs = $this->CI->adodb->execute($sql);
			$data = array();
			$cols = array('year','username',
											'opportunity_active','proposal_active','negotiation_active','closing_active',
											'opportunity_all','proposal_all','negotiation_all','closing_all',
											'opportunity_01','proposal_01','negotiation_01','closing_01',
											'opportunity_02','proposal_02','negotiation_02','closing_02',
											'opportunity_03','proposal_03','negotiation_03','closing_03',
											'opportunity_04','proposal_04','negotiation_04','closing_04',
											'opportunity_05','proposal_05','negotiation_05','closing_05',
											'opportunity_06','proposal_06','negotiation_06','closing_06',
											'opportunity_07','proposal_07','negotiation_07','closing_07',
											'opportunity_08','proposal_08','negotiation_08','closing_08',
											'opportunity_09','proposal_09','negotiation_09','closing_09',
											'opportunity_10','proposal_10','negotiation_10','closing_10',
											'opportunity_11','proposal_11','negotiation_11','closing_11',
											'opportunity_12','proposal_12','negotiation_12','closing_12'
										);
			$dataSummary = array();
			while($row = $rs->fetchRow())
			{
				foreach ($cols as $key => $value)
				{
					if(!isset($dataSummary[$value])) $dataSummary[$value] = 0;
					$dataSummary[$value] = is_numeric($row[$value]) && $key>1 ? $dataSummary[$value] + $row[$value] : "";
					$row1[$value] = is_numeric($row[$value]) && $key>1 ? $this->CI->koje_system->number_format($row[$value]) : $row[$value];
				}
				$dataSummary['username'] = "SUMMARY";
				$data[] = $row1;
			}

			$table = array(
										'id'	=> $pagerID,
										'cols' => $cols,
										'caption' => '',
										'heading' => ' <tr>
											                <th rowspan="2">Sales</th>
																			<th rowspan="2">Year</th>
																			<th colspan="4">ACTIVE</th>
																			<th colspan="4">ALL</th>
											                <th colspan="4">Januari</th>
																			<th colspan="4">Februari</th>
																			<th colspan="4">March</th>
																			<th colspan="4">April</th>
																			<th colspan="4">May</th>
																			<th colspan="4">June</th>
																			<th colspan="4">July</th>
																			<th colspan="4">August</th>
																			<th colspan="4">September</th>
																			<th colspan="4">October</th>
																			<th colspan="4">November</th>
																			<th colspan="4">December</th>
											            </tr>
											            <tr>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											                <th>opportunity</th>
											                <th>Proposal</th>
											                <th>Negotiation</th>
											                <th>Closing</th>
											            </tr>
																	',
										'data' => $data,
			);

			$str = "<h4 class='text-center'>Report Prospect {$year}</h4>";
			$str .= '<h5 class="text-center">Year : '.form_dropdown('year',$this->CI->koje_system->listYear(date("Y")-3,date("Y")),$year,'onchange="reload(this)"').'</h5>';

			$table = html_table($table);
			$smr = "";
			foreach ($dataSummary as $key => $value) {
				$value = is_numeric($value) ? $this->CI->koje_system->number_format($value) : $value;
				$smr .= "<td>$value</td>";
			}
			$table = str_replace('</table>',"<tfoot><tr>{$smr}</tr></tfoot></table>",$table);

			$str .= $table;

			print $str;
		}
	}
	?>
<script>
	function reload(that)
	{
		year = $(that).val();
		redirect(base_url+'/report/report3_controller?year='+year);
	}
</script>
