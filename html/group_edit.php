<form action="index.php" method="post" id="form">
<input type="hidden" name="content" value="DeviceGroups" />
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $group->id; ?>"/>
<table>
	<tr class="ui-widget-header">
		<th colspan="2">Редакция на името на група #<?php echo $group->id ?></th>
	</tr>
	<tr class="line">
		<td><input class="textbox" type="text" name="name" value="<?php echo $group->name; ?>"/></td>
	</tr>
</table>
<div class="ui-widget-header" style="clear: both; padding: 5px 0;">
	<a class="submit">Запиши</a>
	<a class="cancel">Отказ</a>
</div>
<script type="text/javascript">
prepareUI();
jQuery(".submit").click(function()
{
	if (validate('.right'))
	{
		jQuery("#form").submit();
	}
});
jQuery(".cancel").click(function()
{
	jQuery(".right").html('');
});
</script>
</form>