<?php
class Alarms extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('device');
	}
	
	function defaultAction()
	{
		$open_time = $this->SQL->datetime_to_bgdatetime("open_time");
		$query = "SELECT $open_time, c.name AS alarm, d.name AS device, c.priority, d.id AS device_id, a.line_number, 
						c.code
					FROM alarms a
						LEFT JOIN alarm_codes c ON a.alarm = c.code
						LEFT JOIN devices d ON a.device_id = d.id
					WHERE a.active = 1 AND IF(a.device_id, d.deleted = 0, 1)
					ORDER BY c.priority DESC, a.open_time DESC";
		$result = $this->SQL->query($query);
		$this->_template->setView('alarms');
		$array = array(
			"alarms"=>$result
		);
	    $this->_template->simpleRender($array);
	}
	
	function showAll()
	{
		if (isset($_REQUEST['page']))
		{
			$page = (int)$_REQUEST['page'];
		}
		if ((!isset($page)) || ($page < 1))
		{
			$page = 1;
		}
		$startFrom = ($page - 1) * 20;
		
		$where = "IF(a.device_id, d.deleted = 0, 1)";
		
		$device = 0;
		if (isset($_REQUEST['device']) && $_REQUEST['device'])
		{
			$where .= " AND a.device_id = ".(int)$_REQUEST['device'];
			$device = (int)$_REQUEST['device'];
		}
		
		$open_time = $this->SQL->datetime_to_bgdatetime("open_time");
		$close_time = $this->SQL->datetime_to_bgdatetime("close_time");
		$query = "SELECT $open_time, $close_time, line_number,
					c.name AS alarm, d.name AS device, c.priority, d.id AS device_id, c.code
					FROM alarms a
						LEFT JOIN alarm_codes c ON a.alarm = c.code
						LEFT JOIN devices d ON a.device_id = d.id
					WHERE $where
					ORDER BY a.active DESC, a.open_time DESC
					LIMIT $startFrom, 20";
		$result = $this->SQL->query($query);
		$query = "SELECT COUNT(a.id)
					FROM alarms a
						LEFT JOIN alarm_codes c ON a.alarm = c.code
						LEFT JOIN devices d ON a.device_id = d.id
					WHERE $where";
		$countResult = $this->SQL->query($query);
		$count = $countResult->fetch_row();
		$pages = ceil($count[0] / 20);
		$this->_template->setView('alarms');
		$array = array(
			"title"=>$this->lang['All alarms'],
			"alarms"=>$result,
			"devices"=>$this->_model->select(
				"id, name, address",
				"devices",
				"deleted = 0",
				"address"
			),
			"device"=>$device,
			"close_time"=>true,
			"p"=>$page,
			"pages"=>$pages
		);
		if (!isset($_REQUEST['page']))
		{
			$this->_template->render($array);
		}
		else
		{
			$this->_template->simpleRender($array);
		}
	}
}
?>