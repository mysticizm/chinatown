<?php
class Backup extends Controller
{
	function __construct($action)
	{
		parent::__construct($action);
	}

	function defaultAction()
	{
		$this->listFiles();
	}
	
	function listFiles($message = "")
	{
		$this->_template->setView('backup_list');
		$array = array(
			"title"=>'Backup',
			"message"=>$message,
			"files"=>scandir(DUMP_DIR)
		);
	    $this->_template->render($array);
	}

	function dump()
	{
		$tables = array();
		$result = $this->SQL->query('SHOW TABLES');
		while($row = $result->fetch_row())
		{
		  $tables[] = $row[0];
		}
		$dump = "";
		$console = "";
		foreach ($tables as $key => $table)
		{
			$console .= "Dumping $table... ";
			$dump .= "DROP TABLE IF EXISTS `$table`;\n";
			$result = $this->SQL->query("SHOW CREATE TABLE `$table`");
			$row = $result->fetch_row();
			$dump .= $row[1].";\n\n";
			$result = $this->SQL->query("SELECT * FROM `$table`");
			$console .= $result->num_rows." records. <br/>";
			if ($result->num_rows)
			{
				$dump .= "INSERT INTO `$table` VALUES\n";
				$i = 0;
				while ($row = $result->fetch_row())
				{
					$i++;
					foreach ($row as $rkey => $value)
					{
						if ($value == null)
						{
							$row[$rkey] = 'NULL';
						}
						else
						{
							$row[$rkey] = '"'.addslashes($value).'"';
						}
						
					}
					$dump .= '('.implode(',', $row).')';
					if ($i == $result->num_rows)
					{
						$dump .= ";\n\n\n";
					}
					else if ($i % 100)
					{
						$dump .= ",\n";
					}
					else
					{
						$dump .= ";\n";
						$dump .= "INSERT INTO `$table` VALUES\n";
					}
				}
			}
			else
			{
				$dump .= "\n\n\n";
			}
		}
		file_put_contents(DUMP_DIR.DATABASE.'_'.date('Y-m-d_H-i-s').'.sql', $dump);
		
		$this->listFiles($console);
	}
	
	function delete()
	{
		if (isset($_REQUEST['file']))
		{
			unlink(DUMP_DIR.$_REQUEST['file']);
		}
	}
	
	function restore()
	{
		if (isset($_REQUEST['file']))
		{
			$sql = file_get_contents(DUMP_DIR.$_REQUEST['file']);
			
			$query = explode(";\n", $sql);
			for ($i = 0; $i < count($query) - 1; $i++)
			{
				$this->SQL->query($query[$i]);
			}
			
			$this->listFiles(($i-1).' queries executed.');
		}
	}
}
?>