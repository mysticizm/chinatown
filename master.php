<?php
require_once("config.php");

function GetSysVars($SQL)
{
	$result = $SQL->query("SELECT * FROM `system` WHERE id = 1");
	$system = $result->fetch_object();
	$result->free();
	return $system;
}

function sendStatsRequests($SQL)
{
	$offset = GMT_OFFSET / (60 * 60);
	$sunrise = date_sunrise ( time(), SUNFUNCS_RET_TIMESTAMP, 49.279742, -123.103923, 96, $offset) - 20 * 60;
	$sunset =  date_sunset ( time(), SUNFUNCS_RET_TIMESTAMP, 49.279742, -123.103923, 264, $offset) + 20 * 60;

	if ((time() < $sunrise) || (time() > $sunset))
	{
		$device = new Device;
		$result = $SQL->query("SELECT address FROM devices WHERE deleted = 0");
		while ($row = $result->fetch_object())
		{
			$device->properties['address'] = $row->address;
			$device->sendQuery(Commands::getLevels($device->getAddress()));
		}
		$result->free();
		unset($device);
	}
}

function getMissmatch($device, $prop, $address)
{
	$device->properties = $prop;
	$device->loadByAddress($address);
	$resendProps = array();
	foreach ($device->properties as $key => $value)
	{
		if ((strlen($value)) && ($value != $prop[$key]))
		{
			$resendProps[$key] = $value;
		}
	}
	
	return $resendProps;
}

function proceedAnswer($answer, $SQL, $device)
{
	$prop = array();
	$SQL->query('INSERT INTO `log`(i_o, message, send_time) VALUE(3, "'.addslashes($answer->getBinary()).'", NOW())');
	$bytes = $answer->getBytes();
	if (isset($bytes[0]) && ($bytes[0] == '00') && (count($bytes) > 10))
	{
		switch ($bytes[3])
		{
			case 'A0':
			// QUERY_ACTUAL_LEVEL 
				$level = hexdec($bytes[4]);
				$red = hexdec($bytes[8]);
				$blue = hexdec($bytes[9]);
				$green = hexdec($bytes[10]);
				
				$device->SQL->query("UPDATE devices SET level = $level, red = $red, green = $green, blue = $blue, levels_time = CURRENT_TIMESTAMP WHERE address = '".$answer->getAddress()."'");
				
				$prop["power_on_level"] = hexdec($bytes[7]);
				$prop["min_level"] = hexdec($bytes[6]);
				$prop["max_level"] = hexdec($bytes[5]);
				
				$device->setWorktime($answer->getAddress(), $prop["worktime"]);
				$resendProps = getMissmatch($device, $prop, $answer->getAddress());
				
				if (count($resendProps))
				{
					$device->properties = $resendProps;
					$device->properties['address'] = $answer->getAddress();
					$device->sendSettings();
					
					$device->sendFlashQuery();
				}
				break;
				
			case 'ED':
			// QUERY_ALL_FLASH DATA
				$prop["power_on_level"] = hexdec($bytes[1 + 3]);
				$prop["options"] = hexdec($bytes[2 + 3]);
				$prop["min_level"] = hexdec($bytes[3 + 3]);
				$prop["max_level"] = hexdec($bytes[4 + 3]);
				$prop["fade_rate"] = hexdec($bytes[5 + 3]);
				$prop["fade_time"] = hexdec($bytes[6 + 3]);
				
				$prop["groups"] = hexdec($bytes[10 + 3].$bytes[9 + 3].$bytes[12 + 3].$bytes[11 + 3]);
				$prop["groups"] = hexdec($bytes[11 + 3]);
				$prop["groups"] += (int)(hexdec($bytes[12 + 3]) << 8);
				$prop["groups"] += (int)(hexdec($bytes[9 + 3]) << 16);
				$prop["groups"] += (int)(hexdec($bytes[10 + 3]) << 24);
				$prop["worktime"] = hexdec($bytes[13 + 3].$bytes[14 + 3].$bytes[15 + 3].$bytes[16 + 3]);
				
				//$device->loadByAddress($answer->getAddress());
				//$device->loadFrom($prop);
				//$device->update();
				$device->setWorktime($answer->getAddress(), $prop["worktime"]);
				$resendProps = getMissmatch($device, $prop, $answer->getAddress());
				
				if (count($resendProps))
				{
					$device->properties = $resendProps;
					$device->properties['address'] = $answer->getAddress();
					$device->sendSettings();
					if (isset($resendProps['groups']))
					{
						$device->syncGroups($prop["groups"]);
					}
					if (isset($resendProps['options']))
					{
						$device->syncOptions($prop["options"]);
					}
					$device->sendFlashQuery();
				}
				break;
		}
		
		$device->seen($answer->getAddress());
	}
}

$cmd = new Commands;
$cmd->connect();
$SQL = new MyMySQL;
$device = new Device;
$exit = time();
$statsTime = time();
//$SQL->query("DELETE FROM `log` WHERE i_o < 2");
//$SQL->query("UPDATE devices SET active = 1");546
sendStatsRequests($SQL);
$system = GetSysVars($SQL);
$lastAnswer = time() - $system->after_reply;
$lastSend = time() - $system->send_interval;
while (1)  
{
	if (!$cmd->connected)
	{
		$SQL->query("CALL open_alarm(0, 7)");
		$cmd->connect();
		continue;
	}
	else if (time() > $lastAnswer + $system->unplugged_timeout)
	{
		$cmd->disconnect();
		$lastAnswer = time() - $system->after_reply;
		$cmd->connect();
		continue;
	}
	else
	{
		$SQL->query("CALL close_alarm(0, 7)");
	}
	
	if (time() >= $statsTime + $system->stats_interval)
	{
		sendStatsRequests($SQL);
		$statsTime = time();
		$system = GetSysVars($SQL);
	}
	
	$receivedAnswers = $cmd->Receive();
	foreach ($receivedAnswers as $key => $answer)
	{
		$lastAnswer = time();
		proceedAnswer($answer, $SQL, $device);
	}
	
	if ((time() >= $lastAnswer + $system->after_reply) && (time() >= $lastSend + $system->send_interval))
	{
		$result = $SQL->query("SELECT * FROM `log` WHERE i_o < 2 AND send_after <= NOW() ORDER BY send_after LIMIT 1");
		while ($command = $result->fetch_object())
		{
			$address = $cmd->Send($command->message);
			$lastSend = time();
			$SQL->query("UPDATE `log` SET send_time = NOW(), i_o = 2 WHERE id = ".$command->id);
			$SQL->query("DELETE FROM `log` WHERE i_o < 2 AND message = '".addslashes($command->message)."'");
			
			if ($command->i_o)
			{
				$timeResult = $SQL->query("SELECT IFNULL(reply_timeout, ".$system->reply_timeout.") FROM devices WHERE address = '$address'");
				if ($timeResult->num_rows)
				{
					$reply_t = $timeResult->fetch_row();
					$reply_timeout = $reply_t[0];
				}
				else
				{
					$reply_timeout = $system->reply_timeout;
				}
				$received = false;
				for ($ii = 0; ($ii < $reply_timeout * 4) && (!$received); $ii++)
				{
					usleep(249000);
					foreach ($cmd->Receive() as $key => $answer)
					{
						$lastAnswer = time();
						proceedAnswer($answer, $SQL, $device);
						if (($address == $answer->getAddress()) && ($answer->getFirstByte() == '00'))
						{
							$received = true;
						}
						else
						{
							//echo "Received ".$answer->getAddress()." \n";
						}
					}
				}
				if (!$received)
				{
					//echo "Resend $address \n";
					$result1 = $SQL->query('SELECT 0 AS retranslator FROM devices WHERE deleted = 0 AND address = "'.$address.'"');
					if ($result1->num_rows)
					{
						$dev = $result1->fetch_object();
						$rbyte = hexdec($dev->retranslator.'0') | ord($command->message[1]);
						
						if ($command->message[1] == chr($rbyte))
						{
							$command->message[1] == chr(0x00);
							$SQL->query('INSERT INTO `log`(i_o, message, send_after) VALUE(1, "'.addslashes($command->message).'", ADDDATE(NOW(), INTERVAL '.$system->retry_after.' SECOND))');
							$SQL->query('UPDATE devices SET active = 0 WHERE address = "'.$address.'"');
						}
						else
						{
							$command->message[1] = chr($rbyte);
							$SQL->query('INSERT INTO `log`(i_o, message, send_after) VALUE(1, "'.addslashes($command->message).'", ADDDATE(NOW(), INTERVAL -1 HOUR))');
						}
					}
					$result1->free();
				}
			}
			else
			{
				usleep(100000);
			}
		}
		$result->free();
	}
}
?>