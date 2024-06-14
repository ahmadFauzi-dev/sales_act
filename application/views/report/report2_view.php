<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new report2_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class report2_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false) {
			$chart = new koje_chart();
			$year = ifNull($this->CI->input->get_post('year'),date("Y"));
			$chart->setType('mixed');
			$chart->setLabel($this->CI->app_lib->arrMonthLabel);
			$chart->setLabelY('total prospect');
			$chart->setBgColor(array('red','blue','green','orange','red','blue','green','orange'));
			$loginID = $this->CI->koje_system->getLoginID();
			$where ="and ($loginID=1 or $loginID=54 or c.sales_id=$loginID or utils_is_leader_from($loginID,c.sales_id)='Y')";

			$revenue = $this->CI->app_lib->getDataSumRevenueByYear($year,$where);
			$target = $this->CI->app_lib->getDataSumTargetbyYear($year,$where);

			$total_revenue = 0;

			if(isset($revenue['closing'] ))
			{
				foreach ($revenue['closing'] as $key => $value) {
					$total_revenue += $value;
				}
			}
			$total_target = 0;
			$target = $this->CI->app_lib->getDataSumTargetbyYear($year,$where);
			if(isset($target['closing'] ))
			{
				foreach ($target['closing'] as $key => $value) {
					$total_target += $value;
				}
			}

			$chart->setTitle(array('STAGE V.S. TARGET '.$year,
														 'Total Revenue : '. $this->CI->koje_system->number_format( $total_revenue),
														 'Total Target : '.$this->CI->koje_system->number_format($total_target)
													 )
											);
			$chart->setDataset(array(
														array(
															'type'						=> 'bar',
															'label'						=> 'opportunity',
															'fill'						=> 'false',
															'data'						=> $revenue['opportunity'],
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'proposal',
															'data'						=> $revenue['proposal'],
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'negotiation',
															'data'						=> $revenue['negotiation'],
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'closing',
															'data'						=> $revenue['closing_cnt'],
														),
														array(
															'type'						=> 'line',
															'label'						=> 'target opportunity',
															'fill'						=> 'false',
															'data'						=> $target['opportunity'],
														),
														array(
															'type'						=> 'line',
															'label'						=> 'target proposal',
															'fill'						=> 'false',
															'data'						=> $target['proposal'],
														),
														array(
															'type'						=> 'line',
															'label'						=> 'target negotiation',
															'fill'						=> 'false',
															'data'						=> $target['negotiation'],
														),
												)
											);

		  print '<h5 class="text-center">Year '.form_dropdown('year',$this->CI->koje_system->listYear(date("Y")-3,date("Y")),$year,'id="year" onchange="reload()"');
			print $chart->generateChart();
		}
	}

	?>
	<script>
		function reload()
		{
			year = $("#year").val();
			redirect(base_url+'/report/report2_controller?year='+year);
		}
	</script>
