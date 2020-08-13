<?php
class view
{
	public function __construct()
	{
		date_default_timezone_set('Asia/Kolkata');
		require_once('connection.class.php');
		$c=new connection();
		$this->make();
	}
	// making a view of table  
	private function make()
	
	{
		$sql="SELECT e_user_login.cid,e_user.name,e_user.mobile,e_user.email,e_user_login.passw,e_user.ip,e_user.dt FROM e_user , e_user_login ";
		$n=mysqli_query($conn,$sql) or die(mysqli_connect_error());
		
		if(mysqli_num_rows($n)>0)
		{
			while($j=mysqli_fetch_array($n))
			{
				$result[]=$j[];
				return $result[];
			}
		}
	}
	
}
?>