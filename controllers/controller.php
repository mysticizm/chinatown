<?php
class Controller {
    protected $_action;
    protected $_model;
    protected $_template;
	protected $_controller;
	public $SQL;
	public $lang;

    function __construct($action='', $locale = LOCALE) 
	{
		if(!method_exists($this, $action))
		{			
			//echo $action." does not ezist";
			$action = 'defaultAction';
		}   
		$this->_controller = get_class($this);
        $this->_action = $action;
		$this->SQL = new MyMySQL;
		require(GLOBAL_PATH_LANG.$locale.'/lang.php');
		if (file_exists(GLOBAL_PATH_LANG.$locale.'/'.strtolower($this->_controller).'.php'))
		{
			require(GLOBAL_PATH_LANG.$locale.'/'.strtolower($this->_controller).'.php');
		}
		$this->lang = $lang;
		$this->_template = new View($this->lang);
		$this->_template->_controller = $this->_controller;
    }
	
	function setModel($model_name)
	{
		$this->_model = new $model_name;
	}
	
	function checkAccess()
	{
		$result = $this->SQL->query("SELECT min_access, max_access FROM controllers WHERE name = '".$this->_controller."'");
		if ($result->num_rows)
		{
			$row = $result->fetch_object();
			$min_access = $row->min_access;
			$max_access = $row->max_access;
		}
		else
		{
			$this->SQL->query("INSERT INTO controllers(name) VALUE('".$this->_controller."')");
			$min_access = 0;
			$max_access = 1000;
		}
		
		return (($min_access <= $_SESSION["access"]) && ($max_access >= $_SESSION["access"]));
	}
	
	function doAction()
	{
		if ($this->checkAccess())
		{
			$action = $this->_action;
			$this->$action();
			return 1;
		}
		else
		{
			$this->accessDenied();
			return 0;
		}
	}
	
	function getMenuID()
	{
		$result = $this->SQL->query("SELECT menu_id FROM controllers WHERE name = '$this->_controller'");
		$row = $result->fetch_object();
		
		return $row->menu_id;
	}
	
	function defaultAction()
	{
		$this->notFound();
	}
	
	
	function notFound()
	{
		$this->_template->setView('message');
		$array = array(
			"title"=>$this->lang['Error 404'],
			"message"=>$this->lang['Page not found']
		);
	    $this->_template->render($array);
	}
	
	function accessDenied()
	{
		$this->_template->setView('message');
		$array = array(
			"title"=>$this->lang['Access denied'],
			"message"=>$this->lang['Access denied message']
		);
	    $this->_template->render($array);
	}
}
?>