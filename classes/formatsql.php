<?php      

class formatSQL 
{
	function quote_key($key)
	{
		if (str_replace('`', "", $key) == $key)
		$key =  '`'.$key.'`';
		return $key;
	}

	function quote_keys($keys)
	{
		for ($i = 0; isset($keys[$i]); $i++)
		{
			$keys[$i] = $this->quote_key($keys[$i]);
		}
		return $keys;
	}
	
	function prepare_for_select($keys)
	{
		$keys = $this->quote_keys($keys);
		for ($i = 0; isset($keys[$i]); $i++)
		{
			if ($keys[$i] == "`date_created`")
			{
				$keys[$i] = $this->datetime_to_bgdatetime($keys[$i]);
			}
			else
			{
				$keys[$i] = $this->date_to_bgdate($keys[$i]);
			}
		}
		
		return $keys;
	}
	
	function date_to_bgdate($key, $as = null)
	{
		if (!$as)
			$as = $key;
		if (str_replace("date", "", $key) != $key)
			$key = 'DATE_FORMAT('.$key.', "%d.%m.%Y") as '.$as;
		return $key;
	}
	
	function datetime_to_bgdatetime($key, $as = null)
	{
		if (!$as)
			$as = $key;
		$key = 'DATE_FORMAT('.$key.', "%d.%m.%Y %H:%i:%s") as '.$as;
		return $key;
	}
	
	function bgdate_to_date($value)
	{
		if (($value != '') && ($value != '00.00.0000'))
		{
			$value = 'STR_TO_DATE("'.$value.'", "%d.%m.%Y")';
			return $value;
		}
		else
		{
			return 'null';
		}
	}
	
	function bgdatetime_to_datetime($value)
	{
		if (($value != '') && ($value != '00.00.0000 00:00:00'))
		{
			$value = 'STR_TO_DATE("'.$value.'", "%d.%m.%Y %H:%i:%s")';
			return $value;
		}
		else
		{
			return 'null';
		}
	}
	
	function prd_id_to_name($value, $as = null)
	{
		$value = $this->quote_key($value);
		if (!$as)
			$as = $value;
		$value = "get_prd_name($value) as $as";
		return $value;
	}
	
	function nom_to_name($value, $as = null)
	{
		$value = $this->quote_key($value);
		if (!$as)
			$as = $value;
		$value = "nom($value) as $as";
		return $value;
	}
}