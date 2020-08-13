<?php
class db
{
	//PRIAVET VARIABLE
	public $host;
	public $user;
	public $pass;
	public $db;
	public $conn;
	
	//Constructor
	public function __construct()
	{
		date_default_timezone_set('Asia/Kolkata');
		$this->connectDB();
	}
	
	//Destructor
	public function __destruct()
	{
		//mysqli_close($conn);
	}
	
	//Setting Parameters
	/*public function setParameters()
	{
		
		
	}*/
	
	//Database connection
	private function connectDB()
	{
		//$setParameters();	//Setting up parameters
		$host='localhost:3306';
		$user='root';
		$pass='123';
		$db='elgo';		
		
		/*
		//Web
		$this->host='localhost';
		$this->user='livetips';
		$this->pass='Gwalior_123!';
		//$this->pass='';
		$this->db='livetips_elgo';
		*/
		$conn=mysqli_connect($host,$user,$pass,$db) or die(mysqli_connect_error());
		if(!$conn)
		{
			
			mysqli_select_db($conn)|| die(mysqli_connect_error());
		}
	}
}
?>