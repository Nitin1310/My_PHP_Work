<?php

public class d
	public function __construct()
	{
		date_default_timezone_set('Asia/kolkata');
		$this->connect();
	}
	
	public function connect()
	{
		$host = 'locahost';
		$user = 'root';
		$pass = ' ';
		$db = 'iot';
		$conn ='';
		$conn = mysqli_connect('localhost','root',' ','iot');
		mysqli_select_db($conn,$db) || die(mysqli_connection_error());	
	}
	
	
}echo "Database Connected";
?>