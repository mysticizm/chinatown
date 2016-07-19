<table>
<tr>
	<th colspan="2" style="text-align: center;"><?php echo $device->name; ?></th>
</tr>
<tr>
	<th><?php echo $lang['Address'] ?></th>
	<td><?php echo $device->address; ?></td>
</tr>
<tr>
	<th>LED driver</th>
	<td><?php echo '<b>'.($device->onoff ? 'ON' : 'OFF').'</b>'; ?></td>
</tr>
<tr>
	<th>Power on level</th>
	<td><?php echo '<b>'.$device->power_on_level.'</b> ('.round($device->power_on_level / 2.54, 1).'%)'; ?></td>
</tr>
<tr>
	<th>MIN level</th>
	<td><?php echo '<b>'.$device->min_level.'</b> ('.round($device->min_level / 2.54, 1).'%)'; ?></td>
</tr>
<tr>
	<th>MAX level</th>
	<td><?php echo '<b>'.$device->max_level.'</b> ('.round($device->max_level / 2.54, 1).'%)'; ?></td>
</tr>
<tr>
	<th>Fade time</th>
	<td><?php echo str_replace('.5', ':30', $device->fade_time / 2).' min'; ?></td>
</tr>
<tr>
	<td>Auto dimming</td>
	<th><?php echo $device->options & 0x80 ? 'Enabled' : 'Disabled'; ?></th>
</tr>
<tr>
	<td>Auto color change</td>
	<th><?php echo $device->options & 0x40 ? 'Enabled' : 'Disabled'; ?></th>
</tr>
<tr>
	<td>Fading in auto color change</td>
	<th><?php echo $device->options & 0x20 ? 'Enabled' : 'Disabled'; ?></th>
</tr>
<tr>
	<th>Active Groups</th>
	<td>
	<?php
	for ($i = 1; $i <= 32; $i++)
	{
		$value = (int)(1 << ($i - 1));
		echo ($device->groups & $value ? $i.' ': '');
	}
	?>
	</td>
</tr>
<tr>
	<th>Worktime</th>
	<td><?php echo floor($device->worktime / 8).' hr '.str_replace('.5', ':30', ($device->worktime % 8) * 7.5).' min'; ?></td>
</tr>
</table>
<a class="button" style="border-radius:3px" onclick="loadDevice(<?php echo $device->id; ?>);">Edit settings</a>
<a class="button" style="border-radius:3px" onclick="loadLevels(<?php echo $device->id; ?>);">Levels</a>
