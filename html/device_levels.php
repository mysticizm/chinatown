<form action="index.php" method="post" id="form">
<input type="hidden" name="content" value="DeviceAdmin" />
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $device->id; ?>"/>
<?php
if ($device->id && !$device->active)
{
	?>
	<div class="ui-state-highlight">
		<div style="float: left; padding: 20px;"><span class="ui-icon ui-icon-info"></span></div>
		<?php echo $lang['No connection to device']; ?>
	</div>
	<?php
}
?>
<table class="device">
	<tr class="ui-widget-header">
		<th><a class="back"><?php echo $lang['Cancel'] ?></a></th>
		<th>Levels and color</th>
	</tr>
	<tr class="line">
		<th>Power level</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="level" value="<?php echo $device->level; ?>" maxlength="3" onchange="updatePercent(this);changeColor();" />
			<a class="button small incr">+</a>
			<span class="perc"><?php echo round($device->level / 2.54, 1).'%'; ?></span>
			<a class="button setPower">Set</a>
			<img class="loading" src="images/loading.gif" style="display: none;"/>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #FF0000;">Red</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="red" value="<?php echo $device->red; ?>" maxlength="3" onchange="changeColor();" />
			<a class="button small incr">+</a>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #00FF00;">Green</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="green" value="<?php echo $device->green; ?>" maxlength="3" onchange="changeColor();" />
			<a class="button small incr">+</a>
		</td>
	</tr>
	<tr class="line">
		<th style="color: #0000FF;">Blue</th>
		<td>
			<a class="button small decr">-</a>
			<input type="text" class="int number required" name="blue" value="<?php echo $device->blue; ?>" maxlength="3" onchange="changeColor();" />
			<a class="button small incr">+</a>
		</td>
	</tr>
	<tr class="line">
		<td colspan="2">
			<div class="color" value="rgb(100, 86, 70)">Select color</div>
			<a class="button setColor">Set color</a>
			<img class="loading" src="images/loading.gif" style="display: none;"/>
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
	<tr class="ui-widget-header">
		<td>Received data</td>
		<td><?php echo $device->levels_time; ?> </td>
	</tr>
	<tr class="line">
		<th>Level</th>
		<td><?php echo $device->level; ?></td>
	</tr>
	<tr class="line">
		<th>Color</th>
		<td style="background-color: rgb(<?php echo $device->red.', '.$device->green.', '.$device->blue; ?>);">&nbsp;</td>
	</tr>
	<tr class="line">
		<th style="color: red;">Red</th>
		<td><?php echo $device->red; ?></td>
	</tr>
	<tr class="line">
		<th style="color: green;">Green</th>
		<td><?php echo $device->green; ?></td>
	</tr>
	<tr class="line">
		<th style="color: blue;">Blue</th>
		<td><?php echo $device->blue; ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<a class="button reloadLevels" onclick="loadLevels(<?php echo $device->id; ?>);">Reload</a>
		</td>
	</tr>
</table>
</form>
<script type="text/javascript">
smallMap();
prepareUI();

jQuery(".back").click(function()
{
	fullMap();
});

jQuery(".incr").click(function()
{
	var input = jQuery(this).parent().find("input");
	var v = new Number(jQuery(input).val());
	if (v < 255)
	{
		jQuery(input).val(v + 1);
		changeColor();
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
		changeColor();
		updatePercent(input);
	}
});

<?php 
if (!$device->active)
{
	?>
	jQuery(".setColor, .setPower, .setScene").hide();
	<?php
}
else
{
	?>
    var myColorPicker = jQuery('.color').colorPicker({
		opacity: true,
		animationSpeed: 20,
		renderCallback: function($elm, toggled) {
			var colors = this.color.colors,
				rgb = colors.RND.rgb;
			
			jQuery('input[name="red"]').val(rgb.r);
			jQuery('input[name="green"]').val(rgb.g);
			jQuery('input[name="blue"]').val(rgb.b);
			jQuery('input[name="level"]').val(Math.round(colors.alpha * 255));
			
			 $('.cp-disp').css({
				backgroundColor: '#' + colors.HEX,
				color: colors.RGBLuminance > 0.22 ? '#222' : '#ddd'
			});
		},
		buildCallback: function($elm) {
			$elm.prepend('<div class="cp-disp">Selected color</div>');
		},
		cssAddon:
			'.cp-disp {padding:10px; margin-bottom:6px; font-size:19px; height:20px; line-height:20px}' +
			'.cp-xy-slider {width:200px; height:200px;}' +
			'.cp-xy-cursor {width:16px; height:16px; border-width:2px; margin:-8px}' +
			'.cp-z-slider {height:200px; width:40px;}' +
			'.cp-z-cursor {border-width:8px; margin-top:-8px;}' +
			'.cp-alpha {height:40px;}' +
			'.cp-alpha-cursor {border-width:8px; margin-left:-8px;}'
	});
	if (cPicker == null)
	{
		cPicker = myColorPicker;
	}
	
	var colorInstance = cPicker.colorPicker.color;
	
	function changeColor()
	{
		var value = {
			r: jQuery('input[name="red"]').val(),
			g: jQuery('input[name="green"]').val(),
			b: jQuery('input[name="blue"]').val()
		};
		var alpha = Math.round(jQuery('input[name="level"]').val() * 100 / 254) / 100;
		var rgba = 'rgba(' + value.r + ', ' + value.g + ', ' + value.b + ', ' + alpha + ')';
		colorInstance.colors.RND.rgb = value;
		colorInstance.colors.alpha = alpha
		colorInstance.setColor(value, 'rgb', alpha);
		
		jQuery(".color"). 
			attr("value", rgba).
			css('background-color', 'rgba(' + value.r + ',' + value.g + ',' + value.b + ',' + alpha + ')').
			css('color', colorInstance.colors.rgbaMixBGMixCustom.luminance > 0.22 ? '#222' : '#ddd');
	}

	changeColor();
	
	jQuery(".setColor").click(function()
	{
		jQuery(".setColor, .setPower").hide();
		jQuery(".loading").show();
		jQuery.get("index.php",
		{
			content: "deviceAdmin",
			action: "setColor",
			id: jQuery('input[name="id"]').val(),
			red: jQuery('input[name="red"]').val(),
			green: jQuery('input[name="green"]').val(),
			blue: jQuery('input[name="blue"]').val(),
			level: jQuery('input[name="level"]').val()
		}, function()
		{
			jQuery(".setColor, .setPower").show();
			jQuery(".loading").hide();
		});
	});
	
	jQuery(".setPower").click(function()
	{
		jQuery(".setColor, .setPower").hide();
		jQuery(".loading").show();
		jQuery.get("index.php",
		{
			content: "deviceAdmin",
			action: "setPower",
			id: jQuery('input[name="id"]').val(),
			level: jQuery('input[name="level"]').val()
		}, function()
		{
			jQuery(".setColor, .setPower").show();
			jQuery(".loading").hide();
		});
	});
	
	jQuery(".setScene").click(function()
	{
		jQuery.get("index.php",
		{
			content: "deviceAdmin",
			action: "setScene",
			id: jQuery('input[name="id"]').val(),
			scene: jQuery('input[name="scene"]:checked').val()
		});
	});
	<?php
}
?>
jQuery(".expandScenes").click(function()
{
	jQuery(this).parents("tr").hide();
	jQuery("tr.scenes").show();
});
</script>