jQuery(document).ready(function()
{
	//jQuery("#menu_button").button();
	//jQuery(".menu").buttonset();
	prepareUI();
	jQuery(".changePass").dialog({
		modal: true,
		autoOpen: false,
		resizable: false,
		width: 400,
		buttons: 
		{
			"Потвърди": function() 
			{ 
				if (jQuery(".password1").val() != jQuery(".password2").val())
				{
					jQuery(".changePass .alert").html("Двете пароли не съответстват");
				}
				else if (jQuery(".changePass .password1").val() == '')
				{
					jQuery(".changePass .alert").html("Попълнете новата парола");
				}
				else 
				{
					jQuery.post("index.php",
					{
						content: "users",
						action: "changePassword",
						password: jQuery(".changePass .password").val(),
						new_pass: jQuery(".changePass .password1").val()
					},
					function(data)
					{
						if (jQuery.trim(data) == 'OK')
						{
							jQuery('.changePass input[type="password"]').val('');
							jQuery('.changePass .alert').html('');
						}
						else
						{
							jQuery(".changePass").dialog("open");
							jQuery(".changePass .alert").html(data);
						}
					});
					jQuery(this).dialog("close"); 
				}
			},
			"Отказ": function() 
			{ 
				jQuery(this).dialog("close"); 
				jQuery('.changePass input[type="password"]').val('');
				jQuery('.changePass .alert').html('');
			}
		}
	});
	jQuery(".changePassLink").click(function()
	{
		jQuery(".changePass").dialog("open");
	});
});

function checkButton(element)
{
	if (element.checked)
	{
		if (jQuery(element).attr("type") == "radio")
		{
			jQuery('input[name="'+jQuery(element).attr("name")+'"]').button("option", "icons", {primary: "ui-icon-close"});
		}
		jQuery(element).button("option", "icons", {primary: "ui-icon-check"});
	}
	else
	{
		jQuery(element).button("option", "icons", {primary: "ui-icon-close"});
	}
}

function prepareUI()
{
	//jQuery(".button").button();
	jQuery(".checkButton").each(function()
	{
		jQuery(".checkButton").button();
		checkButton(this);
		jQuery(this).change(function()
		{
			checkButton(this);
		});
	});
	//jQuery(document).tooltip({}); 
	jQuery(".add").button({icons: {primary: "ui-icon-circle-plus"}});
	jQuery(".submit").button({icons: {primary: "ui-icon-check"}});
	jQuery(".cancel").button({icons: {primary: "ui-icon-cancel"}});
	jQuery(".back").button({icons: {primary: "ui-icon-arrowthick-1-w"}});
	jQuery(".reload").button({icons: {primary: "ui-icon-refresh"}});
	jQuery(".reset").button({icons: {primary: "ui-icon-closethick"}});
	jQuery(".lines").button({icons: {primary: "ui-icon-grip-solid-vertical"}, text: false});
	jQuery(".delete").button({icons: {primary: "ui-icon-trash"}, text: false});
	jQuery(".edit").button({icons: {primary: "ui-icon-pencil"}, text: false});
	jQuery(".minus").button({icons: {primary: "ui-icon-minusthick"}, text: false});
	jQuery(".plus").button({icons: {primary: "ui-icon-plusthick"}, text: false});
	jQuery(".plusc").button({icons: {primary: "ui-icon-circle-plus"}, text: false});
	jQuery(".settings").button({icons: {primary: "ui-icon-gear"}, text: false});
	jQuery(".locate").button({icons: {primary: "ui-icon-arrow-4"}, text: false});
	//jQuery(".broadcast").button({icons: {primary: "ui-icon-signal-diag"}});
	jQuery(".power").button({icons: {primary: "ui-icon-power"}, text: false});
	jQuery(".leftArrow").button({icons: {primary: "ui-icon-arrowthick-1-w"}, text: false});
	jQuery(".rightArrow").button({icons: {primary: "ui-icon-arrowthick-1-e"}, text: false});
	jQuery(".upArrow").button({icons: {primary: "ui-icon-arrowthick-1-n"}, text: false});
	jQuery(".downArrow").button({icons: {primary: "ui-icon-arrowthick-1-s"}, text: false});
	setCalendars(".date", null);
	setRequired();
	
	jQuery('input[type="text"], select').change(function()
	{
		validate(jQuery(this).parent());
	});
}

// Calendar for fields which need it
function setCalendars(selector, minDate)
{
	jQuery(selector).datepicker({ 
			dateFormat: "dd.mm.yy",
			dayNamesMin: ['Не', 'По', 'Вт', 'Ср', 'Че', 'Пе', 'Съ'],
			firstDay: 1,
			monthNames: ['Януари','Февруари','Март','Апил','Май','Юни','Юли','Август','Септември','Октомври','Ноември','Декември'],
			yearRange: '2011:c+1',
			showMonthAfterYear: true,
			"minDate": minDate,
			"maxDate": 0
		});
	jQuery(selector).attr("maxlength", 10);
}

function setRequired()
{
	jQuery("input.required, select.required").each(function()
	{
		if (!jQuery(this).hasClass("requiredFlag"))
		{
			jQuery(this).addClass("requiredFlag");
			jQuery(this).after('<span class="requiredMark" title="Required field">*</span>');
		}
	});
}

function validate(selector)
{
	var value = "";
	// Check dates
	var inputs = jQuery(selector).find("input.date");
	var patt1 = new RegExp(/[0123]\d\.[01]\d\.\d{4}/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check datetimes
	var inputs = jQuery(selector).find("input.datetime");
	var patt1 = new RegExp(/[0123]\d\.[01]\d\.\d{4} [012]\d\:[012345]\d\:[012345]\d/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check times
	var inputs = jQuery(selector).find("input.time");
	var patt1 = new RegExp(/[012]\d\:[012345]\d/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	
	var inputs = jQuery(selector).find("input.time1");
	var patt1 = new RegExp(/00\:[012345]\d/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check integers
	var inputs = jQuery(selector).find("input.int");
	var patt1 = new RegExp(/\d*/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check real
	var inputs = jQuery(selector).find("input.real");
	var patt1 = new RegExp(/\-?\d*\.?\d*/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check device address
	var inputs = jQuery(selector).find("input.address");
	var patt1 = new RegExp(/0[0-9a-fA-F][0-9a-fA-F][0-9a-fA-F]/);
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery(inputs[i]).val();
		if ((value != '') && (patt1.exec(value) != value))
		{
			jQuery(inputs[i]).addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid");
		}
	}
	// Check required inputboxes
	var inputs = jQuery(selector).find("input.required:visible");
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery.trim(jQuery(inputs[i]).val());
		if (value == '')
		{
			jQuery(inputs[i]).addClass("invalid1");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid1");
		}
	}
	// Check required selectboxes
	var inputs = jQuery(selector).find("select.required");
	for (var i = 0; i < inputs.length; i++)
	{
		value = jQuery.trim(jQuery(inputs[i]).val());
		if (value == '')
		{
			jQuery(inputs[i]).addClass("invalid1");
			jQuery(inputs[i]).children(":selected").addClass("invalid");
		}
		else
		{
			jQuery(inputs[i]).removeClass("invalid1");
			jQuery(inputs[i]).children().removeClass("invalid");
		}
	}
	
	return !(jQuery(selector).find(".invalid").length + jQuery(selector).find(".invalid1").length);
}

function initializeGoogleMapsAPI() 
{
	if (typeof initializeGoogleMapsAPI.map == "undefined")
	{
		var myOptions = {
			zoom: 16,
			minZoom: 15,
			maxZoom: 25,
			panControl: true,
			zoomControl: true,
			mapTypeControl: false,
			scaleControl: true,
			streetViewControl: false,
			overviewMapControl: false,
			center: new google.maps.LatLng(49.279742, -123.103923),
			mapTypeId: google.maps.MapTypeId.HYBRID,
			styles: [{
				featureType: "poi",
				elementType: "labels",
				stylers: [{
					visibility: "off"
				}]
			},
			{
				featureType: "transit",
				elementType: "labels",
				stylers: [{
					visibility: "off"
				}]
			}]
		}
		initializeGoogleMapsAPI.map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	}
	return initializeGoogleMapsAPI.map;
}