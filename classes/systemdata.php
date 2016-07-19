<?php
class SystemData extends Model
{
	public $properties = array(
			"id"=>null,
			"reply_timeout"=>null,
			"retry_after"=>null,
			"stats_interval"=>null,
			"after_reply"=>null,
			"unplugged_timeout"=>null,
			"send_interval"=>null
		);
	
	function __construct()
	{
		parent::__construct();
		$this->tableName = "system";
	}
}
?>