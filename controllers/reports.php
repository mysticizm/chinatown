<?php
class Reports extends Controller
{
	function defaultAction()
	{
		$query = "SELECT l.id, l.line_number, d.name 
					FROM `lines` l
						LEFT JOIN devices d ON l.device_id = d.id
					WHERE l.deleted = 0 AND d.deleted = 0
					ORDER BY name, line_number";
		$lines = $this->SQL->query($query);
		$devices = $this->SQL->query("SELECT id, name FROM devices WHERE deleted = 0 ORDER BY name;");
		$groups = $this->SQL->query("SELECT id, name FROM groups WHERE name IS NOT NULL ORDER BY name;");
		$this->_template->setView('reports');
		$this->_template->render(array(
				"title"=>$this->lang['Reports'],
				"lines"=>$lines,
				"devices"=>$devices,
				"groups"=>$groups
			));
	}
	
	function getReport()
	{
		$data = array();
		// if The from-date is not empty
		if (isset($_REQUEST['from']) && !empty($_REQUEST['from']))
		{
			$from = DateTime::createFromFormat('d.m.Y H:i:s', $_REQUEST['from'].' 00:00:00');
		}
		// else: set the from-date to yesterday
		else
		{
			$from = new DateTime("now");
			$from->sub(new DateInterval('P1D'));
		}
		// if The to-date is not empty
		if (isset($_REQUEST['to']) && !empty($_REQUEST['to']))
		{
			$to = DateTime::createFromFormat('d.m.Y H:i:s', $_REQUEST['to'].' 00:00:00');
			// We have to set the to-date one day later.
			// The period must contain the to-date
			$to->add(new DateInterval('P1D'));
		}
		// else: set the to-date to current date
		else
		{
			$to = new DateTime("now");
		}
		
		$i = 's.i * l.k_i';
		$i = "IF($i > 0.35, $i, 0)";
		$u = 's.u * l.k_u';
		$u = "IF(s.u < 100, (s.u + 494) * l.k_u, s.u * l.k_u)";
		$p = "$i * $u / 1000";
		if ($_SESSION['access'] >= 800)
		{
			$p = "$i * $u * COS((s.fi / 10 + l.k_fi) * PI() / 180) / 1000";
		}
		$line = $_REQUEST['line'];
		switch($line[0])
		{
			case 'a':
			default:
				$where = "1";
				break;
			case 'g':
				$where = "d.groups & (1 << ".((int)substr($line, 1) - 1).")";
				break;
			case 'd':
				$where = "l.device_id = ".substr($line, 1);
				break;
			case 'l':
				$where = "s.line_id = ".substr($line, 1);
				break;
		}
		if ($_SESSION['access'] < 800)
		{
			$where .= " AND $u > 190";
		}
		//echo $where;
		
		// Which factor is selected?
		switch($_REQUEST['statsType'])
		{
			case 1:
				$field = $p;
				$type = 1;
				break;
			case 2:
				$field = $i;
				$type = 1;
				break;
			case 3:
				$field = $u;
				$type = 1;
				break;
			case 4:
				$type = 2;
				$group = 'y, m, d, h';
				$offset = 'PT1H';
				break;
			case 5:
				$type = 2;
				$group = 'y, m, d';
				$offset = 'P1D';
				break;
			case 6:
				$type = 2;
				$group = 'y, m';
				$offset = 'P1M';
				break;
			case 7:
				$type = 2;
				$group = 'y';
				$offset = 'P1Y';
				break;
		}
		
		// Type 1 is line chart
		if ($type == 1)
		{
			$result = $this->SQL->query("SELECT * FROM system");
			$system = $result->fetch_object();
			// Construct the SQL query
			$query = "SELECT (UNIX_TIMESTAMP(s.instime) + ".GMT_OFFSET.") * 1000, $field 
						FROM line_stats s
							LEFT JOIN `lines` l ON l.id = s.line_id
						WHERE $where AND instime > '".$from->format('Y-m-d H:i:s')."' AND instime < '".$to->format('Y-m-d H:i:s')."';";
			// execute query
			$result = $this->SQL->query($query);
			// Calculate step in miliseconds
			$step = $system->stats_interval * 1000;
			$maxStep = $system->stats_interval * 4000;
			if ($result->num_rows > 2000)
			{
				$a = $result->num_rows / 2000;
				$step *= $a;
				$maxStep *= $a;
			}
			$timer = 0;
			while ($row = $result->fetch_row())
			{
				// if the difference is smaller than the step, then ignore this row
				if ($row[0] < $timer + $step)
				{
					continue;
				}
				if (($row[0] > $timer + $maxStep) && ($timer))
				{
					$data[] = array(
						$timer + $maxStep,
						null
					);
				}
				$timer = $row[0];
				// Add this row to the JSON object
				$data[] = $row;
			}
		}
		if ($type == 2)
		{
			$subQuery = "SELECT ROUND(AVG($p), 3) AS p, DAY(instime) AS d, MONTH(instime) AS m, YEAR(instime) AS y, HOUR(instime) AS h, s.line_id, l.device_id
				FROM line_stats s
					LEFT JOIN `lines` l ON l.id = s.line_id 
					LEFT JOIN devices d ON d.id = l.device_id
				WHERE $where AND instime > '".$from->format('Y-m-d H:i:s')."' AND instime < '".$to->format('Y-m-d H:i:s')."'
				GROUP BY y, m, d, h, line_id";
			
			$query = "SELECT SUM(p), d, m, y, h
				FROM ($subQuery) AS q
				GROUP BY $group";
			
			// execute query
			//echo $query; exit(0);
			$result = $this->SQL->query($query);
			while ($row = $result->fetch_row())
			{
				switch($offset)
				{
					case 'PT1H':
						// Hourly
						// From 00 mins and 00 secs on the selected hour
						$date = DateTime::createFromFormat('d.m.Y H:i:s', $row[1].'.'.$row[2].'.'.$row[3].' '.$row[4].':00:00');
						break;
					case 'P1D':
						// daily
						// From 00:00:00 on the selected day
						$date = DateTime::createFromFormat('d.m.Y H:i:s', $row[1].'.'.$row[2].'.'.$row[3].' 00:00:00');
						break;
					case 'P1M':
						// monthly
						// From 1st day on the selected month
						$date = DateTime::createFromFormat('d.m.Y H:i:s', '01.'.$row[2].'.'.$row[3].' 00:00:00');
						break;
					case 'P1Y':
						// yearly
						// From 1st anuary on the selected year
						$date = DateTime::createFromFormat('d.m.Y H:i:s', '01.01.'.$row[3].' 00:00:00');
						break;
				}
				// Remove  day, month year and hour from the row
				array_pop($row);
				array_pop($row);
				array_pop($row);
				array_pop($row);
				
				// add the date in the begining of the row
				array_unshift($row, ($date->getTimestamp() + GMT_OFFSET) * 1000);

				// Add this row to the JSON object
				// Whe are drawing a bar. To draw a vertical line, we need to start from 0
				$data[] = array(
					$row[0] - 1,
					0
				);
				$data[] = $row;
				// Horizontal line for 1 day, month or year
				$date->add(new DateInterval($offset));
				$row[0] = ($date->getTimestamp() + GMT_OFFSET) * 1000;
				$data[] = $row;
				// Vertical line to 0. The bar is done.
				$data[] = array(
					$row[0] + 1,
					0
				);
			}
		}
		
		echo json_encode($data);
	}
}
?>