<?php
	$conn = mysqli_connect('localhost','root',' ','iot');
	if(mysqli_connect_errno())
	{
		echo"MySqli Connection was not established:" . mysqli_connect_error();
	}
	else
	{			
		if(isset($_POST['submit']))
		{
			$response=array();
			date_default_timezone_set('Asia/Kolkata');
			require_once ('smrt.class.php');
			$i=new smrt();
			$edate='';
			$d=strtoupper(date('l',strtotime($sdate)));
			
			$pname=mysqli_real_escape_string($conn,$_POST['pname']);
			$oname=mysqli_real_escape_string($conn,$_POST['oname']);
			$email=mysqli_real_escape_string($conn,$_POST['email']);
			$pwd=mysqli_real_escape_string($conn,$_POST['pwd']);
			$phno=mysqli_real_escape_string($conn,$_POST['phno']);

			$t2="INSERT INTO parking_zone (pname ,oname ,email ,password,phno,reg_date) VALUES ($pname,$oname,$email,$pwd,$phno,$d)";
				mysqli_query($t2) OR die(mysqli_connect_error());
		
		}
	}
?>
