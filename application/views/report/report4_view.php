<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new report4_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class report4_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false) {
			$chart = new koje_chart();
			$chart->setType('mixed');

			$year = ifNull($this->CI->input->get_post('year'),date("Y"));
			$month = ifNull($this->CI->input->get_post('month'),date("m"));

			$chart->setType('mixed');
			$chart->setLabel($this->CI->koje_system->listDaysInMonth($year,$month));
			$chart->setLabelY('total prospect');
			$chart->setBgColor(array('red','blue','green','orange','red','blue','green','orange'));

			$loginID = $this->CI->koje_system->getLoginID();
			$where ="and ($loginID=1 or $loginID=54 or c.sales_id=$loginID or utils_is_leader_from($loginID,c.sales_id)='Y')";

			$revenue = $this->CI->app_lib->getDataSumRevenueByYear($year,$where);
			$target = $this->CI->app_lib->getDataSumTargetbyYear($year,$where);
			$revenue_month = $this->CI->app_lib->getDataRevenueByMonth($year,$month,$where);
			$total_revenue_month = 0;
			if(isset($revenue_month['closing'] ))
			{
				foreach ($revenue_month['closing'] as $key => $value) {
					$total_revenue_month += $value;
				}
			}
			$target_month = $target["closing_".$this->CI->app_lib->arrMonth[$month-1]];

			$chart->setTitle(array('DAILY PROSPECT STAGE ',
														 getArrayVal($this->CI->app_lib->arrMonthLabel,$month-1).' '.$year,
												 	 	 'Total Revenue : '. $this->CI->koje_system->number_format($total_revenue_month),
														 'Total Target : '.$this->CI->koje_system->number_format($target_month)
													 )
											);
			$chart->setDataset(array(
														array(
															'type'						=> 'bar',
															'label'						=> 'opportunity',
															'fill'						=> 'false',
															'data'						=> getArrayVal($revenue_month,'opportunity') ,
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'proposal',
															'data'						=> getArrayVal($revenue_month,'proposal') ,
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'negotiation',
															'data'						=> getArrayVal($revenue_month,'negotiation') ,
														),
														array(
															'type'						=> 'bar',
															'label'						=> 'closing',
															'data'						=> getArrayVal($revenue_month,'closing_cnt') ,
														),
												)
											);
			$str  = "<h4 class='text-center'>Report Prospect ".$this->CI->app_lib->arrMonthLabel[$month-1].' '.$year."</h4>";
			$str .= '<h5 class="text-center">Year '.form_dropdown('year',$this->CI->koje_system->listYear(date("Y")-3,date("Y")),$year,'id="year" onchange="reload()"');
			$str .= 'Month '.form_dropdown('month',$this->CI->app_lib->arrMonthLabel,$month-1,'id="month" onchange="reload()"').'</h5>';

			$str .= $chart->generateChart();

			print $str;
		}
	}
?>
<script>
	function reload()
	{
		year = $("#year").val();
		month = intVal($("#month").val())+1;
		redirect(base_url+'/report/report4_controller?year='+year+'&month='+month);
	}
</script>
