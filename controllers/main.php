<?php
class Main extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('device');
	}
	
	function defaultAction()
	{
		if ($_SESSION["access"] >= 100)
		{
			$this->home();
		}
		else
		{
			header("location: index.php?content=users&action=login");
		}
	}
	
	function home()
	{
		$result = $this->_model->select("id, name, lat, lng");
		$devices = array();
		while ($row = $result->fetch_object())
		{
			$devices[] = $row;
		}
		$overlays = array();
		$this->_template->setView('home');
		$array = array(
			"title"=>$this->lang['Observe title'],
			"devices"=>$devices,
			"overlays"=>$overlays,
			"group"=>0,
			"googleMaps"=>1
		);
	    $this->_template->render($array);
	}
}
?>