<h1>Settings </h1>
<div id="infoMessage"><?php echo $message;?></div>

<div class="block">
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs pull-right">
			<li class=""><a href="#tab_1-1" data-toggle="tab">Groups</a></li>
			<li class="active"><a href="#tab_2-2" data-toggle="tab">Users</a></li>
			<li class="pull-left header"><i class="fa fa-th"></i>Manage User Access</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane" id="tab_1-1">
				<h1><?php echo 'Groups';?></h1>
				<?php echo anchor('_system_/auth/create_group', lang('index_create_group_link'))?>
				<table class='table compact table-bordered table-striped dataTable no-footer dtr-inline' cellpadding=0 cellspacing=10>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Action</th>
					</tr>
					<?php foreach ($groups as $group):?>
					<tr>
						<td>
							<?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8'); ?>
						</td>
						<td>
							<?php echo htmlspecialchars($group->description,ENT_QUOTES,'UTF-8'); ?>
						</td>
						<td>
							<?php echo anchor("_system_/auth/edit_group/".$group->id, 'Edit'); ?>
						</td>
					</tr>
				<?php endforeach?>
				</table>
			</div>
			<div class="tab-pane active" id="tab_2-2">
				<h1><?php echo lang('index_heading');?></h1>
				<p><?php echo anchor('_system_/auth/create_user', lang('index_create_user_link'))?></p>
				<table id=p1 class='table compact table-striped dataTable no-footer dtr-inline' >
					<thead>
					<tr>
						<th><?php echo lang('index_email_th');?></th>
						<th><?php echo 'Name';?></th>
						<th><?php echo lang('index_emp_position_th');?></th>
						<th><?php echo lang('index_emp_leader_th');?></th>
						<th><?php echo lang('index_groups_th');?></th>
						<th><?php echo 'RO';?></th>
						<th><?php echo lang('index_status_th');?></th>
						<th><?php echo lang('index_action_th');?></th>
					</tr>
				 </thead>
				 <tbody>
					<?php foreach ($users as $user):?>
						<tr>
							<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
							<td><?php echo htmlspecialchars($user->first_name .' '. $user->last_name,ENT_QUOTES,'UTF-8');?></td>
							<td><?php echo htmlspecialchars($user->emp_position,ENT_QUOTES,'UTF-8');?></td>
							<td><?php echo htmlspecialchars($user->emp_leader,ENT_QUOTES,'UTF-8');?></td>
							<td>
								<?php foreach ($user->groups as $group):?>
									<?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8');?><br />
								<?php endforeach?>
							</td>
							<td><?php echo htmlspecialchars($user->r_ro_id,ENT_QUOTES,'UTF-8');?></td>							
							<td><?php echo ($user->active) ? anchor("_system_/auth/deactivate/".$user->id, lang('index_active_link')) : anchor("_system_/auth/activate/". $user->id, lang('index_inactive_link'));?></td>
							<td><?php echo anchor("_system_/auth/edit_user/".$user->id, 'Edit') ;?></td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
		var table = $('#p1').dataTable( {
			serverSide  : false,
			responsive  : true,
			dom         : 'B<"table-toolbar col-sm-6">frtip',
			order : [
								[1,'asc']
							],
			pageLength : 50,
			buttons     : [
	                    {extend: 'collection',
	                     text: 'Export Data',
	                     buttons: [ {extend: 'copy', filename:'Export_'+cDate, action: doCOPY, exportOptions: {columns: ':visible'}},
	                                {extend: 'csv',  filename:'Export_'+cDate, action: doCSV, exportOptions: {columns: ':visible'}},
	                                {extend: 'excel',filename:'Export_'+cDate, action: doXLS, exportOptions: {columns: ':visible'}},
	                                {extend: 'pdf',  filename:'Export_'+cDate, action: doPDF, exportOptions: {columns: ':visible'}},
	                              ]
	                    },
	                    {extend: 'collection',
	                     text: 'Column Settings',
	                     buttons: [ {extend: 'colvisGroup',text: 'Show All',show: ':hidden'},
	                                {extend: 'colvis', text : 'Show Selected', postfixButtons: [ 'colvisRestore' ]},
	                                {extend: 'pageLength', text : 'Set Rows Per Page'},
	                              ]
	                    },
										],
		});
})
</script>
