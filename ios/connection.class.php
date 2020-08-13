<?php
class connection
{
	public function __construct()
	{
		date_default_timezone_set('Asia/Kolkata');
		$this->connect();
	}
	private function connect()
	{
		$conn=mysqli_connect("localhost","root","123","elgo");
	}
	
}
?>