<div class="left new">	<?php $this->showView('device_details'); ?></div><div class="right new">	<?php $this->showView('map'); ?></div><div style="clear: both;"></div><script type="text/javascript">prepareUI();function afterMapInit(){	var marker = newMarker(new google.maps.LatLng(			<?php echo $device->lat; ?>,			<?php echo $device->lng; ?>		),		'',		0	);	marker.setDraggable(true);}jQuery(".submit").click(function(){	if (validate('.left'))	{		jQuery.post("index.php", jQuery("#form").serialize(), function()		{			window.location.href = "index.php";		});	}});jQuery(".back").click(function(){	window.history.back();});</script>