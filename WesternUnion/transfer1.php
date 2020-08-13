<?php
session_start();
 $cid= $_SESSION['cid'];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Transfer Money</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
table {
border-collapse: collapse;
width: 100%;
color: #588c7e;
font-family: monospace;
font-size: 25px;
text-align: left;
}
th {
background-color: #fc0b03;
color: white;
}
tr:nth-child(even) {background-color: #f2f2f2}
</style>


</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-red w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a>
    <a href="register.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Send Money</a>
    <a href="transaction.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Transaction</a>
    <a href="RegLog.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Login||Register</a>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="index.php" class="w3-bar-item w3-button w3-padding-large">Home</a>
    <a href="RegLog.php" class="w3-bar-item w3-button w3-padding-large">Send Money</a>
    <a href="transaction.php" class="w3-bar-item w3-button w3-padding-large">Transaction</a>
    <a href="RegLog.php" class="w3-bar-item w3-button w3-padding-large">Login||Register</a>
  </div>
</div>

<!-- Header -->
<header class="w3-container w3-red w3-center" style="padding:128px 16px">
  <h1 class="w3-margin w3-jumbo">Digital money transfers</h1>
</header>



<!-- Contact -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Transaction Details</b></h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
      <div class="w3-section">
        <label>Enter Account Number</label>
        <input class="w3-input w3-border" type="text" name="accNumb" required>
      </div>
      <button type="submit" name="btnshow" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Show</button>
      <button type="submit" name="history" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">History</button>
    </form>  
  </div>
<table>
<tr>
<th>Account Number</th>
<th>Amount</th>
</tr>
<?php

if(isset($_POST['btnshow']))
{
      $AccNum=$_POST['accNumb'];
      $conn = mysqli_connect("localhost", "root", "", "western_union");
      // Check connection
      if ($conn->connect_error) 
      {
        die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT * FROM acount where cid = $cid";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) 
      {
      // output data of each row
      while($row = $result->fetch_assoc()) 
      {
      echo "<tr><td>" . $row["AccNumb"]. "</td><td>" . $row["Amt"] . "</td><td>"
      ."</td></tr>";
      }
      echo "</table>";
      } else { echo "0 results"; }
      $conn->close();

}
if(isset($_POST['history']))
{
      $AccNum=$_POST['accNumb'];
      $conn = mysqli_connect("localhost", "root", "", "western_union");
      // Check connection
      if ($conn->connect_error) 
      {
        die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT *  FROM transactions where aid=(select id from account where cid= $cid)";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) 
      {
      // output data of each row
      while($row = $result->fetch_assoc()) 
      {
      echo "<tr><td>" . $row["tid"]. "</td><td>" . $row["amount"] . "</td><td>". $row["cr_dr"] 
      ."</td><td>". $row["dt"] 
      ."</td></tr>";
      }
      echo "</table>";
      } else { echo "0 results"; }
      $conn->close();

}

?>

</table>
<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
  <div class="w3-xlarge w3-padding-32">
    <i class="fa fa-facebook-official w3-hover-opacity"></i>
    <i class="fa fa-instagram w3-hover-opacity"></i>
    <i class="fa fa-snapchat w3-hover-opacity"></i>
    <i class="fa fa-pinterest-p w3-hover-opacity"></i>
    <i class="fa fa-twitter w3-hover-opacity"></i>
    <i class="fa fa-linkedin w3-hover-opacity"></i>
 </div>
 </footer>

</body>
</html>

