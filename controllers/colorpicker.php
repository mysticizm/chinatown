<?php

class Colorpicker extends Controller{
    function __construct($action)
    {
        parent::__construct($action);
        $this->setModel('Colorpickers');
    }



    function defaultAction()
	{
		$this->showAll();
	}
	
	function showAll()
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "chinatown";
		$conn = $this->SQL->mysqli;
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$id=$_GET['id'];
		$sql = "SELECT * FROM `colorpicker` WHERE schedule_id='".$id."' ORDER BY position";
		$result=$conn->query($sql);
		$this->_template->setView('colorpicker');
		$array = array(
			"result"=>$result,
			"title"=>'Colorpicker'
		);
	    $this->_template->render($array);
	}
	function save()
    {
        $conn = $this->SQL->mysqli;
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $counter=0;
        for($i=0;$i<count($_POST['rgb']);$i+=3){
            $temp[$counter++]=$_POST['rgb'][$i].','.$_POST['rgb'][$i+1].','.$_POST['rgb'][$i+2];
        }
		if(count($_POST)>0){
            for($i = 0 ;$i < count($_POST['id']);++$i){
                if($_POST['id'][$i]>=0){
                    $sql="UPDATE colorpicker 
                    SET rgb='".$temp[$i]."',hex='".$_POST['hex'][$i]."',time='".$_POST['time'][$i]."',`position`=".$_POST['position'][$i]."
                    WHERE id='".$_POST['id'][$i]."'";
                    $result=$conn->query($sql);
                }
                else{
                    $sql="INSERT INTO colorpicker (hex,rgb,time,schedule_id,`position`) 
                          VALUES ('".$_POST['hex'][$i]."','".$temp[$i]."','".$_POST['time'][$i]."','".$_GET['id']."','".$_POST['position'][$i]."')";
                    $result=$conn->query($sql);
                    $_POST['hex'][$i]=1;
                }
                $sql="DELETE FROM colorpicker WHERE id=".$_POST['id'][$i]." AND ".$_POST['hex'][$i]."=0";
                $result=$conn->query($sql);
            }
		}
		$this->showAll();
	}
}