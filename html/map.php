<div id="map_canvas"></div>
<div style="height: 10px;"></div>
<script type="text/javascript">
jQuery("#map_canvas").height(jQuery(window).height() - 130);

var infowindow;

jQuery(document).ready(function()
{
	var map = initializeGoogleMapsAPI();
	
	afterMapInit();
	
	infowindow = new google.maps.InfoWindow({
		content: '<div>test</div>',
		maxWidth: 300
	});
	
	google.maps.event.addListener(map, 'zoom_changed', function(event) 
	{
		//alert(map.getZoom());
		if(map.getZoom() >= 19)
		{
			for (i in deviceMarkers)
			{
				deviceMarkers[i].labelClass = "markerLabels";
			}
		}
		else
		{
			for (i in deviceMarkers)
			{
				deviceMarkers[i].labelClass = "hiddenMarker";
			}
		}
	});
});

function newMarker(location, name, id) 
{
	var map = initializeGoogleMapsAPI();
	
	var marker = new MarkerWithLabel({
		position: location,
		labelContent: name,
		icon: "images/lamp.png",
		labelAnchor: new google.maps.Point(50, 0),
		labelClass: "hiddenMarker"
	});
	marker.setMap(map);
	<?php
	if ($_SESSION['access'] >= 300)
	{
		?>
		google.maps.event.addListener(marker, 'dragend', function(event) 
		{
			jQuery('input[name="lat"]').val(event.latLng.lat());
			jQuery('input[name="lng"]').val(event.latLng.lng());
		});
		<?php
	}
	?>
	
	if (id)
	{
		<?php
		if ($_SESSION['access'] >= 300)
		{
			?>
			google.maps.event.addListener(marker, 'rightclick', function(event) 
			{
				
				jQuery('#add_group .device_name').html(name);
				jQuery('#add_group input[name="device_id"]').val(id);
				jQuery('#add_group').dialog("open");
			});
			<?php
		}
		?>
		
		google.maps.event.addListener(marker, 'dblclick', function(event) 
		{
			loadDevice(id);
		});
		
		google.maps.event.addListener(marker, 'click', function(event) 
		{
			var map = initializeGoogleMapsAPI();
			infowindow.setContent("Loading...");
			infowindow.open(map, marker); 
			loadBaloon(id);
			map.setCenter(marker.getPosition());
			map.panBy(0, -120);
		});
	}
	
	return marker;
}
</script>