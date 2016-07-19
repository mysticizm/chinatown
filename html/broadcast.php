<form action="index.php" method="post" id="form">
<input type="hidden" name="content" value="DeviceAdmin" />
<input type="hidden" name="action" value="sendBroadcast" />
<div class="ui-state-highlight message_info">
	<div style="float: left; padding: 14px;"><span class="ui-icon ui-icon-info"></span></div>
	<?php echo $lang['Broadcast warning']; ?>
</div>
<table>
	<?php $this->showView('settings'); ?>
	<tr class="line">
		<th>Power level</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="level" maxlength="3" onchange="updatePercent(this);" />
			<a class="button small incr">+</a>
			<span class="perc"></span>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #FF0000;">Red</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="red" maxlength="3" />
			<a class="button small incr">+</a>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #00FF00;">Green</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="green" maxlength="3" />
			<a class="button small incr">+</a>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #0000FF;">Blue</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="blue" maxlength="3" />
			<a class="button small incr">+</a>
		</td>
	</tr>
</table>
<div class="ui-widget-header broad" style="clear: both; padding: 5px 0;">
	<a class="back"><?php echo $lang['Cancel']; ?></a>
	<a class="submit"><?php echo $lang['Send']; ?></a>
</div>
</form>
<script type="text/javascript">
jQuery(".left input:checked").removeAttr("checked");
jQuery('.left input[type="text"]').val("");
jQuery(".groups").parent().remove();
jQuery(".left input.required").removeClass("required");
prepareUI();
smallMap();
jQuery(".back").click(function()
{
	reloadDevices();
	fullMap();
});

jQuery(".incr").click(function()
{
	var input = jQuery(this).parent().find("input");
	var v = new Number(jQuery(input).val());
	if (v < 255)
	{
		jQuery(input).val(v + 1);
		updatePercent(input);
	}
});

jQuery(".decr").click(function()
{
	var input = jQuery(this).parent().find("input");
	var v = new Number(jQuery(input).val());
	if (v > 0)
	{
		jQuery(input).val(v - 1);
		updatePercent(input);
	}
});

jQuery(".submit").click(function()
{
	if (validate('.left'))
	{
		jQuery.post("index.php", jQuery("#form").serialize()+"&group="+jQuery('select[name="group"]').val(),
		function(data)
		{
			jQuery(".left").html(data);
			fullMap();
		});
	}
});
</script>