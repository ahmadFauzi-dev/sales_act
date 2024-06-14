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
				$jj = $this->CI->app_lib->arrMonth[$i-1];
				$s .= ",sum(utils_case(to_char(start_date,'MM'),'{$j}',utils_case(a.t_stage_id::text,'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0'),'0')::numeric) as closing_{$jj}";
				$s .= ",coalesce(d.closing_{$jj},0) as target_{$jj}";
			}

			$s .= ",sum(utils_case(utils_case(b.current_stage_id::text,'1',a.t_stage_id::text,'0'),'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0')::numeric) as closing_active";
			$s .= ",sum(utils_case(a.t_stage_id::text,'7',getquotationitemdealbyprospect(b.t_prospect_id,'$year')::text,'0')::numeric) as closing_all";
			$s .= ",coalesce(d.closing_jan_01+d.closing_feb_02+d.closing_mar_03+d.closing_apr_04+d.closing_may_05+d.closing_jun_06+d.closing_jul_07+d.closing_aug_08+d.closing_sep_09+d.closing_oct_10+d.closing_nov_11+d.closing_dec_12,0) as target_all";
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
				select concat_ws(' ',first_name,last_name) as username, '$year' as year,
				utils_get_operator_name(utils_get_leader(utils_get_leader(utils_get_leader(c.id)))) leader
				$s
				from sys_users c
				left join t_prospect b on b.sales_id=c.id
				left join t_prospect_stage a on a.t_prospect_id=b.t_prospect_id and to_char(start_date,'YYYY')='$year'
				left join t_target d on d.sales_id=c.id and d.year='$year'
				where c.emp_position='AE' and c.active=1
				 group by c.id, d.closing_jan_01,d.closing_feb_02,d.closing_mar_03,d.closing_apr_04,d.closing_may_05,d.closing_jun_06,d.closing_jul_07,d.closing_aug_08,d.closing_sep_09,d.closing_oct_10,d.closing_nov_11,d.closing_dec_12
				 order by leader, id
			) q1";
//			print $sql;
			$rs = $this->CI->adodb->execute($sql);
			$data = array();
			$cols = array(  'leader',
											'username',
										  'target_all',
											'closing_all',
											'target_jan_01',
											'closing_jan_01',
											'target_feb_02',
											'closing_feb_02',
											'target_mar_03',
											'closing_mar_03',
											'target_apr_04',
											'closing_apr_04',
											'target_may_05',
											'closing_may_05',
											'target_jun_06',
											'closing_jun_06',
											'target_jul_07',
											'closing_jul_07',
											'target_aug_08',
											'closing_aug_08',
											'target_sep_09',
											'closing_sep_09',
											'target_oct_10',
											'closing_oct_10',
											'target_nov_11',
											'closing_nov_11',
											'target_dec_12',
											'closing_dec_12'
										);
			$dataSummary = array();
			$dataSummaryMgr = array();
			while($row = $rs->fetchRow())
			{
				$leader = $row['leader'] ? $row['leader'] : 'no-mgr';
				if(!isset($dataSummaryMgr[$leader])) $dataSummaryMgr[$leader] = array();
				foreach ($cols as $key => $value)
				{
					if(!isset($dataSummary[$value])) $dataSummary[$value] = 0;
					$dataSummary[$value] = is_numeric($row[$value]) ? $dataSummary[$value] + $row[$value] : "";
					$row1[$value] = is_numeric($row[$value]) ? $this->CI->koje_system->number_format($row[$value]) : $row[$value];

					if(!isset($dataSummaryMgr[$leader][$value])) $dataSummaryMgr[$leader][$value] = 0;
					$dataSummaryMgr[$leader][$value] = is_numeric($row[$value]) ? $dataSummaryMgr[$leader][$value] + $row[$value] : "";
				}
				$dataSummary['username'] = "SUMMARY";
				$data[] = $row1;
			}

			$table = array(
										'id'	=> $pagerID,
										'cols' => $cols,
										'caption' => '',
										'heading' => ' <tr>
																			<th rowspan="2">Leader</th>
																			<th rowspan="2">Sales</th>
																			<th colspan="2">ALL</th>
											                <th colspan="2">Januari</th>
																			<th colspan="2">Februari</th>
																			<th colspan="2">March</th>
																			<th colspan="2">April</th>
																			<th colspan="2">May</th>
																			<th colspan="2">June</th>
																			<th colspan="2">July</th>
																			<th colspan="2">August</th>
																			<th colspan="2">September</th>
																			<th colspan="2">October</th>
																			<th colspan="2">November</th>
																			<th colspan="2">December</th>
											            </tr>
											            <tr>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											                <th>Target</th>
											                <th>Closing</th>
											            </tr>
																	',
										'data' => $data,
			);

			$str = "<h4 class='text-center'>Report Monthly Closing {$year}</h4>";
			$str .= '<h5 class="text-center">Year : '.form_dropdown('year',$this->CI->koje_system->listYear(date("Y")-3,date("Y")),$year,'onchange="reload(this)"').'</h5>';

			$table = html_table($table);
			$smrMgr= '<tr><td colspan=28>S U M M A R Y</td></tr>';
			foreach ($dataSummaryMgr as $key0 => $value0) {
				$smrMgr .= "<tr><td colspan=2>$key0</td>";
				foreach ($value0 as $key => $value) {
					if(!is_numeric($value)) continue;
					$value = is_numeric($value) ? $this->CI->koje_system->number_format($value) : $value;
					$smrMgr .= "<td>$value</td>";
				}
				$smrMgr .= "</tr>";
			}

			$smr = "<tr>";
			foreach ($dataSummary as $key => $value) {
				$value = is_numeric($value) ? $this->CI->koje_system->number_format($value) : $value;
				$smr .= "<td>$value</td>";
			}
			$smr .= "</tr>";


			$table = str_replace('<td></td><td>SUMMARY','<td colspan=2>TOTAL',str_replace('</table>',"{$smrMgr}{$smr}</table>",$table));

			$str .= $table;
			print $str;
		}
	}
	?>
<script>
	function reload(that)
	{
		year = $(that).val();
		redirect(base_url+'/report/report7_controller?year='+year);
	}
</script>
