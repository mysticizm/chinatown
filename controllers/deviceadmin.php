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
		if (isset($_POST['groups']))
		{
			$this->_model->properties['groups'] = 0;
			foreach ($_POST['groups'] as $key => $value)
			{
				$this->_model->properties['groups'] += $value;
			}
		}
		
		if (isset($_POST['options']))
		{
			$this->_model->properties['options'] = 0;
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
		$id = (int)$_REQUEST['id'];
		$scene = (int)$_REQUEST['scene'];
		
		$this->_model->load($id);
		
		$this->_model->fastCommand(Commands::setColor($this->_model->getAddress(), $scene));
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
				$where = "(groups >> $shift) & 1";
				$address = chr(0).chr((1 << 7) + $shift);
			}
			else
			{
				$address = chr(0).chr(0xFE);
			}
			$this->_model->update($where);
			$this->_model->sendSettings($address);
			
			if ($_POST['red'] && $_POST['green'] && $_POST['blue'])
			{
				$this->_model->setColor((int)$_POST['red'], (int)$_POST['green'], (int)$_POST['blue'], (int)$_POST['level'], $address);
			}
			else
			{
				$this->_model->fastCommand(Commands::setCurrentLevel($address, $_POST['level']));
			}
		}
		
		$this->showList();
	}
}
?>