<div class="ui-widget-header">
	<a class="add" onclick="newUser()"><?php echo $lang['New user']; ?></a>
</div>
<table>
	<tr class="ui-widget-header">
		<th>N</th>
		<th><?php echo $lang['Name']; ?></th>
		<th><?php echo $lang['Access']; ?></th>
		<th><?php echo $lang['Tools']; ?></th>
	</tr>
	<?php 
	$i = 0;	
	while ($user = $users->fetch_object())
	{
		$i++;
		if ($i % 2)
		{
			$class = 'odd';
		}
		else
		{
			$class = 'even';
		}
		?>
		<tr class="<?php echo $class.' user'.$user->id; ?>">
			<td><?php echo $user->id; ?></td>
			<td><?php echo $user->first_name.' '.$user->last_name; ?></td>
			<td><?php echo $lang[$user->role_name]; ?></td>
			<td>
				<a class="edit" onclick="loadUser(<?php echo $user->id; ?>);"><?php echo $lang['Edit']; ?></a>
				<a class="delete" onclick="deleteUser(<?php echo $user->id; ?>);"><?php echo $lang['Delete']; ?></a>
			</td>
		</tr>
		<?php
	}
	?>
</table>
<script type="text/javascript">
prepareUI();
</script>