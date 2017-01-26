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
	<tr class="expand">
		<td colspan="2">
			<a class="button expandScenes">Go to predefined scene</a>
		</td>
	</tr>
	<tr class="ui-widget-header scenes" style="display: none;">
		<th colspan="2">Predefined scenes</th>
	</tr>
	<tr class="line scenes" style="display: none;">
		<td colspan="2" class="radioGroup" style="padding: 10px 0; line-height: 70px;">
			<input type="radio" id="s0" name="scene" value="0" />
			<label for="s0" style="color: white;">0. White</label>
			<input type="radio" id="s1" name="scene" value="1" />
			<label for="s1" style="color: red;">1. Red</label>
			<input type="radio" id="s2" name="scene" value="2" />
			<label for="s2" style="color: blue;">2. Blue</label>
			<input type="radio" id="s3" name="scene" value="3" />
			<label for="s3" style="color: green;">3. Green</label>
			<input type="radio" id="s4" name="scene" value="4" />
			<label for="s4" style="color: purple;">4. Purple</label><br/>
			<input type="radio" id="s5" name="scene" value="5" />
			<label for="s5" style="color: Yellow;">5. Yellow</label>
			<input type="radio" id="s6" name="scene" value="6" />
			<label for="s6" style="color: rose;">6. Rose</label>
			<input type="radio" id="s7" name="scene" value="7" />
			<label for="s7" style="color: SkyBlue;">7. Sky blue</label>
			<input type="radio" id="s8" name="scene" value="8" />
			<label for="s8" style="color: Aquamarine;">8. Light marine</label><br/>
			<input type="radio" id="s9" name="scene" value="9" />
			<label for="s9" style="color: Turquoise;">9. Turquoise</label>
			<input type="radio" id="s10" name="scene" value="10" />
			<label for="s10" style="color: LightGreen;">10. Light green</label>
			<input type="radio" id="s11" name="scene" value="11" />
			<label for="s11" style="color: AliceBlue;">11. Ice Blue</label><br/>
			<input type="radio" id="s12" name="scene" value="12" />
			<label for="s12" style="color: GreenYellow;">12. Green Yellow </label>
			<input type="radio" id="s13" name="scene" value="13" />
			<label for="s13" style="color: #0D98BA;">13. Blue green</label>
			<input type="radio" id="s14" name="scene" value="14" />
			<label for="s14" style="color: #f9f7d1;">14. Moon white</label><br/>
			<input type="radio" id="s15" name="scene" value="15" />
			<label for="s15" style="color: orange;">15. Orange</label>
			<input type="radio" id="s16" name="scene" value="16" />
			<label for="s16" style="color: mediumblue;">16. Blue 2</label>
			<input type="radio" id="s17" name="scene" value="17" />
			<label for="s17" style="color: #9FA91F;">17. Citro yellow</label>
			<input type="radio" id="s18" name="scene" value="18" />
			<label for="s18" style="color: #89664A;">18. Ohra</label><br/>
			<input type="radio" id="s19" name="scene" value="19" />
			<label for="s19" style="color: DarkRed;">19. Red 3</label>
			<a class="button setScene">Go to scene</a>
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

jQuery(".expandScenes").click(function()
{
	jQuery(this).parents("tr").hide();
	jQuery("tr.scenes").show();
});

jQuery(".setScene").click(function()
	{
		jQuery.get("index.php",
		{
			content: "deviceAdmin",
			action: "setScene",
			group: jQuery('select[name="group"]').val(),
			scene: jQuery('input[name="scene"]:checked').val()
		});
	});
</script>