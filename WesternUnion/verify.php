<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<title>Transfer Money Easy Way</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<style>
body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
body {font-size:16px;}
.w3-half img{margin-bottom:-6px;margin-top:16px;opacity:0.8;cursor:pointer}
.w3-half img:hover{opacity:1}
</style>
<body>

 <!-- Contact -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Register</b></h1>
    <form action="verification.php" target="_blank" method="post">
      <div class="w3-section">
        <label>Enter Mobile OTP</label>
        <input class="w3-input w3-border" type="text" name="motp" required>
      </div>
      <div class="w3-section">
        <label>Enter Email OTP</label>
        <input class="w3-input w3-border" type="text" name="eotp" required>
      </div>
      <button type="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Send OTP</button>
    </form>  
  </div>

</body>
</html>
