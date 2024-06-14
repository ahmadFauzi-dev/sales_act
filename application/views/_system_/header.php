<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$html_header =  $this->koje_system->load_asset();
	$img = img(array('src' => $this->koje_system->getLoginPictURL(),'class'=>'user-image', 'alt'=>'User Image','onclick'=>'editPict()','title'=>'Click to Edit','style' =>'cursor:pointer'));
	$breadcrumb = $this->koje_system->html_breadcrumb();
	$menu = $this->koje_system->html_side_menu();
	$username = $this->koje_system->getLoginUser();
	$title_lg = $this->koje_config['KOJE_TITLE_LG'];
	$title_mini = $this->koje_config['KOJE_TITLE_MINI'];
	$user_id = $this->koje_system->getLoginID();
	$prospectCnt = $this->CI->app_lib->getTotalProspectActive($user_id);
	$opportunity = $this->CI->app_lib->getTotalOpportunityActive($user_id);
	$proposal 	 = $this->CI->app_lib->getTotalProposalActive($user_id);
	$negotiation = $this->CI->app_lib->getTotalNegotiationActive($user_id);
	$position    = $this->CI->session->userdata('emp_position');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php print $html_header; ?>
	</head>
	<body class="sidebar-mini fixed skin-blue-light sidebar-collapse">
		<div class="wrapper">
		  <header class="main-header">
		    <a href="#" class="logo">
		      <span class="logo-mini"><?php print $title_mini?></span>
		      <span class="logo-lg"><?php print $title_lg?></span>
		    </a>
		    <nav class="navbar navbar-static-top">
		      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
		        <span class="sr-only">Toggle navigation</span>
		      </a>
		      <div class="navbar-custom-menu">
		        <ul class="nav navbar-nav">
		          <li class="dropdown tasks-menu">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              <i class="fa fa-flag-o"></i>
		              <span class="label label-danger"><?php print $prospectCnt?></span>
		            </a>
		            <ul class="dropdown-menu">
		              <li class="header">You have <?php print $prospectCnt?> prospects</li>
		              <li>
		                <ul class="menu">
		                  <li>
		                    <a href="#">
		                      <h3>
		                        <?php print $opportunity?> opportunities
		                        <small class="pull-right">30%</small>
		                      </h3>
		                      <div class="progress xs">
		                        <div class="progress-bar progress-bar-aqua" style="width: 30%" role="progressbar"
		                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          <span class="sr-only">30% Complete</span>
		                        </div>
		                      </div>
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <h3>
		                        <?php print $proposal?> proposals
		                        <small class="pull-right">50%</small>
		                      </h3>
		                      <div class="progress xs">
		                        <div class="progress-bar progress-bar-green" style="width: 50%" role="progressbar"
		                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          <span class="sr-only">50% Complete</span>
		                        </div>
		                      </div>
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <h3>
		                        <?php print $negotiation?> negotiation
		                        <small class="pull-right">70%</small>
		                      </h3>
		                      <div class="progress xs">
		                        <div class="progress-bar progress-bar-red" style="width: 70%" role="progressbar"
		                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          <span class="sr-only">70% Complete</span>
		                        </div>
		                      </div>
		                    </a>
		                  </li>
		                </ul>
		              </li>
		            </ul>
		          </li>
		          <li class="dropdown user user-menu">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              <?php print $img ?>
		              <span class=""><?php print $username?></span>
		            </a>
		            <ul class="dropdown-menu">
		              <li class="user-header">
		                <?php print $img;?>
		                <p>
		                  <?php print $username;?>
		                  <small><?php print $position?></small>
		                </p>
		              </li>
		              <li class="user-body">
		                <div class="row">
		                  <div class="col-xs-4 text-center">
		                    <a href="<?php print base_url()?>index.php/report/report6_controller">DASHBOARD</a>
		                  </div>
		                  <div class="col-xs-4 text-center">
		                    <a href="<?php print base_url()?>index.php/report/report5_controller">CONTROL</a>
		                  </div>
		                  <div class="col-xs-4 text-center">
		                    <a href="<?php print base_url()?>index.php/monitoring/current_controller">PROSPECT</a>
		                  </div>
		                </div>
		              </li>
		              <li class="user-footer">
		                <div class="pull-left">
		                  <a href="<?php print base_url()?>index.php/_system_/auth/edit_user/<?php print $user_id?>/home" class="btn btn-default btn-flat">Profile</a>
		                </div>
		                <div class="pull-right">
		                  <a href="<?php print base_url()?>index.php/_system_/auth/logout" class="btn btn-default btn-flat">Sign out</a>
		                </div>
		              </li>
		            </ul>
		          </li>
		          <li>
		            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
		          </li>
		        </ul>
		      </div>
		    </nav>
		  </header>
			<?php print $menu;?>
			<div class="control-sidebar-bg"></div>
			<div class="content-wrapper">
				<section class="content-header">
					<?php print $breadcrumb?>
				</section>
				<section class="content">

<script>
	var baseURL = "<?php echo base_url()?>";
	function editPict() {
		redirect(base_url+'_setting_/profile_controller/pict');
	}
</script>
