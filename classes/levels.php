<?php
class Levels extends Device
{
	public $properties = array(
			"id"=>null,
			"level"=>254,
			"red"=>90,
			"green"=>60,
			"blue"=>90,
			"active"=>1,
			"levels_time"=>null
		);
		
	function __construct()
	{
		parent::__construct();
		$this->tableName = "devices";
	}
}
?>