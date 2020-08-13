<?php
session_start();
// Create connection
$id1= $_SESSION['id'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$mobile=$_SESSION['mobile'];
$city=$_SESSION['city'];
$connection = new mysqli("localhost", "root", "","western_union");
	if (isset($_POST['motp'])&&isset($_POST['eotp']) )
	{
        $motp = $_POST['motp'];
		$eotp = $_POST['eotp'];
		//$p=array();
        $query = "Select * from otp where (motp='$motp' and eotp='$eotp' and uid=$id1)";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result)>0)
		{
			$query1 = "INSERT into custmer (name,email,mobile,city)value ('$name','$email','$mobile','$city')";
			

			if ($connection->query($query1) === TRUE) {
				$id=$connection -> insert_id;
				$query1 = "INSERT into account (cid,amount)value ($id,0)";
				$_SESSION["cid"]=$id;
				if ($connection->query($query1) === TRUE) {
				header('location: code.php');}
			} else {
				header('location: index.php');
			}

			
		}
		
    }

?>