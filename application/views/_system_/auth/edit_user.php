<h1><?php echo lang('edit_user_heading');?></h1>
<p><?php echo lang('edit_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open(uri_string());?>
      <p>
            <?php echo lang('edit_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('edit_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>

      <p>
            <?php echo lang('edit_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('edit_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>

      <p>
            <?php echo lang('edit_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
            <?php echo form_input($password_confirm);?>
      </p>
      <br />

      <?php if ($this->ion_auth->is_admin()): ?>
        <p>
              <?php //echo lang('create_user_email_label', 'email');?> <br />
              <?php //echo form_input($email);?>
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
		
        <h3><?php echo lang('edit_user_groups_heading');?></h3>
          <?php foreach ($groups as $group):
              if($group['name']==$this->config->item('admin_group', 'ion_auth') ||
                 $group['name']==$this->config->item('default_group', 'ion_auth')
              )
              {
                continue;
              }

            ?>
              <label class="checkbox">
              <?php
                  $gID=$group['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentGroups as $grp) {
                      if ($gID == $grp->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
              <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
              </label>
          <?php endforeach?>

      <?php endif ?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

      <p><?php echo form_submit('submit', lang('edit_user_submit_btn'));?></p>

<?php echo form_close();?>
