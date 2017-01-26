<?php
class DeviceAdmin extends Devices
{	
	function newDevice()
	{
		if ($_SESSION['access'] >= 800)
		{
			if (isset($_REQUEST['parent_id']))
			{
				$parent = (int)$_REQUEST['parent_id'];
				$this->_model->load($parent);
				$this->_model->properties['parent_id'] = $parent;
				$this->_model->properties['id'] = null;
				$this->_model->properties['address'] = "";
				$this->_model->properties['name'] .= " slave";
			}
			$this->_template->setView('device_new');
			$array = array(
				"title"=>$this->lang['New device'],
				"device"=>$this->_model->getProperties(),
				"googleMaps"=>1
			);
			$this->_template->render($array);
		}
	}
	
	function delete()
	{
		if (($_SESSION['access'] >= 800) && (isset($_REQUEST['id'])))
		{
			//$this->_model->delete((int)$_REQUEST['id']);
			$this->SQL->query("UPDATE devices SET deleted = 1, address = null WHERE id = ".(int)$_REQUEST['id']);
			$this->SQL->query("UPDATE devices SET deleted = 1, address = null WHERE parent_id = ".(int)$_REQUEST['id']);
			echo (int)$_REQUEST['id'];
		}
	}
	
	private function groupsFromPost()
	{
		$this->_model->properties['groups'] = 0;
		if (isset($_POST['groups']))
		{
			
			foreach ($_POST['groups'] as $key => $value)
			{
				$this->_model->properties['groups'] += $value;
			}
		}
		
		$this->_model->properties['options'] = 0;
		if (isset($_POST['options']))
		{
			
			foreach ($_POST['options'] as $key => $value)
			{
				$this->_model->properties['options'] += $value;
			}
		}
	}
	
	function save()
	{
		if ($_POST['id'])
		{
			$this->_model->load((int)$_POST['id']);
			$properties = $this->_model->properties;
			$this->_model->loadFrom($_POST);
			
			if ($_SESSION['access'] < 800)
			{
				$this->_model->properties['address'] = $properties['address'];
			}
			
			$this->groupsFromPost();
			
			$this->_model->syncGroups($properties['groups']);
			$this->_model->syncOptions($properties['options']);
			
			foreach ($properties as $key=>$value)
			{
				if (($key != 'id') && ($key != 'address') && ($value == $this->_model->properties[$key]))
				{
					unset($this->_model->properties[$key]);
				}
			}
			$this->_model->update();
			$this->_model->sendSettings();
			$this->_model->sendFlashQuery();
			$this->showList();
		}
		else
		{
			$this->_model->loadFrom($_POST);
			$this->groupsFromPost();
			
			$this->_model->syncGroups($properties['groups']);
			$this->_model->syncOptions($properties['options']);
			
			$this->_model->add();
			$this->_model->sendSettings();
			$this->_model->sendFlashQuery();
			echo 'OK';
		}
	}
	
	function setColor()
	{
		$id = (int)$_REQUEST['id'];
		$red = (int)$_REQUEST['red'];
		$green = (int)$_REQUEST['green'];
		$blue = (int)$_REQUEST['blue'];
		$level = (int)$_REQUEST['level'];
		
		$this->_model->load($id);
		
		$this->_model->setColor($red, $green, $blue, $level);
	}
	
	function setPower()
	{
		$id = (int)$_REQUEST['id'];
		$level = (int)$_REQUEST['level'];
		
		$this->_model->load($id);
		
		$this->_model->fastCommand(Commands::setCurrentLevel($this->_model->getAddress(), $level));
		$this->_model->fastQuery(Commands::getLevels($this->_model->getAddress()));
	}
	
	function setScene()
	{
		$scene = (int)$_REQUEST['scene'];
		
		if (isset($_REQUEST['id']))
		{
			$id = (int)$_REQUEST['id'];
			
			$this->_model->load($id);
			
			$this->_model->fastCommand(Commands::setColor($this->_model->getAddress(), $scene));
		}
		
		if (isset($_REQUEST['group']))
		{
			$group = (int)$_REQUEST['group'];
			
			if ($group)
			{
                $shift = $group - 1;
                $where = "(1 << $shift) & groups";
				
				$result = $this->SQL->query("SELECT address FROM devices WHERE $where");
				while($row = $result->fetch_assoc())
				{
					$this->_model->properties['address'] = $row['address'];
					$address=$this->_model->getAddress();
					
					$this->_model->fastCommand(Commands::setColor($this->_model->getAddress(), $scene));
				}
			}
			else
			{
				$address = chr(0).chr(0xFE);
				
                $this->_model->fastCommand(Commands::setColor($this->_model->getAddress(), $scene));
			}
		}
	}
	
	function broadcast()
	{
		$this->_template->setView('broadcast');
		$array = array(
			"device"=>$this->_model->getProperties()
		);
		$this->_template->simpleRender($array);
	}
	
	function sendBroadcast()
	{
		$group = (int)$_POST['group'];
		$properties = array();
		foreach ($_POST as $key => $value)
		{
			if ((isset($this->_model->properties[$key])) && ($value != ""))
			{
				$properties[$key] = $value;
			}
		}
		
		if (count($properties) || $_POST['level'])
		{
			$this->_model->properties = $properties;
			$where = "1";

			if ($group)
			{
                $shift = $group - 1;
                $where = "(1 << $shift) & groups";

				if (count($properties))
				{
					$this->_model->update($where);
				}
				
				$result = $this->SQL->query("SELECT address FROM devices WHERE $where");
				while($row = $result->fetch_assoc())
				{
					$this->_model->properties['address'] = $row['address'];
					$address=$this->_model->getAddress();
					
					$this->_model->sendSettings($address);
					if (($_POST['red']) || ($_POST['green']) || ($_POST['blue']))
					{
						$this->_model->setColor((int)$_POST['red'], (int)$_POST['green'], (int)$_POST['blue'], (int)$_POST['level'], $address);
					}
					else
					{
						$this->_model->fastCommand(Commands::setCurrentLevel($address, $_POST['level']));
					}
				}
			}
			else
			{
				$address = chr(0).chr(0xFE);
                $this->_model->sendSettings($address);
				
				if (count($properties))
				{
					$this->_model->update($where);
				}

				if (isset($_POST['red']) && isset($_POST['green']) && isset($_POST['blue']))
				{
					$this->_model->setColor((int)$_POST['red'], (int)$_POST['green'], (int)$_POST['blue'], (int)$_POST['level'], $address);
				}
				else
				{
					$this->_model->fastCommand(Commands::setCurrentLevel($address, $_POST['level']));
				}
			}
		}
		
		$this->showList();
	}
	
	function import()
	{
		$this->SQL->query("TRUNCATE TABLE devices");
		$file = file_get_contents('fixtures.geojson');
		$array = json_decode($file);
		foreach ($array->features as $key => $feature)
		{
			$device = array(
				'left'=> $feature->properties->left,
				'right'=> $feature->properties->right,
				'lat'=> $feature->geometry->coordinates[1],
				'lng'=> $feature->geometry->coordinates[0]
			);
			if ($device['left'])
			{
				$this->_model->properties['name'] = "L ".$device['left'];
				$addr = $device['left'];
				if ($addr >= 0x80)
				{
					echo $addr.' -> '.((0x0F80 & $addr) * 2).' + '.(0x007F & $addr);
					$addr = ((0x0F80 & $addr) * 2) + (0x007F & $addr);
				}
				echo ' = '.$addr;
				$address = dechex($addr);
				for ($i = strlen($address); $i < 4; $i++)
				{
					$address = '0'.$address;
				}
				echo ' = '.$address.'<br/>';
				$this->_model->properties['address'] = $address;
				$this->_model->properties['lat'] = $device['lat'] + 0.00001;
				$this->_model->properties['lng'] = $device['lng'] - 0.00001;
				
				$this->_model->add();
				
				if($this->SQL->mysqli->error)
				{
					echo $device['left']."<br/>";
				}
			}
			if ($device['right'])
			{
				$this->_model->properties['name'] = "R ".$device['right'];
				$addr = $device['right'];
				if ($addr >= 0x80)
				{
					echo $addr.' -> '.((0x0F80 & $addr) * 2).' + '.(0x007F & $addr);
					$addr = ((0x0F80 & $addr) * 2) + (0x007F & $addr);
				}
				echo ' = '.$addr;
				$address = dechex($addr);
				for ($i = strlen($address); $i < 4; $i++)
				{
					$address = '0'.$address;
				}
				echo ' = '.$address.'<br/>';
				$this->_model->properties['address'] = $address;
				$this->_model->properties['lat'] = $device['lat'] - 0.00001;
				$this->_model->properties['lng'] = $device['lng'] + 0.00001;
				
				$this->_model->add();
				
				if($this->SQL->mysqli->error)
				{
					echo $device['right']."<br/>";
				}
			}
		}
	}
}
?>