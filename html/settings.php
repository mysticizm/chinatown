<tr class="line">
	<th>LED driver</th>
	<td class="radioGroup">
		<input type="radio" id="onoff1" name="onoff" value="1" <?php echo $device->onoff ? 'checked="checked"' : ''; ?> />
		<label for="onoff1">ON</label>
		
		<input type="radio" id="onoff0" name="onoff" value="0" <?php echo $device->onoff ? '' : 'checked="checked"'; ?> />
		<label for="onoff0">OFF</label>
	</td>
</tr>
<tr class="line">
	<th class="red">MIN level</th>
	<td>
		<a class="button small decr">-</a>
		<input type="text" class="int number required" name="min_level" value="<?php echo $device->min_level; ?>" maxlength="3" onchange="updatePercent(this);" />
		<a class="button small incr">+</a>
		<span class="perc"><?php echo round($device->min_level / 2.54, 1).'%'; ?></span>
	</td>
</tr>
<tr class="line">
	<th>Power on level</th>
	<td>
		<a class="button small decr">-</a>
		<input type="text" class="int number required" name="power_on_level" value="<?php echo $device->power_on_level; ?>" maxlength="3" onchange="updatePercent(this);" />
		<a class="button small incr">+</a>
		<span class="perc"><?php echo round($device->power_on_level / 2.54, 1).'%'; ?></span>
	</td>
</tr>
<tr class="line">
	<th class="red">MAX level</th>
	<td>
		<a class="button small decr">-</a>
		<input type="text" class="int number required" name="max_level" value="<?php echo $device->max_level; ?>" maxlength="3" onchange="updatePercent(this);" />
		<a class="button small incr">+</a>
		<span class="perc"><?php echo round($device->max_level / 2.54, 1).'%'; ?></span>
	</td>
</tr>
<tr class="line">
	<td colspan="2" style="text-align: center;">2 &le; <span class="red">MIN level</span> &le; <b>Power on level</b> &le; <span class="red">MAX level</span> &le; 254</td>
</tr>
<tr class="line" style="display: none;">
	<th>Fade rate</th>
	<td>
		<input type="text" class="int number" name="fade_rate" value="<?php echo $device->fade_rate; ?>" maxlength="3" />
		No idea what is it for
	</td>
</tr>
<tr class="line">
	<th>Fade time</th>
	<td>
		<input type="text" class="int number required byte" name="fade_time" value="<?php echo $device->fade_time; ?>" maxlength="3" />
		x 30 sec
	</td>
</tr>
<tr class="line">
	<th>Auto dimming</th>
	<td class="radioGroup">
		<input type="radio" id="ad1" name="options[1]" value="<?php echo 0x80; ?>" <?php echo $device->options & 0x80 ? 'checked="checked"' : ''; ?> />
		<label for="ad1">Enable</label>
		
		<input type="radio" id="ad0" name="options[1]" value="0" <?php echo $device->options & 0x80 ? '' : 'checked="checked"'; ?> />
		<label for="ad0">Disable</label>
	</td>
</tr>
<tr class="line">
	<th>Auto color change</th>
	<td class="radioGroup">
		<input type="radio" id="ac1" name="options[2]" value="<?php echo 0x40; ?>" <?php echo $device->options & 0x40 ? 'checked="checked"' : ''; ?> />
		<label for="ac1">Enable</label>
		
		<input type="radio" id="ac0" name="options[2]" value="0" <?php echo $device->options & 0x40 ? '' : 'checked="checked"'; ?> />
		<label for="ac0">Disable</label>
	</td>
</tr>
<tr class="line">
	<th>Fading in auto color change</th>
	<td class="radioGroup">
		<input type="radio" id="fa1" name="options[3]" value="<?php echo 0x20; ?>" <?php echo $device->options & 0x20 ? 'checked="checked"' : ''; ?> />
		<label for="fa1">Enable</label>
		
		<input type="radio" id="fa0" name="options[3]" value="0" <?php echo $device->options & 0x20 ? '' : 'checked="checked"'; ?> />
		<label for="fa0">Disable</label>
	</td>
</tr>
<tr class="ui-widget-header">
	<th class="groups" colspan="2">Groups</th>
</tr>
<tr class="line">
	<td class="groups" colspan="2">
	<?php
	for ($i = 1; $i <= 32; $i++)
	{
		$value = (int)(1 << ($i - 1));
		echo '<input '.($device->groups & $value ? 'checked="checked"': '').' type="checkbox" id="g'.$i.'" name="groups['.$i.']" value="'.$value.'" />';
		echo '<label for="g'.$i.'">'.$i.'</label>';
		if ($i % 8 == 0)
		{
			echo '<br/>';
		}
	}
	?>
	</td>
</tr>