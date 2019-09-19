<?php
  require('dbConnect.php');

  function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  if (isset($_POST['submit'])) {
    // Get Link To Shorten from form
    $linkToShorten = $_POST['linkToShorten'];

    // SQL Query
    $sql = "SELECT link_key, link FROM links where link=?";

    // Execute SQL Query
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo 'SQL statement failed.';
    }
    else{
      mysqli_stmt_bind_param($stmt, "s", $linkToShorten);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      while($row = mysqli_fetch_assoc($result)){
        $link_key = $row['link_key'];
      }

      $rowsSelected = mysqli_num_rows($result);
    }

    if($rowsSelected > 0){
      /* use sql query above to find out if the requested url
      *  has already been shortened before.
      *  If it has.. just get the same url & key.
      */
      header("location: index.php?l=$link_key");
    }
    else{
      // if link hasn't been shortened before then run this code (to shorten):

      $link_key = generateRandomString(); // generate random string for the key

      $http     = 'http://';
      $https    = 'https://';
      $hasHttp  = strpos($linkToShorten, $http);
      $hasHttps = strpos($linkToShorten, $https);

      if ($hasHttp !== false || $hasHttps !==false) {
           $linkToShorten = "$linkToShorten";
      }
      else {
           $linkToShorten = "http://$linkToShorten";
      }

      // check and set expiry date
      if(isset($_POST['expiryDate'])){
          $expiryDate = $_POST['expiryDate'];
          switch ($expiryDate) {
              case 'A Day':
                $expire = "A Day";
                break;
              case 'A Week':
                $expire = "A Week";
                break;
              case 'A Month':
                $expire = "A Month";
                break;
              case 'Never':
                $expire = "Never";
                break;
              default:
                $expire = "A Week";
                break;
          }
      }

      // Insert link_key and link into table
      $sql = "INSERT INTO links (link_key, link, expire) value(?, ?, ?)";

      // execute sql query
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
        echo 'There was an error shortening the link.';
      }
      else{
        mysqli_stmt_bind_param($stmt, "sss", $link_key, $linkToShorten, $expire);
        mysqli_stmt_execute($stmt);
      }
      // go to index.php with shortened link
      header("location: index.php?l=$link_key");
    }
    // Close Connection
    mysqli_close($conn);
  }
?>
