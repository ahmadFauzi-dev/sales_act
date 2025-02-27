<h1><?php echo lang('create_user_heading');?></h1>
<p><?php echo lang('create_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("_system_/auth/create_user");?>
      <p>
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>

      <?php
      if($identity_column!=='email') {
          echo '<p>';
          echo lang('create_user_identity_label', 'identity');
          echo '<br />';
          echo form_error('identity');
          echo form_input($identity);
          echo '</p>';
      }
      ?>

      <p>
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>

      <p>
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p>
            <?php echo lang('edit_user_emp_position_label', 'emp_position');?> <br />
            <?php echo form_dropdown($emp_position,$emp_position['options'],$emp_position['value']);?>

      </p>
      <br />
      <p>
            <?php echo lang('edit_user_emp_leader_label', 'emp_leader');?> <br />
            <?php echo form_dropdown($emp_leader,$emp_leader['options'],$emp_leader['value']);?>

      </p>
	  <br />
      <p>
            <?php echo lang('edit_user_ro_name_label', 'r_ro_id');?> <br />
            <?php echo form_dropdown($r_ro_id,$r_ro_id['options'],$r_ro_id['value']);?>

      </p>
      <br />
      <p><?php echo form_submit('submit', lang('create_user_submit_btn'));?></p>

<?php echo form_close();?>
