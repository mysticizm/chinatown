<?php
class Timings extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('SystemData');
	}

	function defaultAction()
	{
		$this->edit();
	}
	
	function edit()
	{
		$result = $this->_model->select("*", "system", "id = 1");
		$row = $result->fetch_object();
		$this->_template->setView('timings');
		$array = array(
			"title"=>$this->lang['System settings'],
			"system"=>$row
		);
	    $this->_template->render($array);
	}
	
	function save()
	{
		$this->_model->loadFrom($_POST);
		$this->_model->update();
		$this->edit();
	}
	
	function restartService()
	{
		if ($_POST['restart'])
		{
			$processes = explode("\n", shell_exec('ps -ef'));
			echo $processes[0]."\n";
			foreach ($processes as $key => $process)
			{
				if (str_replace('master.php', "", $process) != $process)
				{
					$process_arr = preg_split('/\s+/', $process);
					echo $process."\n";
					//echo "<br/>".$process_arr[1];
					//var_dump($process_arr);
					echo "kill ".$process_arr[1]."\n";
					shell_exec("kill ".$process_arr[1]);
				}
			}
			
			/*$query = "DELETE FROM `log` WHERE i_o < 2";
			$this->SQL->query($query);
			echo $query."\n";
			$query = "UPDATE devices SET active = 1";
			$this->SQL->query($query);
			echo $query."\n";*/
			sleep(1);
			$query = "php5 ".GLOBAL_PATH."master.php > ".GLOBAL_PATH."master.log 2>&1 &";
			exec($query);
			echo $query."\n";
		}
	}
}
?>