<?php
	$conn = mysqli_connect("localhost","root","123","elgo");
	if(mysqli_connect_errno())
	{
		echo"MySqli Connection was not established:" . mysqli_connect_error();
	}
	else
	{			
		if(isset($_POST['GETDATA']))
		{
			$response=array();
			
			require_once ('i.class.php');
			$i=new i();
			require_once ('view.class.php');
			$v=new view();
			
			$user=mysqli_real_escape_string($conn,$_POST['user']);
			$pass=mysqli_real_escape_string($conn,$_POST['pass']);
			
			if($user=='NA' || $pass=='NA')
			{
				$response[]=array("code"=>"FAIL","message"=>"please enter username,password.");		
			}
			else
			{			
				if($i->chekMobile($user))							//match the formate fo mobile number give by user
				{
					
				}
			}	
		}
	}
?>	