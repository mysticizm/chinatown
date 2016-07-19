<?php
class Terminal extends Controller
{
	function defaultAction()
	{
		$this->_template->setView('terminal');
		$this->_template->render(array(
				"title"=>$this->lang['Console']
			));
	}
	
	function getLog()
	{
		$last = (int)$_REQUEST['last_id'];
		if ($last)
		{
			$where = "send_time >= (SELECT send_time FROM `log` WHERE id = $last)";
			$stop = false;
		}
		else
		{
			$where = "send_time > ADDDATE(NOW(), INTERVAL -3 MINUTE) ";
		}
		$sent = $this->SQL->datetime_to_bgdatetime("send_time", "sent");
		$query = "SELECT l.id, i_o, message, $sent, IFNULL(user_name, 'System') AS user
					FROM `log` l LEFT JOIN users u ON u.id = l.user
					WHERE $where
					ORDER BY send_time DESC, id DESC";
		$result = $this->SQL->query($query);
		while ($row = $result->fetch_object())
		{
			if ($last && !$stop && ($row->id == $last))
			{
				$stop = true;
			}
			if (!$last || !$stop)
			{
				$class = "new";
				if ($row->i_o == 2)
				{
					$class .= ' blue';
				}
				echo '<li class="'.$class.'">';
				echo '#<span class="log_id">'.$row->id.'</span> ';
				echo $row->sent;
				if ($row->i_o == 2)
				{
					echo ' <span class="red">'.$row->user.'</span> >>> ';
				}
				if ($row->i_o == 3)
				{
					echo ' <<< ';
				}
				$string = "";
				for ($i = 0; $i < strlen($row->message); $i++)
				{
					$byte = dechex(ord($row->message[$i]));
					if (strlen($byte) == 1)
					{
						$byte = '0'.$byte;
					}
					if ($i == 1)
					{
						$byte = '<span class="red">'.$byte;
					}
					if ($i == 2)
					{
						$byte .= '</span>';
					}
					$string .= $byte.' ';
				}
				echo strtoupper($string);
				echo '</li>';
			}
		}
	}
	
	function getHistory()
	{
		$from = $_REQUEST['from_date'];
		if ($_REQUEST['from_time'] != "")
		{
			$from .= ' '.$_REQUEST['from_time'].':00';
		}
		else
		{
			$from .= ' 00:00:00';
		}
		$from = $this->SQL->bgdatetime_to_datetime($from);
		$to = $_REQUEST['to_date'];
		if ($_REQUEST['to_time'] != "")
		{
			$to .= ' '.$_REQUEST['to_time'].':00';
		}
		else
		{
			$to .= ' 23:59:59';
		}
		$to = $this->SQL->bgdatetime_to_datetime($to);
		$sent = $this->SQL->datetime_to_bgdatetime("send_time", "sent");
		$query = "SELECT l.id, i_o, message, $sent, IFNULL(user_name, 'System') AS user
					FROM `log` l LEFT JOIN users u ON u.id = l.user
					WHERE send_time > $from AND send_time < $to
					ORDER BY send_time ASC, id ASC";
		$result = $this->SQL->query($query);
		while ($row = $result->fetch_object())
		{
			$class = "new";
			if ($row->i_o == 2)
			{
				$class .= ' blue';
			}
			echo '<li class="'.$class.'">';
			echo '#<span class="log_id">'.$row->id.'</span> ';
			echo $row->sent;
			if ($row->i_o == 2)
			{
				echo ' <span class="red">'.$row->user.'</span> >>> ';
			}
			if ($row->i_o == 3)
			{
				echo ' <<< ';
			}
			$string = "";
			for ($i = 0; $i < strlen($row->message); $i++)
			{
				$byte = dechex(ord($row->message[$i]));
				if (strlen($byte) == 1)
				{
					$byte = '0'.$byte;
				}
				if ($i == 1)
				{
					$byte = '<span class="red">'.$byte;
				}
				if ($i == 2)
				{
					$byte .= '</span>';
				}
				$string .= $byte.' ';
			}
			echo strtoupper($string);
			echo '</li>';
		}
	}
	
	function getQueue()
	{
		$query = "SELECT * FROM `log` WHERE i_o < 2 ORDER BY send_after";
		$result = $this->SQL->query($query);
		echo '<li>'.$result->num_rows.' '.$this->lang['in queue'].'</li>';
		$j = 0;
		while ($row = $result->fetch_object())
		{
			echo '<li class="blue">';
			echo '<span class="log_id">'.(++$j).'</span>. ';
			echo ' >>> '; 
			$string = "";
			for ($i = 0; $i < strlen($row->message); $i++)
			{
				$byte = dechex(ord($row->message[$i]));
				if (strlen($byte) == 1)
				{
					$byte = '0'.$byte;
				}
				if ($i == 1)
				{
					$byte = '<span class="red">'.$byte;
				}
				if ($i == 2)
				{
					$byte .= '</span>';
				}
				$string .= $byte.' ';
			}
			echo strtoupper($string);
			echo '</li>';
		}
	}
	
	function clearQueue()
	{
		$query = "DELETE FROM `log` WHERE i_o < 2";
		$result = $this->SQL->query($query);
		$this->getQueue();
	}
	
	function send()
	{
		if (isset($_REQUEST['command']))
		{
			$command = preg_replace('/\s*/', '', $_REQUEST['command']);
			$blob = "";
			for ($i = 0; $i < strlen($command); $i += 2)
			{
				$blob .= chr(hexdec(substr($command, $i, 2)));
			}
			if (strlen($blob) >= 6)
			{
				$this->SQL->query('INSERT INTO `log`(message, send_after, user) VALUE("'.addslashes($blob).'", ADDDATE(NOW(), INTERVAL -2 HOUR), '.(isset($_SESSION['user']) ? $_SESSION['user'] : 0).')');	
				echo 'OK';
			}
		}
	}
}
?>