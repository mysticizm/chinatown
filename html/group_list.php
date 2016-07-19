<table>
	<tr class="ui-widget-header">
		<th>N</th>
		<th>Име</th>
		<th>Промяна</th>
	</tr>
	<?php 
	$i = 0;	
	while ($group = $groups->fetch_object())
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
		<tr class="<?php echo $class.' group'.$group->id; ?>">
			<td><?php echo $group->id; ?></td>
			<td class="name"><?php echo $group->name; ?></td>
			<td>
				<a class="edit" onclick="loadGroup(<?php echo $group->id; ?>);">Промени името</a>
				<a class="delete" onclick="deleteGroup(<?php echo $group->id; ?>);">Изтрий групата</a>
			</td>
		</tr>
		<?php
	}
	?>
</table>
<script type="text/javascript">
prepareUI();
</script>