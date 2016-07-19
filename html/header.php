<!DOCTYPE html>
<html>
<head>
<title><?php echo $lang['Project name']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="css/custom-theme/jquery-ui-1.9.1.custom.css" rel="stylesheet" type="text/css"/>
<link href="css/style.css?ver=2" type="text/css" rel="stylesheet">
<script type="text/javascript" SRC="js/jquery-1.8.2.js"></script>
<script type="text/javascript" SRC="js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" SRC="js/jqColorPicker.min.js"></script>
<script type="text/javascript" SRC="js/main.js?ver=2"></script>

<?php
if (isset($googleMaps))
{
	?>
	<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC5sIGsx5bLnr8dlW44utVDKA2RiIjnYTI&sensor=false">
    </script>
	<?php
}
?>
<script type="text/javascript" src="js/markerwithlabel.js"></script>
</head>
<body class="<?php echo $this->_controller; ?>">
    <div class="main">
		<div class="menu_topp">
		<?php
		if ($_SESSION['access'])
		{
			?>
			<ul class="user_settings"><li><a class="changePassLink"><?php echo $lang['Change Password']; ?></a></li>
			<li><a class="logedout" href="index.php?content=users&action=logOut"><?php echo $lang['Log Out']; ?></a></li>
			</ul>
			<div class="dialog changePass" title="<?php echo $lang['Change Password']; ?>">
				<table class="full" style="border: none;">
					<tr>
						<td><?php echo $lang['Old Password']; ?></td>
						<td style="width: 150px;"><input class="password textbox" type="password" /></td>
					</tr>
					<tr>
						<td><?php echo $lang['New Password']; ?></td>
						<td><input class="password1 textbox" type="password" /></td>
					</tr>
					<tr>
						<td><?php echo $lang['Confirm new password']; ?></td>
						<td><input class="password2 textbox" type="password" /></td>
					</tr>
					<tr>
						<td colspan="2" class="alert red"></td>
					</tr>
				</table>
			</div>
			<?php
		}
		?>
		<img class="menu_i" src="images/icon_menu.png">
		<?php
		if ($_SESSION['access'])
		{
			?>
			<div class="right alarmButton" style="cursor: pointer;">
				<div class="alarmAlert ui-state-error ui-corner-all" style="display: none">
					<div class="ui-icon ui-icon-alert" style="float: right;"></div>
					<p>
						<?php echo $lang['There are']; ?> <span class="alarmCount"></span> <?php echo $lang['Active alarms']; ?>!
					</p>
				</div>
				<div class="alarmMessage ui-state-highlight ui-corner-all">
					<div class="ui-icon ui-icon-check" style="float: right; "></div>
					<p>
						<?php echo $lang['There are']; ?> <span class="alarmCount">0</span> <?php echo $lang['Active alarms']; ?>!
					</p>
				</div>
			</div>
			<div id="alarms" class="dialog">
				<div id="alarmsContent">
				</div>
			</div>
			<?php
		}
		?>
		<div class="menu">
		<?php 
		foreach ($menu->links as $k=>$v)
		{	
			?>
			<a href="<?php echo PROJECT_DIRECTORY.$v->link; ?>" id="menu_button<?php echo $v->id; ?>"><?php echo $lang[$v->name]; ?></a>
			<?php
		}
		?>
		</div>
		</div>
	<?php
	if ($_SESSION['access'])
	{
		echo '<div class="user">'.$lang['User'].' '.$_SESSION['name'].'</div>'; 
	}
	?>
<h1><?php echo $title; ?></h1>
<script type="text/javascript">		
jQuery(document).ready(function()
{
	prepareUI();
	jQuery(".user ").click(function()
	{
		if (jQuery("ul.user_settings").hasClass("show"))
		{
			jQuery("ul.user_settings").removeClass("show");
			jQuery(".user").removeClass("width");
		}
		else
		{
			jQuery("ul.user_settings").addClass("show");
			jQuery(".user").addClass("width");
		}
	});
	jQuery(".menu_i ").click(function()
	{
		if (jQuery(".menu").hasClass("show"))
		{
			jQuery(".menu").removeClass("show")
		}
		else
		{
			jQuery(".menu").addClass("show")
		}
	});
});	

jQuery("#alarms").dialog(
{
	modal: false,
	autoOpen: false,
	resizable: true,
	width: 300,
	title: "<?php echo $lang['Active alarms'] ?>",
	position: ['right', 'top']
});

function loadAlarms()
{
	jQuery("#alarmsContent").load("index.php?content=alarms",
	function(data)
	{
		jQuery(".alarmCount").html(jQuery("#alarmsContent tr.alarm").length);
		if (jQuery("#alarmsContent tr.alarm").length)
		{
			//jQuery("#alarms").dialog("open");
			jQuery("#alarmsContent tr.alarm").css("cursor", "pointer");
			jQuery("#alarmsContent tr.alarm").click(function()
			{
				var device_id = jQuery(this).find(".device_id").html();
				if (deviceMarkers[device_id] != undefined)
				{
					var map = initializeGoogleMapsAPI();
					map.setCenter(deviceMarkers[device_id].getPosition());
					deviceMarkers[device_id].setAnimation(google.maps.Animation.DROP);
				}
			});
			jQuery(".alarmAlert").show();
			jQuery(".alarmMessage").hide();
			
		}
		else
		{
			//jQuery("#alarms").dialog("close");
			jQuery(".alarmAlert").hide();
			jQuery(".alarmMessage").show();
		}
		
		//var t = setTimeout("loadAlarms()", 15000);
	});
}

loadAlarms();

jQuery(".alarmButton").click(function()
{
	jQuery("#alarms").dialog("open");
});
function blinkErrors(opacity)
{
	var timeout;
	jQuery("#alarmsContent .ui-state-error").animate({opacity},400);
	jQuery(".alarmButton .ui-state-error").animate({opacity},400);
   	if (opacity == 1)
	{
        opacity=0.5;
	}
	else
	{
		opacity=1;
	}
	var t = setTimeout("blinkErrors('"+opacity+"')", 400);
}
blinkErrors("0.5");
</script>	
