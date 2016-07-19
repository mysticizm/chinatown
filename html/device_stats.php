<h2><?php echo $lang['Last stats'].': '.$device->name; ?></h2>
<input type="hidden" class="device_id" value="<?php echo $device->id; ?>" />
<table class="full">
	<tr class="ui-widget-header">
		<th><?php echo $lang['Line']; ?></th>
		<th>I</th>
		<th>U</th>
		<?php
		if ($_SESSION['access'] >= 800)
		{
			echo '<th>&phi;</th>';
		}
		?>
		<th>P</th>
		<th><?php echo $lang['Date and time']; ?></th>
	</tr>
	<?php 
	foreach ($stats as $key => $value)
	{
		if ($key % 2)
		{
			$class = 'even';
		}
		else
		{
			$class = 'odd';
		}
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $value->line_number; ?></td>
			<td><?php echo $value->i; ?> A</td>
			<td><?php echo $value->u; ?> V</td>
			<?php
			if ($_SESSION['access'] >= 800)
			{
				echo '<td>'.$value->fi.'</td>';
			}
			?>
			<td><?php echo $value->p; ?> kW</td>
			<td><?php echo $value->stat_time; ?></td>
		</tr>
		<?php
	}
	?>
</table>