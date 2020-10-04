<?php
  $conn = mysqli_connect('ip', 'user', 'pass', 'db');
	
  if (!$conn){
    die("Database Connection Failed");
  }
?>
