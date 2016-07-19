<?php

class Commands
{
	public $serial;
	private $answer;
	private $flagMessage = true;
	public $connected = false;
	
	function connect()
	{
		// Initialize serial
		$pluggedDevices = glob('/dev/ttyACM*');
		if (count($pluggedDevices))
		{
			$this->serial = new phpSerial;

			$this->serial->deviceSet($pluggedDevices[0]);
			
			$this->serial->confBaudRate(9600);
			$this->serial->confParity("none");
			$this->serial->confCharacterLength(8);
			$this->serial->confStopBits(1);
			$this->serial->confFlowControl("none");
			
			$cmd = "ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon min 1 time 5 -iexten -echo -echoe -echok -echoctl -echoke";
			shell_exec('stty -F '.$pluggedDevices[0].' '.$cmd);
			
			if ($this->serial->deviceOpen("r+"))
			{
				$this->connected = true;
				echo date('Y.m.d H:i:s')." opened port ".$pluggedDevices[0]."\n";
				
				//$output = shell_exec('stty -F '.$pluggedDevices[0]);
				//echo $output."\n";
				
				/*if ($output[1] != 9600)
				{
					$this->disconnect();
					sleep(1);
				}*/
			}
		}
	}
	
	function disconnect()
	{
		$this->serial->deviceClose();
		$this->connected = false;
		echo date('Y.m.d H:i:s')." closed port.\n";
	}
	
	function __destruct()
	{
		$this->disconnect();
	}
	
	function GetMessageAddress($s_message)
	{
		$address = "";
		for ($i = 1; $i <= 2; $i++)
		{
			$byte = dechex(ord($s_message[$i]));
			if (strlen($byte) == 1)
			{
				$byte = '0'.$byte;
			}
			$address .= strtoupper($byte);
		}
		return $address;
	}
	
	public function Send($s_message)
	{
		$this->serial->sendMessage($s_message, 0);
		//$this->ShowHex($s_message);
		return $this->GetMessageAddress($s_message);
	}
	
	public function Receive()
	{
		$received = array();
		do
		{
			$read = $this->serial->readPort();
			
			for ($i = 0; $i < strlen($read); $i++)
			{
				if ($read[$i] == "\r" || $read[$i] == "\n")
				{
					$this->flagMessage = false;
					
					$answer = new Answer($this->answer);
					$received[] = $answer; 
					$this->answer = "";
					
					//$binary = $answer->getBinary();
					//$this->ShowHex($binary);
				}
				if ($this->flagMessage)
				{
					$this->answer .= $read[$i];
				}
				if ($read[$i] == "@")
				{
					if ($this->flagMessage)
					{
						$this->answer = "";
					}
					$this->flagMessage = true;
				}
			}
		}
		while (strlen($read));
		
		//$answers = preg_split("/\r:\d+@/", $read, -1, PREG_SPLIT_NO_EMPTY);
		return $received;
	}
	
	private function ShowHex($message)
	{
		for ($i = 0; $i < strlen($message); $i++)
		{
			$byte = dechex(ord($message[$i]));
			if (strlen($byte) == 1)
			{
				$byte = '0'.$byte;
			}
			
			echo '<b>'.($i + 1).':</b>'.strtoupper($byte).' ';
		}
		echo "<br/>\n";
	}
	
/*************************************************************************/
	
	static function switchOff($address)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x00);
		$s_message.=chr(0x00);
		$s_message.=chr(0xFF);
		
		return ($s_message);
	}
	
	static function switchOn($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x08);
		$s_message.=chr($p);
		$s_message.=chr(0xF7);
		
		return ($s_message);
	}
	
	static function getVersion($address)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x97);
		$s_message.=chr(0x00);
		$s_message.=chr(0x68);
		
		return ($s_message);
	}
	
	static function addToGroup($address, $group)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		
		$s_message.=chr(($group > 15) ? (0x40 + (int)$group - 16) : (0x60 + (int)$group));
		$s_message.=chr(0);
		$s_message.=chr(($group > 15) ? (0xB0 + 15 - (int)$group + 16) : (0x90 + 15 - (int)$group));
		
		return ($s_message);
	}
	
	static function removeFromGroup($address, $group)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		
		$s_message.=chr(($group > 15) ? (0x50 + (int)$group - 16) : (0x70 + (int)$group));
		$s_message.=chr(0);
		$s_message.=chr(($group > 15) ? (0xA0 + 15 - (int)$group + 16) : (0x80 + 15 - (int)$group));
		
		return ($s_message);
	}
	
	static function getFlashData($address)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0xED);
		$s_message.=chr(0x00);
		$s_message.=chr(0x12);
		
		return ($s_message);
	}
	
	static function setMaxPowerLevel($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x2A);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xD5);
		
		return ($s_message);
	}
	
	static function setMinPowerLevel($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x2B);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xD4);
		
		return ($s_message);
	}
	
	static function setStartPowerLevel($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x2D);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xD2);
		
		return ($s_message);
	}
	
	static function setCurrentLevel($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x28);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xD7);
		
		return ($s_message);
	}
	
	static function setRed($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x21);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xDE);
		
		return ($s_message);
	}
	
	static function setGreen($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x23);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xDC);
		
		return ($s_message);
	}
	
	static function setBlue($address, $p)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x22);
		$s_message.=chr((int)$p);
		$s_message.=chr(0xDD);
		
		return ($s_message);
	}
	
	static function getLevels($address)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0xA0);
		$s_message.=chr(0x00);
		$s_message.=chr(0x5F);
		
		return ($s_message);
	}
	
	static function autoColorChange($address, $set)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x24);
		$s_message.=chr($set ? 0x40 : 0);
		$s_message.=chr(0xDB);
		
		return ($s_message);
	}
	
	static function autoBlending($address, $set)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x25);
		$s_message.=chr($set ? 0x20 : 0);
		$s_message.=chr(0xDA);
		
		return ($s_message);
	}
	
	static function autoDimming($address, $set)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x26);
		$s_message.=chr($set ? 0x80 : 0);
		$s_message.=chr(0xD9);
		
		return ($s_message);
	}
	
	static function autoTime($address, $time)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x29);
		$s_message.=chr($time);
		$s_message.=chr(0xD6);
		
		return ($s_message);
	}
	
	static function setColor($address, $c)
	{
		$s_message=chr(0xFF);
		$s_message.=$address;
		$s_message.=chr(0x10);
		$s_message.=chr($c);
		$s_message.=chr(0xEF);
		
		return ($s_message);
	}
} // end of class


?>
