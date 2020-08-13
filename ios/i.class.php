<?php
class i
{
	public function __construct()
	{
		date_default_timezone_set('Asia/Kolkata');
		require_once('connection.class.php');
		$c=new connection();
	}
	public function chekMobile($m)
	{
		if(preg_match('#^[7-9]([0-9]){9}$#',$s))
		return true;
		else
		return false;
	}
	function chekEmail($email) 
	{		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)===false)
		return true;
		else
		return false;
	}
	function chek_existence($table,$col,$val)
	{
		$sql="SELECT *FROM &table WHERE $col=$val";
		$r=mysqli_query($conn,$sql) or die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
?>