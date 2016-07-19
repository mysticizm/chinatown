<div style="clear: both;">
<form id="login_form" name="login_form" method="post" action="index.php">
	<input type="hidden" name="content" value="users" />
	<input type="hidden" name="action" value="checkUser" />
	<?php
	if ($error != '')
	{
		echo '<div class="ui-state-highlight" style="width: 300px; margin: auto; text-align: center;"><span class="ui-icon ui-icon-alert" style="display: inline-block; vertical-align: middle;"></span>'.$error.'</div>';
	}
	?>
	<table>
		<tr>
			<td style="width: 100px;"><label for="user_name"><?php echo $lang['Username']; ?></label></td>
			<td style="width: calc(100% - 100px);"><input type="text" name="user_name" id="user_name" /></td>
		</tr>
		<tr>
			<td><label for="user_pass"><?php echo $lang['Password']; ?></label></td>
			<td><input type="password" name="user_pass" id="user_pass" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input class="button log" type="submit" value="<?php echo $lang['Log In']; ?>" /></td>
		</tr>
	</table>
</form>
</div>