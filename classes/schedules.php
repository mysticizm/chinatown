<?php
    class Schedules extends Model{
        public $properties = array(
            "id"=>null,
            "name"=>"",
            "group_number"=>"",
            "date"=>"",
            "every_day"=>""
        );

        function __construct()
        {
            parent::__construct();
            $this->tableName = "schedule";
        }
    }
?>