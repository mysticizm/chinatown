<?php
require_once("config_private.php");

define ("PROJECT_DIRECTORY", $PROJECT_DIRECTORY);

$GLOBAL_PATH = (getcwd().'/');

define('GLOBAL_PATH', $GLOBAL_PATH);

define('GLOBAL_PATH_CONTROLLERS',GLOBAL_PATH.'controllers/');
define('GLOBAL_PATH_VIEWS',GLOBAL_PATH.'html/');
define('GLOBAL_PATH_CLASSES',GLOBAL_PATH.'classes/');
define('GLOBAL_PATH_LANG',GLOBAL_PATH.'lang/');
define('IMAGE_DIR', PROJECT_DIRECTORY.'images/');
define('IMAGE_PATH', GLOBAL_PATH.'images/');
define('DUMP_URL',PROJECT_DIRECTORY.'dump/');
define('DUMP_DIR',GLOBAL_PATH.'dump/');

define('DEFAULT_CONTROLLER','Main');
define('DEFAULT_ACTION','defaultAction');

define('DEFAULT_LOCALE', 'en');

define("ENCODING", "utf8"); 

function __autoload($class_name)
{
	if(file_exists(GLOBAL_PATH_CONTROLLERS.strtolower($class_name).'.php'))
	{
		require_once GLOBAL_PATH_CONTROLLERS.strtolower($class_name).'.php';
	}
	else if(file_exists(GLOBAL_PATH_CLASSES.strtolower($class_name).'.php'))
	{
		require_once GLOBAL_PATH_CLASSES.strtolower($class_name).'.php';
	}
	else 
	{
		//echo 'CLASS "'.$class_name.'" does not exist! <br />';
	}
}

define('CURRENT_TIME_ZONE','America/Vancouver');
date_default_timezone_set(CURRENT_TIME_ZONE);	

define('CURRENT_DATE', date('d.m.Y'));

// Get what is the offset from GMT time in seconds.
// We need this value, because the jQuery Flot library interprets all timestamps as GMT timestamps
$Sofia = new DateTimeZone(CURRENT_TIME_ZONE);
$now = new DateTime("now");
$timeOffset = $Sofia->getOffset($now);
define('GMT_OFFSET', $timeOffset);
?>