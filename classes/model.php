<?php
abstract class Model
{
	public $tableName;
	public $SQL;

	function __construct()
	{
		$this->SQL = new MyMySQL;
		if ((isset($_GET["page"])) && ($_GET["page"] >= 1))
		{
			$this->page = (int)$_GET["page"];
		}
		else
		{
			$this->page = 1;
		}
	}

	function add()
	{
		$SQL = $this->SQL;
		$this->onSave();
		unset($this->properties["id"]);
		$vars = $this->properties;
		$this->properties["id"] = $SQL->post_insert($this->tableName, array_keys($vars), $vars);
		
		return $this->properties["id"];
	}

	function update($where = null)
	{
		$SQL = $this->SQL;
		$this->onSave();
		$vars = $this->properties;
		$SQL->post_update($this->tableName, array_keys($vars), $vars, $where);
	}
	
	function save()
	{
		if ($this->properties["id"])
		{
			$this->update();
		}
		else
		{
			$this->add();
		}
		
		return $this->properties["id"];
	}

	function onSave()
	{

	}

	function load($id = 0)
	{
		$SQL = $this->SQL;
		if ((!$id) && (isset($_REQUEST["id"])))
		{
			$id = (int)$_REQUEST["id"];
		}
		
		if ($id)
		{
			$keys = array_keys($this->properties);
			$properties = $SQL->select($this->tableName, $keys, $id);
			if ($properties)
			{
				$this->properties = $properties;
			}
		}
	}
	
	function count($where = 1, $query = "SELECT COUNT(id) AS count FROM #table WHERE ")
	{
		$SQL = $this->SQL;
		$query .= $where;
		$query = str_replace("#table", $this->tableName, $query);
		$result = $SQL->query($query);
		$row = $result->fetch_row();
		
		return $row[0];
	}
	
	function select($fields, $tables = "#table", $where = "deleted = 0", $order = "id", $limit = null)
	{
		$SQL = $this->SQL;
		$fields_array = explode(',', $fields);
		if (count($fields_array))
		{
			foreach ($fields_array as $key => $value)
			{
				$value = trim($value);
				if ($value == str_replace('(', '', $value))
				{
					$value = $SQL->date_to_bgdate($value);
				}
				$fields_array[$key] = $value;
			}
			$fields = implode(', ', $fields_array);	
		}
		$query = "SELECT $fields FROM $tables WHERE $where ORDER BY $order";
		if ($limit)
		{
			$query .= " LIMIT $limit";
		}
		$query = str_replace("#table", $this->tableName, $query);
		$result = $SQL->query($query);

		return $result;
	}
	
	function getProperties()
	{
		$data = new stdClass();
		foreach ($this->properties as $key => $value)
		{
			$data->$key = str_replace('"', '&quot;', $value);
		}
		
		return $data;
	}
	
	function loadFrom($array)
	{
		foreach ($this->properties as $key => $value)
		{
			if (isset($array[$key]))
			{
				$this->properties[$key] = $array[$key];
			}
		}
	}
	
	function delete($id)
	{
		$this->SQL->query("UPDATE $this->tableName SET deleted = 1 WHERE id = $id");
	}
	
	function restore($id)
	{
		$this->SQL->query("UPDATE $this->tableName SET deleted = 0 WHERE id = $id");
	}
}

?>