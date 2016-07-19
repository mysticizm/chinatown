<form action="index.php" method="post" id="form">
<input type="hidden" name="content" value="Timings" />
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $system->id; ?>"/>
<table class="full">
	<tr class="ui-widget-header">
		<th class="header_timing"><?php echo $lang['Param']; ?></th>
		<th class="header_timing"><?php echo $lang['Value']; ?></th>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Replay timeout']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="reply_timeout" value="<?php echo $system->reply_timeout; ?>" />
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Repeat after']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="retry_after" value="<?php echo $system->retry_after; ?>"/>
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Data interval']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="stats_interval" value="<?php echo $system->stats_interval; ?>"/>
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Replay after']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="after_reply" value="<?php echo $system->after_reply; ?>"/>
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Min between time']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="send_interval" value="<?php echo $system->send_interval; ?>"/>
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['USB check timeout']; ?>:</th>
		<td>
			<input type="text" class="number int required" name="unplugged_timeout" value="<?php echo $system->unplugged_timeout; ?>" maxlength="3"/>
			<?php echo $lang['seconds']; ?> 
		</td>
	</tr>
</table>
<div class="ui-widget-header " style="clear: both; padding: 5px 0;">
	<a class="submit system sys_settings"><?php echo $lang['Save']; ?></a>
	<a class="reload"><?php echo $lang['Restart process']; ?></a>
</div>
</form>
<script type="text/javascript">
prepareUI();
jQuery(".submit").click(function()
{
	if (validate('#form'))
	{
		jQuery("#form").submit();
	}
});
jQuery(".reload").click(function()
{
	if (confirm("<?php echo $lang['Restart q']; ?>"))
	{
		jQuery.post("index.php",
		{
			content: "Timings",
			action: "restartService",
			restart: 1
		});
	}
});
</script>