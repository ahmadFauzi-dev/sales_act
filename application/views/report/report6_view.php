<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new report6_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class report6_view {
		var $CI;
		function __construct() {
			$this->CI =& get_instance();
		}
		function index($params=false)
		{
			$loginID = $this->CI->koje_system->getLoginID();
			
			$where ="and ($loginID=1 or $loginID=54 or  a.sales_id=$loginID or utils_is_leader_from($loginID,a.sales_id)='Y')";
			$str = $this->_listStatistic($where). '<br/>'.
						 $this->_listTableu($where);
			print $str;
		}
		function _listStatistic($where) {
			$sql = "select
								sum(case when t_stage_id=1 then target_amount else 0 end) amount_opportunity,
								sum(case when t_stage_id=1 then 1 else 0 end) cnt_opportunity,
								sum((select count(*) from t_telemarketing x where x.t_prospect_id=a.t_prospect_id)) cnt_telemarketing,
								sum(case when t_stage_id=3 then target_amount else 0 end) amount_proposal,
								sum(case when t_stage_id=3 then 1 else 0 end) cnt_proposal,
								sum((select count(*) from t_proposal x where x.t_prospect_id=a.t_prospect_id)) cnt_sending,
								sum((select count(*) from t_presentation x where x.t_prospect_id=a.t_prospect_id)) cnt_presentation,
								sum((select count(*) from t_survey x where x.t_prospect_id=a.t_prospect_id)) cnt_survey,
								sum(case when t_stage_id=5 then target_amount else 0 end) amount_negotiation,
								sum(case when t_stage_id=5 then 1 else 0 end) cnt_negotiation,
								sum((select count(*) from t_requirement x where x.t_prospect_id=a.t_prospect_id)) cnt_requirement,
								sum((select count(*) from t_quotation x where x.t_prospect_id=a.t_prospect_id)) cnt_quotation,
								sum((select count(*) from t_negotiation x where x.t_prospect_id=a.t_prospect_id)) cnt_negotiation1,
								sum(case when t_stage_id=7 then getquotationitemdealbyprospect(a.t_prospect_id,'') else 0 end) amount_closing,
								sum(case when t_stage_id=7 then 1 else 0 end) cnt_closing
							from v_prospect_current a
							where status='PSST-01' $where";
			$row = $this->CI->adodb->getRow($sql);
?>
	<section class="content-header">
		<h1>
			Dashboard
			<small>Prospect Control Panel</small>
		</h1>
	</section>
	<section class="content">
		<h5>Active Prospect</h5>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-th-large"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">opportunity (30%)</span>
            <span class="info-box-number"><?php print $row['cnt_opportunity']?> record(s)</span>
            <span class="info-box-number">Rp. <?php print number_format($row['amount_opportunity'])?></span>
          </div>
        </div>
				<div class="info-box bg-secondary">
          <span class="info-box-icon bg-aqua"><span class='info-box-number'>30 %</span></span>
					<div class="info-box-content">
						<span class="info-box-number">Telemarketing & Email</span>
						<span class="progress-description"><?php print $row['cnt_telemarketing']?> record(s)</span>
					</div>
				</div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-database"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Proposal (50%)</span>
						<span class="info-box-number"><?php print $row['cnt_proposal']?> record(s)</span>
            <span class="info-box-number">Rp. <?php print number_format($row['amount_proposal'])?></span>
          </div>
        </div>
				<div class="info-box bg-secondary">
          <span class="info-box-icon bg-red"><span class='info-box-number'>50 %</span></span>
					<div class="info-box-content">
						<span class="info-box-number">Sending Proposal</span>
						<span class="progress-description"><?php print $row['cnt_proposal']?> record(s)</span>
						<span class="info-box-number">Presentation</span>
						<span class="progress-description"><?php print $row['cnt_presentation']?> record(s)</span>
						<span class="info-box-number">Survey</span>
						<span class="progress-description"><?php print $row['cnt_survey']?> record(s)</span>
					</div>
				</div>
      </div>
      <div class="clearfix visible-sm-block"></div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-building"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Negotiation (70%)</span>
						<span class="info-box-number"><?php print $row['cnt_negotiation']?> record(s)</span>
            <span class="info-box-number">Rp. <?php print number_format($row['amount_negotiation'])?></span>
          </div>
        </div>
				<div class="info-box bg-secondary">
          <span class="info-box-icon bg-green"><span class='info-box-number'>70 %</span></span>
					<div class="info-box-content">
						<span class="info-box-number">Quotation</span>
						<span class="progress-description"><?php print $row['cnt_quotation']?> record(s)</span>
						<span class="info-box-number">Negotiation</span>
						<span class="progress-description"><?php print $row['cnt_negotiation1']?> record(s)</span>
					</div>
				</div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-desktop"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Closing (100%)</span>
						<span class="info-box-number"><?php print $row['cnt_closing']?> record(s)</span>
            <span class="info-box-number">Rp. <?php print number_format($row['amount_closing'])?></span>
          </div>
        </div>
      </div>
    </div>
	</section>
<?php
		}
		function _listTableu($params=false) {
			$url = $this->CI->config->item('tableu_server_url');
			$loginID = $this->CI->koje_system->getLoginID()*999;
			$str = "";
			$str .= "<h4>Report Graph</h4><iframe
		 					src='$url/views/sales_revenue/SalesActivityWinByMgr_1?:embed=yes&:toolbar=no&:tabs=no&user_id=$loginID'
		 					style='width:1200px; height:500px; border:solid 1px #bdbbbb;'
		 				>
		 				 </iframe>";
			$str .= "<h4>Detail Report</h4><iframe
							src='$url/views/sales_revenue/SalesActivityWin_1?:embed=yes&:toolbar=no&:tabs=no&user_id=$loginID'
							style='width:1200px; height:700px; border:solid 1px #bdbbbb;'
						>
						 </iframe>";
			return $str;
		}
	}
	?>
