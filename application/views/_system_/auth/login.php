<html>
<?php
	print $this->koje_system->load_asset();
	$title_lg = $this->config->item('KOJE_TITLE_LG');
	$logo = img(array('src' => $this->koje_system->getLogoURL(),'class'=>'koje-logo', 'alt'=>'Logo'));
?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo no-margin">
    <label><?php print $title_lg?></label>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
		<?php print $logo?>
    <label class="login-box-msg">Sign in</label>
		<div id="infoMessage"><?php echo $message;?></div>
		<?php echo form_open('_system_/auth/login'); ?>
      <div class="form-group has-feedback">
				<?php print form_input($identity);?>
	      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
				<?php print form_input($password);?>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
			<div class="form-group has-feedback">
				 <?php echo lang('login_remember_label', 'remember');?>
				<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
		    	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
        <div class="col-xs-12">
          <p><?php echo form_submit('submit', lang('login_submit_btn'));?></p>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
	</body>
</html>
