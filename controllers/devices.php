<?php
class Devices extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('device');
	}
	
	function defaultAction()
	{
		$this->showList();
	}
	
	function showList()
	{
		$where = "d.deleted = 0";
		$group = 0;
		if ((isset($_REQUEST['group'])) && ((int)$_REQUEST['group']))
		{
			$group = (int)$_REQUEST['group'];
			$shift = $group - 1;
			$where .= " AND (d.groups >> $shift) & 1";
		}
		$result = $this->_model->select(
					"*", 
					"devices d", 
					$where
				);
		$devices = array();
		while ($row = $result->fetch_object())
		{
			$devices[] = $row;
		}
		$this->_template->setView('device_list');
		$array = array(
			"devices"=>$devices,
			"group"=>$group,
			"alarms"=>$this->_model->select(
				"a.id, c.name", 
				"alarms a LEFT JOIN alarm_codes c ON c.code = a.alarm", 
				"a.active = 1 AND c.priority >= 250",
				"c.priority DESC"
			)
		);
	    $this->_template->simpleRender($array);
	}
	
	function edit()
	{
		if (isset($_REQUEST['id']))
		{
			$this->_model->properties['active'] = null;
			$this->_model->load((int)$_REQUEST['id']);
			$this->_template->setView('device_edit');
			$array = array(
				"device"=>$this->_model->getProperties()
			);
			$this->_template->simpleRender($array);
		}
	}
	
	function levels()
	{
		if (isset($_REQUEST['id']))
		{
			$l = new Levels;
			$l->load($_REQUEST['id']);
			$this->_template->setView('device_levels');
			$array = array(
				"device"=>$l->getProperties()
			);
			$this->_template->simpleRender($array);
		}
	}
	
	function baloon()
	{
		if (isset($_REQUEST['id']))
		{
			$this->_model->properties['active'] = null;
			$this->_model->properties['worktime'] = 0;
			$this->_model->properties['onoff'] = 1;
			$this->_model->load((int)$_REQUEST['id']);
			$this->_template->setView('device_preview');
			$array = array(
				"device"=>$this->_model->getProperties()
			);
			$this->_template->simpleRender($array);
		}
	}
	
	function getLastStats()
	{
		if (isset($_REQUEST['id']))
		{
			$id = (int)$_REQUEST['id'];
			$this->_model->load($id);
			$stats = array();
			$result = $this->SQL->query("SELECT * FROM `lines` WHERE device_id = $id AND deleted = 0 ORDER BY line_number");
			while ($line = $result->fetch_object())
			{
				$i = 'i * '.$line->k_i;
				$i = "IF($i > 0.35, $i, 0)";
				$u = 'u * '.$line->k_u; 
				$u = "IF($u < 50, (u + 494) * ".$line->k_u.", $u)";
				$fi = 'fi / 10 + '.$line->k_fi;
				$p = "ROUND($i * $u / 1000, 3)";
				if ($_SESSION['access'] >= 800)
				{
					$p = "ROUND($i * $u * COS(($fi) * PI() / 180) / 1000, 3)";
				}
				$stat_time = $this->SQL->datetime_to_bgdatetime("instime", "stat_time");
				$where = "line_id = ".$line->id;
				if ($_SESSION['access'] < 800)
				{
					$where .= " AND $u > 190";
				}
				$query = "SELECT ROUND($i, 2) AS i, ROUND($u, 1) AS u, ROUND($fi, 1) AS fi, $p AS p, ".$line->line_number." AS line_number, $stat_time
							FROM line_stats 
							WHERE $where
							ORDER BY instime DESC 
							LIMIT 1";
				$statResult = $this->SQL->query($query);
				while ($row = $statResult->fetch_object())
				{
					$stats[] = $row;
				}
			}
			$this->_template->setView('device_stats');
			$array = array(
				"device"=>$this->_model->getProperties(),
				"stats"=>$stats
			);
			$this->_template->simpleRender($array);
		}
	}
	
	function dechexAddress()
	{
		if (isset($_REQUEST['address']))
		{
			$addr = (int)$_REQUEST['address'];
			$addr = ((0x0F80 & $addr) * 2) + (0x007F & $addr);
			$address = dechex($addr);
			for ($i = strlen($address); $i < 4; $i++)
			{
				$address = '0'.$address;
			}
			
			echo $address;
		}
	}
	
	function hexdecAddress()
	{
		if (isset($_REQUEST['address']))
		{	
			$addr = hexdec($_REQUEST['address']);
			$addr = ((0x0F00 & $addr) / 2) + (0x007F & $addr);
			
			echo $addr;
		}
	}
}
?>