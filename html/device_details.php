<form action="index.php" method="post" id="form">
<input type="hidden" name="content" value="DeviceAdmin" />
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $device->id; ?>"/>
<input type="hidden" name="lat" value="<?php echo $device->lat; ?>"/>
<input type="hidden" name="lng" value="<?php echo $device->lng; ?>"/>
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
<table>
	<tr class="ui-widget-header">
		<th><a class="back"><?php echo $lang['Cancel'] ?></a></th>
		<th><?php echo $lang['Device info'] ?></th>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Name'] ?></th>
		<td><input class="textbox" type="text" name="name" value="<?php echo $device->name; ?>"/></td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Address'] ?> (heximal)</th>
		<td>
			<input 
				class="textbox address required" 
				type="text" 
				name="address" 
				maxlength="4" 
				value="<?php echo $device->address; ?>" 
				<?php
				if ($_SESSION['access'] < 800)
				{
					echo 'readonly="readonly"';
				}
				?>
			/>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Address'] ?> (decimal)</th>
		<td>
			<input 
				class="textbox int" 
				type="text" 
				name="addressdec" 
				maxlength="4" 
				value="<?php echo hexdec($device->address); ?>" 
				<?php
				if ($_SESSION['access'] < 800)
				{
					echo 'readonly="readonly"';
				}
				?>
			/>
		</td>
	</tr>
	<tr class="line">
		<th><?php echo $lang['Wait response time']; ?>:</th>
		<td>
			<input type="text" class="number int byte" name="reply_timeout" 
				value="<?php echo $device->reply_timeout; ?>" 
				<?php
				if ($_SESSION['access'] < 800)
				{
					echo 'readonly="readonly"';
				}
				?> />
			<?php echo $lang['seconds']; ?>
		</td>
	</tr>
	<?php $this->showView('settings'); ?>
</table>
<div class="ui-widget-header" style="clear: both; padding: 5px 0;">
	<a class="back"><?php echo $lang['Cancel'] ?></a>
	<?php
	if ($_SESSION['access'] >= 300)
	{
		?>
		<a class="submit"><?php echo $lang['Save'] ?></a>
		<?php
	}
	?>
</div>
</form>
<script type="text/javascript">
jQuery(".incr").click(function()
{
	var input = jQuery(this).parent().find("input");
	var v = new Number(jQuery(input).val());
	if (v < 254)
	{
		jQuery(input).val(v + 1);
		updatePercent(input);
	}
});

jQuery(".decr").click(function()
{
	var input = jQuery(this).parent().find("input");
	var v = new Number(jQuery(input).val());
	if (v > 2)
	{
		jQuery(input).val(v - 1);
		updatePercent(input);
	}
});

jQuery(".byte").change(function()
{
	if (jQuery(this).val() > 255)
	{
		jQuery(this).val(255);
	}
});

function validatePowers()
{
	if (Number(jQuery('input[name="min_level"]').val()) > Number(jQuery('input[name="max_level"]').val()))
	{
		jQuery('input[name="min_level"]').addClass("invalid");
		jQuery('input[name="max_level"]').addClass("invalid");
		
		return 0;
	}
	
	if (Number(jQuery('input[name="min_level"]').val()) > Number(jQuery('input[name="power_on_level"]').val()))
	{
		jQuery('input[name="min_level"]').addClass("invalid");
		jQuery('input[name="power_on_level"]').addClass("invalid");
		
		return 0;
	}
	
	if (Number(jQuery('input[name="max_level"]').val()) < Number(jQuery('input[name="power_on_level"]').val()))
	{
		jQuery('input[name="max_level"]').addClass("invalid");
		jQuery('input[name="power_on_level"]').addClass("invalid");
		
		return 0;
	}
	
	return 1;
}

jQuery('input[name="address"]').change(function()
{
	jQuery.get("index.php",
	{
		content: "devices",
		action: "hexdecAddress",
		address: jQuery(this).val()
	}, function(data)
	{
		jQuery('input[name="addressdec"]').val(data);
	});
});

jQuery('input[name="addressdec"]').change(function()
{
	jQuery.get("index.php",
	{
		content: "devices",
		action: "dechexAddress",
		address: jQuery(this).val()
	}, function(data)
	{
		jQuery('input[name="address"]').val(data);
	});
});

<?php 
if ($device->id && !$device->active)
{
	?>
	jQuery('input[type="text"]').each(function()
	{
		if ((jQuery(this).attr("name") != "name") 
			&& (jQuery(this).attr("name") != "address"))
		{
			jQuery(this).attr("readonly", "readonly");
		}
	});
	jQuery('input[type="radio"], input[type="checkbox"]').each(function()
	{
		jQuery(this).attr("disabled", "disabled");
	});
	jQuery(".incr, .decr").remove();
	<?php
}
?>
</script>