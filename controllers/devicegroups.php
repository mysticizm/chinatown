<?php
class DeviceGroups extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('DeviceGroup');
	}
	
	function defaultAction()
	{
		$this->showList();
	}
	
	function showList()
	{
		$groups = $this->_model->select("id, name", "groups", "1");
		$this->_template->setView('groups');
		$array = array(
			"title"=>$this->lang['Edit groups'],
			"groups"=>$groups
		);
	    $this->_template->render($array);
	}
	
	function edit()
	{
		if (isset($_REQUEST['id']))
		{
			$this->_model->load((int)$_REQUEST['id']);
			$this->_template->setView('group_edit');
			$array = array(
				"group"=>$this->_model->getProperties()
			);
			$this->_template->simpleRender($array);
		}
	}
	
	function delete()
	{
		if (isset($_REQUEST['id']))
		{
			$this->_model->load((int)$_REQUEST['id']);
			$this->_model->properties['name'] = "";
			$this->_model->update();
		}
	}
	
	function save()
	{
		$this->_model->loadFrom($_POST);
		$this->_model->update();
		$this->showList();
	}
}
?>