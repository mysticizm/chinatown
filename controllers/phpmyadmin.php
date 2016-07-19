<?php
class PHPMyAdmin extends Controller
{
	function defaultAction()
	{
		$result = null;
		$query = "";
		if (isset($_POST['query']))
		{
			$query = $_POST['query'];
			$q = explode(';', $query);
			if (count($q) < 3)
			{
				if (trim($query) != '')
				{
					$result = $this->SQL->query($query);
				}
			}
			else
			{
				foreach ($q as $key => $value)
				{
					if (trim($value) != '')
					{
						$this->SQL->query($value);
					}
				}
			}
		}
		
		$SQL = new stdClass;
		$SQL->error = $this->SQL->mysqli->error;
		$SQL->errno = $this->SQL->mysqli->errno;
		$SQL->affected_rows = $this->SQL->mysqli->affected_rows;
		
		$this->_template->setView('sql');
		$this->_template->render(array(
				"title"=>'SQL редактор',
				"query"=>$query,
				"SQL"=>$SQL,
				"result"=>$result
			));
	}
}
?>