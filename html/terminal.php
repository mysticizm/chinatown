<div class="terminal">
	<input type="text" id="command" />
	<a class="button second"><?php echo $lang['Send']; ?></a>
	<form id="filter" style="display: inline;">
		<input type="text" class="address" id="address" />
		<a class="button"><?php echo $lang['Filter']; ?></a>
	</form>
	<a class="button openHistory"><?php echo $lang['Archive']; ?></a>
	<a class="button openQueue"><?php echo $lang['Queue']; ?></a>
	<a class="button help">?</a>
	<ul id="log">
	 
	</ul>
	<div class="dialog commandsHelp">
		<?php $this->showView('help'); ?>
	</div>
	<div style="clear: both;"></div>
	<div class="dialog history">
		<form>
			<input type="hidden" name="content" value="terminal" />
			<input type="hidden" name="action" value="getHistory" />
			<label for="from_date" style="width: 50px; display: inline-block;"><?php echo $lang['From']; ?>: </label>
			<input type="text" class="date required" name="from_date" id="from_date" />
			<input type="text" class="time" name="from_time" />
			<br/>
			<label for="to_date" style="width: 50px; display: inline-block;"><?php echo $lang['To']; ?>: </label>
			<input type="text" class="date required" name="to_date" id="to_date" />
			<input type="text" class="time" name="to_time" />
			<a class="button showHistory"><?php echo $lang['Show']; ?></a>
		</form>
		<ul>
		 
		</ul>
	</div>
	<div class="dialog queue">
		<ul>
		 
		</ul>
	</div>
</div>
<script type="text/javascript">
function getLog(last)
{
	jQuery.get("index.php",
	{
		content: "terminal",
		action: "getLog",
		last_id: last
	},
	function(data)
	{
		var last_class = "even";
		if (jQuery("#log li").length)
		{
			if (jQuery("#log li").first().hasClass("odd"))
			{
				last_class = "odd";
			}
		}
		jQuery("#log").prepend(data);
		var new_commands = jQuery("#log li.new");
		for (var i = new_commands.length - 1; i >= 0; i--)
		{
			jQuery(new_commands[i]).removeClass("new");
			if (last_class == "even")
			{
				jQuery(new_commands[i]).addClass("odd");
				last_class = "odd";
			}
			else
			{
				jQuery(new_commands[i]).addClass("even");
				last_class = "even";
			}
		}
		var last_id = 0;
		if (jQuery("#log .log_id").length)
		{
			last_id = jQuery("#log .log_id").first().html();
		}
		filter();
		var t = setTimeout("getLog("+last_id+")", 1000);
	});
}

getLog(0);
jQuery(".second").click(function()
{
	var patt = new RegExp(/[0-9a-fA-F\s]*/);
	var value = jQuery("#command").val();
	if (patt.exec(value) != value)
	{
		jQuery("#command").addClass("invalid");
	}
	else
	{
		jQuery.get("index.php",
		{
			content: "terminal",
			action: "send",
			command: value
		},
		function(data)
		{
			if (data == 'OK')
			{
				jQuery("#command").removeClass("invalid");
				jQuery("#command").val('');
			}
			else
			{
				jQuery("#command").addClass("invalid");
			}
		});
	}
});

jQuery("#command").keypress(function(event)
{
	if ( event.which == 13 ) {
		jQuery(".broadcast").click();
	}
});

function filter()
{
	if (validate("#filter"))
	{
		var adr = jQuery("#filter .address").val();
		if (adr.length)
		{
			adr = adr.toUpperCase();
			adr = adr[0] + adr[1] + " " + adr[2] + adr[3];
			jQuery("#log .RED").each(function()
			{
				//alert(jQuery(this).html());
				if (jQuery(this).html() != adr)
				{
					jQuery(this).parent().hide();
				}
			});
		}
		else
		{
			jQuery("#log li").show();
		}
	}
}

jQuery("#filter .address").change(function()
{
	filter();
});

jQuery(".history").dialog({
	title: "<?php echo $lang['Archive']; ?>",
	autoOpen: false,
	width: 500,
	height: 300
});
setCalendars(".date", new Date(2014, 9 - 1, 2));
jQuery("a.openHistory").click(function()
{
	jQuery(".history").dialog("open");
});
jQuery(".showHistory").click(function()
{
	if (validate(".history form"))
	{
		jQuery(".history ul").html('');
		jQuery(".history ul").load("index.php", jQuery(".history form").serialize());
	}
});

function loadQueue()
{
	jQuery(".queue ul").load("index.php",
	{
		content: "terminal",
		action: "getQueue"
	});
}
jQuery(".queue").dialog({
	title: "<?php echo $lang['Queue']; ?>",
	autoOpen: false,
	width: 300,
	height: 400,
	buttons:
	{
		"<?php echo $lang['Refresh']; ?>":function()
		{
			jQuery(".queue ul").html('');
			loadQueue();
		},
		"<?php echo $lang['Delete']; ?>":function()
		{
			jQuery(".queue ul").html('');
			jQuery(".queue ul").load("index.php",
			{
				content: "terminal",
				action: "clearQueue"
			});
		},
		"<?php echo $lang['Close']; ?>":function()
		{
			jQuery(".queue ul").html('');
			jQuery(this).dialog("close");
		}
	}
});
jQuery("a.openQueue").click(function()
{
	loadQueue();
	jQuery(".queue").dialog("open");
});

jQuery(".accordion").accordion({
	heightStyle: "content"
});
jQuery(".tabs").tabs({
	heightStyle: "content"
});

jQuery("a.help").click(function()
{
	//jQuery(".commandsHelp").dialog("open");
	if (jQuery("#log").hasClass("left"))
	{
		jQuery("#log").removeClass("left");
		jQuery(".commandsHelp").addClass("dialog");
		jQuery(".commandsHelp").removeClass("right");
	}
	else
	{
		jQuery("#log").addClass("left");
		jQuery(".commandsHelp").removeClass("dialog");
		jQuery(".commandsHelp").addClass("right");
	}
});
</script>