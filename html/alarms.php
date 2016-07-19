<div class="alarmContainer">
<?php
if (isset($devices))
{
	?>
	Device: 
	<select id="device">
		<option value="0">All devices</option>
		<?php 
		while ($row = $devices->fetch_object())
		{
			echo '<option value="'.$row->id.'" '.($device == $row->id ? 'selected="selected"' : '').'>'.$row->name.' - '.$row->address.'</option>';
		}
		?>
	</select>
	<?php
}
if ($alarms->num_rows)
{
	?>
	<table class="full">
	<tr class="ui-widget-header">
		<th>!</th>
		<th><?php echo $lang['Alarm']; ?></th>
		<th><?php echo $lang['Device']; ?></th>
		<th><?php echo $lang['Avtivated on']; ?></th>
		<?php
		if (isset($close_time))
		{
			?>
			<th><?php echo $lang['Deactivated on']; ?></th>
			<?php
		}
		?>
	</tr>
	<?php
	$i = 0;
	while ($alarm = $alarms->fetch_object())
	{
		if ($alarm->priority > 150)
		{
			$class = 'ui-state-error';
		}
		else
		{
			$class = 'ui-state-highlight';
		}
		$i++;
		$tr_class = 'alarm ';
		if ($i % 2)
		{
			$tr_class .= 'odd';
		}
		else
		{
			$tr_class .= 'even';
		}
		?>
		<tr class="<?php echo $tr_class; ?>">
			<td class="<?php echo $class; ?>"><span class="ui-icon ui-icon-alert"></span></td>
			<td class="<?php echo $class; ?>"><?php echo $lang['Code '.$alarm->code]; ?></td>
			<td>
				<?php 
				echo $alarm->device;
				if (strlen($alarm->line_number))
				{
					echo ' <b>л'.$alarm->line_number.'</b>';
				}
				echo '<span class="device_id" style="display: none;">'.$alarm->device_id.'</span>'; 
				?>
			</td>
			<td><?php echo $alarm->open_time; ?></td>
			<?php
			if (isset($close_time))
			{
				?>
				<td><?php echo $alarm->close_time; ?></td>
				<?php
			}
			?>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}
else
{
	echo $lang['none'];
}

if ((isset($pages)) && ($pages > 1))
{
	$this->pages($p, $pages);
}
?>
<script type="text/javascript">
jQuery(".deleteMe").remove(); 
jQuery(".alarmContainer input.page").button();
jQuery("input.page").change(function()
{
	jQuery.get("index.php",
	{
		content: "alarms",
		action: "showAll",
		page: jQuery("input.page:checked").val(),
		device: jQuery("#device").val()
	},
	function(data)
	{
		var oldTable = jQuery(".alarmContainer");
		jQuery(oldTable).hide();
		jQuery(oldTable).removeClass("alarmContainer");
		jQuery(oldTable).addClass("deleteMe");
		jQuery(oldTable).after(data);
	});
});
jQuery("#device").change(function()
{
	jQuery.get("index.php",
	{
		content: "alarms",
		action: "showAll",
		page: 1,
		device: jQuery("#device").val()
	},
	function(data)
	{
		var oldTable = jQuery(".alarmContainer");
		jQuery(oldTable).hide();
		jQuery(oldTable).removeClass("alarmContainer");
		jQuery(oldTable).addClass("deleteMe");
		jQuery(oldTable).after(data);
	});
});
</script>
</div>
