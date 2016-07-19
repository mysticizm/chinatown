<?php
if ($message != "")
{ 
	echo '<p class="ui-state-highlight">'.$message.'</p>'; 
}
?>
<p><a class="button" href="<?php echo PROJECT_DIRECTORY; ?>index.php?content=backup&action=dump"><?php echo 'New backup'; ?></a></p>
<table class="full">
<tr class="ui-widget-header">
	<th>-</th>
	<th><?php echo 'Backup filename'; ?></th>
	<th><?php echo 'Size'; ?></th>
</tr>
	<?php
	foreach (array_reverse($files) as $key=>$file)
	{
		if (!is_dir(DUMP_DIR.$file))
		{
			if ($key % 2)
			{
				$class = 'even';
			}
			else
			{
				$class = 'odd';
			}
			$dimension = ' M';
			$size = round(filesize(DUMP_DIR.$file) / (1024 * 1024), 2);
			if ($size == 0)
			{
				$dimension = ' K';
				$size = round(filesize(DUMP_DIR.$file) / 1024, 2);
			}
			$size .= $dimension;
			?>
			<tr class="backup<?php echo $key.' '.$class; ?>">
				<td class="tools" style="width: 300px;">
					<a class="button" href="<?php echo PROJECT_DIRECTORY; ?>index.php?content=backup&action=restore&file=<?php echo $file; ?>"><?php echo 'Restore'; ?></a>
					<a class="delete" onclick="deleteBackup(<?php echo "'$file', $key"; ?>)"><?php echo 'Delete'; ?></a>
				</td>
				<td class="id" style="width: auto;"><a href="<?php echo DUMP_URL.$file; ?>"><?php echo $file; ?></a></td>
				<td><?php echo $size; ?></td>
			</tr>
			<?php
		}
	}
	?>
</table>
<script type="text/javascript">
function deleteBackup(filename, key)
{
	if (confirm('Do you really want to delete '+filename+'?'))
	{
		jQuery(".backup"+key).hide();
		jQuery.post("<?php echo PROJECT_DIRECTORY; ?>index.php",
		{
			content: "backup",
			action: "delete",
			file: filename
		});
	}
}
</script>