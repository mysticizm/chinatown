<?php
class DeviceGroup extends Model
{
	public $properties = array(
			"id"=>null,
			"name"=>""
		);
		
	function __construct()
	{
		parent::__construct();
		$this->tableName = "groups";
	}
	
	function add()
	{
		// DO NOTHING
	}
}
?>