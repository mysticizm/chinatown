<?php
    class Schedules extends Model{
        public $properties = array(
            "id"=>null,
            "name"=>"",
            "group_number"=>"",
            "date"=>"",
            "start_time"=>"",
            "every_day"=>""
        );

        function __construct()
        {
            parent::__construct();
            $this->tableName = "schedule";
        }
    }
?>