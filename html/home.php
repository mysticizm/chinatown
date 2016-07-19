<div class="group"><span class="first">
	<?php echo $lang['Group']; ?>:</span>
	<select name="group">
		<option value="0"><?php echo $lang['All devices']; ?></option>
		<?php
		for ($i = 1; $i <= 32; $i++)
		{
			echo '<option value="'.$i.'">'.$i.'</option>'."\n";
		}
		?>
	</select>
	<?php
	if ($_SESSION['access'] >= 300)
	{
		?>
		<a class="button broadcast" ><span><?php echo $lang['Broadcast settings']; ?></span><img src="images/setting.png"></a>
		<?php
	}
	if ($_SESSION['access'] >= 800)
	{
		?>
		<a class="button newDevice" href="index.php?content=deviceAdmin&action=newDevice" ><img class="add_i" src="images/plus.png"><span><?php echo $lang['New device']; ?></span></a>
		<?php
	}
	?>
</div>

<script type="text/javascript">
jQuery('select[name="group"]').change(function()
{
	clearTimeout(t1);
	jQuery(".left").load("index.php",
	{
		content: "devices",
		action: "showList",
		group: jQuery(this).val()
	}, function()
	{
		fullMap();
	});
});

var deviceMarkers = [];

var cPicker = null;
</script>
<div class="left" style="clear: both; display: none;" id="leftContent">

</div>
<div id="mapContainer" class="fullMap">
	<?php $this->showView('map'); ?>
	<div class="stateIndicator"></div>
	<div class="clockIndicator"></div>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
function fullMap()
{
	var map = initializeGoogleMapsAPI();
	var c = map.getCenter();
	
	jQuery("#leftContent").hide();
	jQuery("#mapContainer").removeClass("right");
	jQuery("#mapContainer").addClass("fullMap");
	
	google.maps.event.trigger(map, "resize");
	map.setCenter(c);
}

function smallMap()
{
	var map = initializeGoogleMapsAPI();
	var c = map.getCenter();
	
	jQuery("#leftContent").show();
	jQuery("#mapContainer").addClass("right");
	jQuery("#mapContainer").removeClass("fullMap");
	
	google.maps.event.trigger(map, "resize");
	map.setCenter(c);
}

function loadDevice(device_id)
{
	clearTimeout(t1);
	jQuery(".left").load("index.php",
	{
		content: "devices",
		action: "edit",
		id: device_id
	});
	infowindow.close();
}

function loadLevels(device_id)
{
	clearTimeout(t1);
	jQuery(".left").html('<img src="images/loading.gif" />');
	jQuery(".left").load("index.php",
	{
		content: "devices",
		action: "levels",
		id: device_id
	});
	infowindow.close();
}

function loadBaloon(device_id)
{
	jQuery.get("index.php",
	{
		content: "devices",
		action: "baloon",
		id: device_id
	}, function(data)
	{
		infowindow.setContent(data);
	});
}


jQuery(".broadcast").click(function()
{
	clearTimeout(t1);
	jQuery(".left").load("index.php",
	{
		content: "DeviceAdmin",
		action: "broadcast"
	});
});

function reloadDevices()
{
	jQuery(".left").load('index.php?content=devices&group='+jQuery('select[name="group"]').val(),
	function()
	{
		loadAlarms();
	});
}

function locateDevice(device_id)
{
	if (deviceMarkers[device_id] != undefined)
	{
		var map = initializeGoogleMapsAPI();
		map.setCenter(deviceMarkers[device_id].getPosition());
		deviceMarkers[device_id].setAnimation(google.maps.Animation.DROP);
	}
}

function deleteDevice(device_id)
{
	if (confirm("<?php echo $lang['Delete device q']; ?>"))
	{
		jQuery.get("index.php",
		{
			content: "DeviceAdmin",
			action: "delete",
			id: device_id
		});
		jQuery(".device"+device_id).hide(1000);
		if (deviceMarkers[device_id] != undefined)
		{
			deviceMarkers[device_id].setMap(null);
		}
	}
}

function updatePercent(me)
{
	if (jQuery(me).val() < 2)
	{
		jQuery(me).val(2);
	}
	if (jQuery(me).val() > 254)
	{
		jQuery(me).val(254);
	}
	
	var perc = (Math.round(jQuery(me).val()  / 0.254) / 10 + '%');
	jQuery(me).parent().find(".perc").html(perc);
}

function afterMapInit()
{
	<?php
	foreach ($devices as $key => $device)
	{
		if (is_numeric($device->lat) && is_numeric($device->lng))
		{
			?>
			deviceMarkers[<?php echo $device->id; ?>] = newMarker(new google.maps.LatLng(
					<?php echo $device->lat; ?>,
					<?php echo $device->lng; ?>
				),
				'<?php echo $device->name; ?>',
				<?php echo $device->id; ?>
			);
			<?php
		}
	}
	?>
	
	reloadDevices();
}
</script>