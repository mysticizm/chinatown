<div class="left">
	<?php $this->showView('group_list'); ?>
</div>
<div class="right">

</div>
<div style="clear: both;"></div>
<script type="text/javascript">
function loadGroup(group_id)
{
	jQuery(".right").load("index.php",
	{
		content: "DeviceGroups",
		action: "edit",
		id: group_id
	});
}

function deleteGroup(group_id)
{
	if (confirm("Наистина ли искате да изтриете група №"+group_id+"?"))
	{
		jQuery.get("index.php",
		{
			content: "DeviceGroups",
			action: "delete",
			id: group_id
		});
		jQuery(".group"+group_id).find(".name").html("");
	}
}
</script>