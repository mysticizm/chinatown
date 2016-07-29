<?php
    class Colorpickers extends Model {
        public $properties = array(
            "id"=>null,
            "rgb"=>"",
            "hex"=>"",
            "time"=>"",
            "schedule_id"=>""
        );

        function __construct()
        {
            parent::__construct();
            $this->tableName = "colorpicker";
        }
}
?>