<?php
class Menu
{
	public $links = array();
	public $SQL;
	
	function __construct()
	{
		$this->SQL = new MyMySQL;
		$access = $_SESSION['access'];
		$result = $this->SQL->query("SELECT id, name, link FROM menu WHERE min_access <= $access AND max_access >= $access ORDER BY `order`");
		while ($row = $result->fetch_object())
		{
			$this->links[] = $row;
		}
	}
}
?>