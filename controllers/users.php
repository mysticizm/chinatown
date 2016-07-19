<?php
class Users extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
		$this->setModel('user');
	}
	
	function checkUser()
	{
		if ($this->_model->checkUser(addslashes($_REQUEST["user_name"]), addslashes($_REQUEST["user_pass"])))
		{
			$main = new Main('home');
			$main->doAction();
		}
		else
		{
			$this->login($this->lang['Invalid userpass']);
		}
	}
	
	function login($error = '')
	{
		$this->_template->setView('login_form');
		$array = array(
			"title"=>$this->lang['User log in'],
			"error"=>$error
		);
	    $this->_template->render($array);
	}
	
	function logOut()
	{
		$this->_model->clearSession();
		$this->login($this->lang['Log out success']);
	}
	
	function changePassword()
	{
		if ((isset($_POST['password'])) && (isset($_POST['new_pass'])))
		{
			$query = "SELECT user_pass = SHA('".addslashes($_POST['password'])."') FROM users WHERE id = ".$_SESSION['user'];
			$result = $this->SQL->query($query);
			$row = $result->fetch_row();
			if ($row[0])
			{
				$this->SQL->query("UPDATE users SET user_pass = SHA('".addslashes($_POST['new_pass'])."') WHERE id = ".$_SESSION['user']);
				echo 'OK';
			}
			else
			{
				echo $lang['Invalid old pass'];
			}
		}
	}
}
?>