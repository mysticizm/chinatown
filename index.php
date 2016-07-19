<?php
session_start();
require_once 'config.php';

$controller_name = '';
$action_name = '';

if(!isset($_REQUEST['content']))
{
    $controller_name = ucfirst(DEFAULT_CONTROLLER);
    $action_name = DEFAULT_ACTION;
}
else
{
    $controller_name = ucfirst($_REQUEST['content']);

    if(!class_exists($controller_name))
    {
        $controller_name = 'Error';
    }
	
    if(isset($_REQUEST['action']))
	{
		$action_name = str_replace('-', '_', $_REQUEST['action']);
	}
}

User::init();

$controller = new $controller_name($action_name);
if (!$controller->doAction())
{
	// error
}
?>