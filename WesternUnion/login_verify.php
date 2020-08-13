<?php
session_start();
// Create connection
$id1= $_SESSION['id'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$mobile=$_SESSION['mobile'];
$city=$_SESSION['city'];
$connection = new mysqli("localhost", "root", " ","western_union");
	if (isset($_POST['mob'])&&isset($_POST['pass']) )
	{
        $mob = $_POST['mob'];
		$pass = $_POST['pass'];
		//$p=array();
        $query = "Select * from custmer where (mobile='$mob' and passscode='$pass')";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result)>0)
		{
			$sql="SELECT * from uid where uidai='$uid'";
			$r=mysqli_query($connection, $sql);
			if(mysqli_num_rows($r)>0)
			{
				$d1=mysqli_fetch_assoc($r);
				
			}
			$_SESSION['cid']= $d1[id];
			
				header('location: transfer.php');
		}
		
    }

?>