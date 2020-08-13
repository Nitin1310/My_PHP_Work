<?php
class m1
{
	public function __construct()
	{
		ini_set('max_execution_time',0);
		set_time_limit(0);
		session_start();							//Initiating Session	
		date_default_timezone_set('Asia/Kolkata');	//Setting Timezone	
		$this->disable_old_sessions();		//Disabled old sessions
		$this->disable_expired_services();
		$conn = mysqli_connect('localhost','root','123','elgo');    // Connection with Database
	}
	
	public function __destruct(){}
	
	//For Valid Mobile Number
	public function validMobile($s)
	{
		if(preg_match('#^[7-9]([0-9]){9}$#',$s))
		return true;
		else
		return false;
	}
	//FUNCTION TO GET BG
	public function getbg($s)
	{
		$today=date('Y-m-d');
		
		$sql="SELECT * FROM callcolor WHERE edate='$today' AND sname='$s' LIMIT 0,1";
		$r=mysqli_query($conn,$sql) OR die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			$k=mysqli_fetch_array($r);
			return $k['color'];
		}
		else
		return '#FFFFFF';
	}
	//FUNCTION TO GET VAL 
	function get_val($table,$col,$val,$req)
	{
		$sql="SELECT * FROM $table WHERE $col=$val";
		$r=mysqli_query($conn,$sql) OR die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			$d=mysqli_fetch_array($r);
			return $d[$req];
		}
		else
		return 0;
		mysqli_close($conn,$r);
	}
	
}