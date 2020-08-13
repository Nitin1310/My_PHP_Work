<?php
$sql='SELECT * FROM autocall';
if(!empty ($sql))
{
	
	$conn = mysqli_connect('localhost','root','123','elgo');
	$sql='SELECT * FROM autocall';
	$r=mysqli_query($conn,$sql);
	if(mysqli_num_rows($r)>0){
	
		while($row = mysqli_fetch_assoc($r)){
	
		echo "ID : {$row['id']} <br> ".
		 "Sname:{$row['sname']} <br> ".
		 "--------------------------------<br>"; 
		}
	}
	else{  
	echo "0 results";  
	}  
	mysqli_close($conn);
	
}























/*$conn = new mysqli_connect("locahost:3306","root","123","elgo");
$sql='SELECT * FROM autocall';
$r=mysqli_query($conn,$sql);
if(mysqli_num_rows($r)>0){
	
while($row = mysqli_fetch_assoc($r)){
	
	echo "ID : {$row['id']} <br> ".
		 "Sname:{$row['sname']} <br> ".
		 "--------------------------------<br>"; 
	}
}
else{  
echo "0 results";  
}*/
//mysqli_close($conn,$sql); 
?>