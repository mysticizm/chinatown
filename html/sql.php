<form action="index.php" method="post" id="editForm">
<input type="hidden" name="content" value="phpmyadmin"/>
<textarea name="query" style="width: 100%; height: 100px;">
<?php echo $query; ?>
</textarea>
<input type="submit" class="submit" value="Изпълни" />
</form>
<br />
<?php
if (isset($result) && is_object($result) && $result->num_rows)
{
	echo '<table>';
	echo '<tr class="ui-widget-header">';
	foreach ($result->fetch_fields() as $key=>$field)
	{
		echo '<th>'.$field->name.'</th>';
	}
	echo '</tr>';
	$i = 0;
	while ($row = $result->fetch_assoc())
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
		echo '<tr class="'.$class.'">';
		foreach ($row as $key=>$value)
		{
			echo '<td>'.$value.'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}
if ($query != '')
{
	echo '<div class="ui-state-highlight">';
	if ($SQL->errno)
	{
		echo 'ERROR '.$SQL->errno.' - '.$SQL->error;
	}
	else
	{
		echo $SQL->affected_rows.' записа <br />';
	}
	echo '</div>';
}
?>