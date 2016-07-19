<?php
class User extends Model
{
	public $properties = array(
			"id"=>null,
			"first_name"=>"",
			"last_name"=>"",
			"user_name"=>'',
			"e_mail"=>'',
			"role_id"=>null,
		);
		
	function __construct()
	{
		parent::__construct();
		$this->tableName = "users";
	}
	
	function checkUser($user, $pass)
	{
		$result = $this->SQL->query("SELECT id, first_name, last_name, access_level FROM users 
										WHERE user_name = '$user' AND user_pass = SHA('$pass') AND deleted = 0");
		if ($result->num_rows)
		{
			$row = $result->fetch_object();
			$_SESSION['access'] = $row->access_level;
			$_SESSION['user'] = $row->id;
			$_SESSION['name'] = $row->first_name.' '.$row->last_name;
		}
		else
		{
			$this->clearSession();
		}
		
		return $result->num_rows;
	}
	
	static function clearSession()
	{
		$_SESSION['access'] = 0;
		$_SESSION['user'] = 0;
		$_SESSION['name'] = "";
	}
	
	static function init()
	{
		if (!isset($_SESSION['user']))
		{
			self::clearSession();
		}
		
		if (isset($_GET['locale']))
		{
			if (is_dir(GLOBAL_PATH_LANG.$_GET['locale']))
			{
				$_SESSION['locale'] = $_GET['locale'];
			}
		}
		
		if (!isset($_SESSION['locale']))
		{
			$_SESSION['locale'] = DEFAULT_LOCALE;
		}
		
		define('LOCALE', $_SESSION['locale']);
		
		return $_SESSION['user'];
	}
	
	protected function randomPassword()
	{
		$code = "";
		for ($i = 0; $i <= 6; $i++)
		{
			$code .= chr(mt_rand(48, 122));
		}
		
		return $code;
	}
	
	function getAccessByRole($role_id = 0)
	{
		if (!$role_id)
		{
			$role_id = $this->properties['role_id'];
		}
		$role_id = (int)$role_id;
		
		$result = $this->SQL->query("SELECT access_level FROM roles WHERE id = $role_id");
		if ($result->num_rows)
		{
			$row = $result->fetch_row();
			if ($_SESSION['access'] > $row[0])
			{
				$this->properties['access_level'] = $row[0];
			}
		}
		return 0;
	}
}
?>