<?php
/*
<table>
	<tr class="ui-widget-header">
		<th>N</th>
		<th><?php echo $lang['Name']; ?></th>
		<th><?php echo $lang['Indicators']; ?></th>
		<th><?php echo $lang['Tools']; ?></th>
	</tr>
	<?php 
	$i = 0;
	foreach ($devices as $key => $device)
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
		<tr class="<?php echo $class.' device'.$device->id; ?>">
			<td><?php echo $device->id; ?></td>
			<td>
			<?php 
			echo $device->name; 
			?>
			</td>
			<td>
				<ul class="ui-widget notifier">
				<?php
				if (!$device->active)
				{
					echo '<li class="ui-state-error" title="'.$lang['Lost connection'].'">';
					echo '<span class="ui-icon ui-icon-closethick"></span>';
					echo '</li>';
				}
				else
				{
					
				}
				?>
				</ul>
			</td>
			<td>
				<a class="power" onclick="loadDevice(<?php echo $device->id; ?>);"><?php echo $lang['Settings']; ?></a>
				
				<a class="settings" onclick="loadLineStats(<?php echo $device->id; ?>);"><?php echo $lang['Current data']; ?></a>
				<a class="locate" onclick="locateDevice(<?php echo $device->id; ?>);"><?php echo $lang['Show on map']; ?></a>
				<?php
				if ($_SESSION['access'] >= 800)
				{
					?>
					<a class="delete" onclick="deleteDevice(<?php echo $device->id; ?>);"><?php echo $lang['Delete device']; ?></a>
					<?php
				}
				?>
			</td>
		</tr>
		<?php
	}
	?>
</table>
*/
?>
<script type="text/javascript">
<?php
if ($alarms->num_rows)
{
	$alarm = $alarms->fetch_object();
	?>
	jQuery(".stateIndicator").html('<span style="padding: 0 10px;" class="ui-state-error ui-corner-all"><?php echo $alarm->name; ?></span>');
	<?php
}
else
{
	?>
	jQuery(".stateIndicator").html('<span style="padding: 0 10px;" class="indicator"><?php echo $lang['Communication is OK']; ?></span>');
	<?php
}

$offset = GMT_OFFSET / (60 * 60);
$clock = date('m-d-Y H:i').'<br/>';
$clock .= 'Sunrise: '.date('H:i', date_sunrise ( time(), SUNFUNCS_RET_TIMESTAMP, 49.279742, -123.103923, 96, $offset)).'<br/>'; 
$clock .= 'Sunset: '.date('H:i', date_sunset ( time(), SUNFUNCS_RET_TIMESTAMP, 49.279742, -123.103923, 264, $offset)); 
?>
jQuery(".clockIndicator").html('<?php echo $clock; ?>');

prepareUI();
for (var i in deviceMarkers)
{
	deviceMarkers[i].setIcon("images/lamp_small.png");
	deviceMarkers[i].setVisible(true);
}

if (deviceMarkers.length)
{
	<?php
	foreach ($devices as $key => $device)
	{
		$icon = '';
		if ($device->active)
		{
			if ($device->power_on_level == '5')
			{
				$icon .= '_on';
			}
			if ($device->power_on_level == '9')
			{
				$icon .= '_50';
			}
		}
		else
		{
			$icon .= '_lost';
		}
		echo 'deviceMarkers['.$device->id.'].setIcon("images/lamp'.$icon.'.png");';
		$icon = str_replace('_open', '', $icon);
	}
	?>
}

var t1 = setTimeout("reloadDevices()", 15000);
</script>