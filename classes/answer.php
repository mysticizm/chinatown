<?php
class Answer
{
	private $binary = "";
	private $bytes = array();
	
	function __construct($hex)
	{
		for ($i = 0; $i < strlen($hex); $i += 2)
		{
			$byte = substr($hex, $i, 2);
			$this->binary .= chr(hexdec($byte));
			$this->bytes[] = $byte;
		}
	}
	
	function getBinary()
	{
		return $this->binary;
	}
	
	function getFirstByte()
	{
		return $this->bytes[0];
	}
	
	function getBytes()
	{
		return $this->bytes;
	}
	
	function length()
	{
		return count($this->bytes);
	}

	function getAddress()
	{
		$address = "";
		
		if ($this->length())
		{
			$address = dechex(hexdec($this->bytes[1].$this->bytes[2]) & 0x0FFF);
			for ($i = strlen($address); $i < 4; $i++)
			{
				$address = '0'.$address;
			}
		}
		
		return strtoupper($address);
	} 

}
?>