<?php
class db
{
	//PRIAVET VARIABLE
	private $host;
	private $user;
	private $pass;
	private $db;
	private $link;
	
	//Constructor
	public function __construct()
	{
		date_default_timezone_set('Asia/Kolkata');
		$this->connectDB();
	}
	
	//Destructor
	public function __destruct()
	{
		//mysql_close($this->link);
	}
	
	//Setting Parameters
	private function setParameters()
	{
		/*
		$this->host='localhost';
		$this->user='root';
		$this->pass='rsv@000000';
		$this->db='elgo';		
		*/
		
		//Web
		$this->host='localhost';
		$this->user='livetips';
		$this->pass='Gwalior_123!';
		//$this->pass='';
		$this->db='livetips_elgo';
		
		
	}
	
	//Database connection
	private function connectDB()
	{
		$this->setParameters();	//Setting up parameters
		
		if(!$this->link)
		{
			$this->link=mysql_connect($this->host,$this->user,$this->pass) or die(mysql_error());
			mysql_select_db($this->db,$this->link) or die(mysql_error());
		}
	}
}
?>