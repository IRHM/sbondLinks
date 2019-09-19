<?php
  $conn = mysqli_connect('localhost', 'sbondLinks', 'MuWELum4veQ721GI74GAbAJE2iC8xe', "sbondLinks");
  if (!$conn){
      die("Database Connection Failed" . mysqli_error($conn));
  }
?>
