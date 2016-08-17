<?php      

class MyMySQL extends formatSQL 
{
	public $mysqli;
	// Hold an instance of the class
    private static $instance;
	

    // The singleton method
    public static function singleton() 
    {
        if (!isset(self::$instance)) {
            self::$instance = new mysqli(HOST, USER, PASSWORD);
			if (mysqli_connect_errno())
			{
				echo("Failed to connect, the error message is : ".mysqli_connect_error());
				exit();
			}
        }

        return self::$instance;
    }
	
	// Prevent users to clone the instance
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
	
	
	function __construct() 
	{
		$this->mysqli = $this->singleton();
		$this->mysqli->select_db(DATABASE);
		$this->mysqli->query("set names ".ENCODING);
	}
	
	

	function __destruct() 
	{
		//$this->mysqli->close();
	}
	
	function query($query)
	{
		$result = $this->mysqli->query($query);
		$this->check_error($query, $result);
		return 	$result;
	}
	
	function multi_query($query)
	{
		$done = $this->mysqli->multi_query($query);
		$result[0] = $this->mysqli->store_result();
		$this->check_error($query, $done);
		for ($i = 1; $this->mysqli->more_results(); $i++)
		{
			$this->mysqli->next_result();
			$result[$i] = $this->mysqli->store_result();
			$this->check_error($query, $done);
		}
		
		return 	$result;
	}
	
	function check_error($query, $done)
	{
		//$server = $_SERVER["SERVER_NAME"];
		$server = "localhost";
		if((!$done) && ($server == "localhost"))
		{
			printf(date('d.m.Y H:i:s')." Error: %s<br> <b>%s</b><br>\n", $this->mysqli->error, $query);
		}
	}
	
	function post_insert($into, $keys, $post = array())
	{
		if ($post == array())
		{
			$post = $_POST;
		}
		$table_keys = implode(",", $keys);
		$query = "INSERT INTO ".$into."(".$table_keys.") ";
		$values = array();
		foreach ($keys as $key)
		{
			if ($post[$key] === '')
			{
				$postval = 'null';
			}
			else if ((($val_no_quote = str_replace("#'no'#", "", $post[$key])) != $post[$key]) || ($post[$key] == 'null'))
			{
				$postval = $val_no_quote;
			}
			else if (!get_magic_quotes_gpc())
			{
				$postval = "'".addslashes($post[$key])."'";
			}
			else
			{
				$postval = "'".$post[$key]."'";
			}
			array_push($values, $postval);
		}
		$vals = implode(",", $values);
		$query .= "VALUE(".$vals.");";
		$this->query($query);
		$id = $this->mysqli->insert_id;
		return $id;
	}
	
	function post_update($table, $keys, $post = array(), $where = null)
	{
		if ($post == array())
		{
			$post = $_POST;
		}
		if (!$where)
			$where = "id = ".$post["id"];
		$table_keys = implode(",", $keys);
		$values = array();
		foreach ($keys as $key)
		{
			if (str_replace("date", "", $key) != $key)
			{
				if ($key != "date_created")
				{
					$postval = $this->bgdate_to_date($post[$key]);
				}
				else
				{
					$postval = $this->bgdatetime_to_datetime($post[$key]);
				}
			}
			else if (($post[$key] === '') || ($post[$key] === "") || $post[$key] === NULL)
			{
				$postval = 'null';
			}
			else if (($val_no_quote = str_replace("#'no'#", "", $post[$key])) != $post[$key])
			{
				$postval = $val_no_quote;
			}
			else if (!get_magic_quotes_gpc())
			{
				$postval = "'".addslashes($post[$key])."'";
			}
			else
			{
				$postval = "'".$post[$key]."'";
			}
			$next = "`".$key."` = ".$postval."";
			array_push($values, $next);
		}
		$vals = implode(",", $values);
		$query = "UPDATE $table SET $vals WHERE $where;";
		$this->query($query);
	}
	
	function select($from, $keys, $id, $setPost = false)
	{
		$p_keys = $this->prepare_for_select($keys);
	
		$table_keys = implode(",", $p_keys);
		$query = "SELECT ".$table_keys." FROM ".$from." WHERE id = ".$id;
		$result = $this->query($query);
		$row = $result->fetch_assoc();
		
		if ($setPost)
		{
			foreach ($row as $key=>$value)
			{
				$_POST[$key] = $value;
			}
		}
		
		return $row;
	}
}
?>