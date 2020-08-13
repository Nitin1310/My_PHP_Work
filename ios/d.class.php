<?php

class d
{
	public function __construct()
	{
		date_default_timezone_set('Asia/kolkata');
		$this->connect();
	}
	
	public function connect()
	{
		$host = 'locahost';
		$user = 'root';
		$pass = '123';
		$db = 'elgo';
		$conn ='';
		$conn = mysqli_connect('localhost','root','123','elgo');
		mysqli_select_db($conn,$db) || die(mysqli_connection_error());	
	}
	
	
}echo "Database Connected";
?>