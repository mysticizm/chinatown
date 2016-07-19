<table>
	<tr>
		<td>
			<?php echo $lang['From']; ?>:
			<input class="date" type="text" id="from" readonly="readonly"/>
		</td>
		<td>
			<select id="statsType">
				<option value="1"><?php echo $lang['Energy']; ?> [kW]</option>
				<option value="2"><?php echo $lang['I']; ?> [A]</option>
				<option value="3"><?php echo $lang['U']; ?> [V]</option>
				<option value="4" selected="selected"><?php echo $lang['Energy per hour']; ?> [kWh]</option>
				<option value="5"><?php echo $lang['Energy per day']; ?> [kWh]</option>
				<option value="6"><?php echo $lang['Energy per month']; ?> [kWh]</option>
				<option value="6"><?php echo $lang['Energy per year']; ?> [kWh]</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $lang['To']; ?>:
			<input class="date" type="text" id="to" readonly="readonly" />
		</td>
		<td>
			<select id="line">
				<option class="grouping" value="a"><?php echo $lang['All devices']; ?></option>
				<?php
				while ($row = $groups->fetch_object())
				{
					echo '<option class="grouping" value="g'.$row->id.'">'.$lang['Group'].' '.$row->name.'</option>';
				}
				while ($row = $devices->fetch_object())
				{
					echo '<option class="grouping" value="d'.$row->id.'">'.$lang['Device'].' '.$row->name.'</option>';
				}
				while ($row = $lines->fetch_object())
				{
					echo '<option class="line" value="l'.$row->id.'">'.$row->name.' '.$lang['Line'].' '.$row->line_number.'</option>';
				}
				?>
			</select>
		</td>
		<td align="center">
			<a id="showStats" class="button" onclick="getGraph()"><?php echo $lang['Show']; ?></a>
			<img src="images/loading.gif" id="Loading" style="display: none;" />
		</td>
	</tr>
</table>
<div id="Graph" style="width:900px;height:400px;"></div> 
<script type="text/javascript">
jQuery("#statsType").change(function()
{
	if (jQuery(this).val() > '3') 
	{
		jQuery("option.grouping").show();
	}
	else
	{
		if(jQuery("option.grouping:selected").length)
		{
			jQuery("option.grouping:selected").removeAttr("selected");
			jQuery("option.line").first().attr("selected", "selected");
		}
		jQuery("option.grouping").hide();
	}
});

function getGraph()
{
	jQuery("#showStats").hide();
	jQuery("#Loading").show();
	jQuery.getJSON("index.php",
	{
		content: "reports",
		action:  "getReport",
		statsType: jQuery("#statsType").val(),
		from: jQuery("#from").val(),
		to: jQuery("#to").val(),
		line: jQuery("#line").val()
	}, function(d)
	{
		var metric = '';
		var datasheet = getDataSheet(d);
		var options = { 
			xaxis: { 
				mode: "time" 
			}
		};
		switch(jQuery("#statsType").val())
		{
			case '1':
				metric = 'kW';
				break;
			case '2':
				metric = 'A';
				break;
			case '3':
				metric = 'V';
				break;
			case '4':
			case '5':
			case '6':
			case '7':
				datasheet[0].lines = { show: true, fill: true };
				metric = 'kWh';
				break;
		}
		jQuery.plot(jQuery("#Graph"), datasheet, options);
		jQuery("#Graph .metric").detach();
		var metricDiv = document.createElement("div");
		jQuery(metricDiv).addClass("metric");
		jQuery(metricDiv).html(metric);
		jQuery(metricDiv).appendTo("#Graph");
		
		jQuery("#showStats").show();
		jQuery("#Loading").hide();
	});
}

function getDataSheet(d)
{
	var datasheet = [
	{
		color: "#FF4444", 
		data: d
	}];
	
	return datasheet;
}
</script>