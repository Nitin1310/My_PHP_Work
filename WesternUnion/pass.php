<?php
session_start();
// Create connection
$connection = new mysqli("localhost", "root", "","western_union");
	if (isset($_POST['pass']) )
	{
        if(strlen($_POST['pass'])<6)
		{
			header('location: code.php');
		}
		else{
			$cid=$_SESSION["cid"];
			$query1 = "update custmer set pass_code=$_POST['pass'] where cid= $cid";
			if ($connection->query($query1) === TRUE) {
				
				header('location: transaction.php');
			} else {
				header('location: code.php');
			}
		}
		$p=array();
        $query = "Select * from uid where uidai=$uid";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result)>0)
		{
			while($d=mysqli_fetch_assoc($result))
			{
				$p[]=$d;
			}
			
			
			$sql="SELECT * from uid where uidai='$uid'";
			$r=mysqli_query($connection, $sql) ;
			if(mysqli_num_rows($r)>0)
			{
				$d1=mysqli_fetch_assoc($r);
				
			}
			
			echo "$d1[id]";
			$_SESSION["id"]=$d1[id];
			$_SESSION["name"]=$d1[name];
			$_SESSION["email"]=$d1[email];
			$_SESSION["mobile"]=$d1[mob];
			$_SESSION["city"]=$d1[city];
			$check = "Select * from custmer where(mobile=$d1[mob])";
			$t=mysqli_query($connection, $check) ;
			if(mysqli_num_rows($t)>0)
			{
				header('location:index.php');
				
			}
			$randomid = mt_rand(100000,999999);
			$randomid1 = mt_rand(100000,999999);
			// echo $randomid;
			// echo $randomid1; 
			$query1 = "INSERT into otp (uid,motp,eotp)value ($d1[id],$randomid,$randomid1)";
			if ($connection->query($query1) === TRUE) {
				
				header('location: verify.php');
			} else {
				header('location: register.php');
			}

			//mysqli_free_result($result);
		}
		//return $p.
		echo "success";
    }

?>