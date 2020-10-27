<?php
  require('dbConnect.php');

  // Get JSON from POST request
  $JSON = @file_get_contents('php://input');

  // If no JSON then exit
  if (!is_object(json_decode($JSON))) {
    echo json_encode(array('error' => 'invalid request'));
    exit();
  }

  // Decode JSON
  $linkArgs = json_decode($JSON, true);

  function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  if (isset($linkArgs['link'])) {
    // Get Link To Shorten from form
    $linkToShorten = $linkArgs['link'];

    // Add http:// if not already
    $http     = 'http://';
    $https    = 'https://';
    $hasHttp  = strpos($linkToShorten, $http);
    $hasHttps = strpos($linkToShorten, $https);

    if ($hasHttp !== false || $hasHttps !== false) {
      $linkToShorten = "$linkToShorten";
    }
    else {
      $linkToShorten = "http://$linkToShorten";
    }
		
		// check and set expiry date
    if (isset($linkArgs['expiry']) && !empty($linkArgs['expiry'])) {
      $expiryDate = $linkArgs['expiry'];

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
    else {
      // Set expire to 'A Week' encase expiry isn't set
      $expire = "A Week";
    }

    // SQL Query
    $query = "SELECT link_key, link FROM links where link=? AND expire=? LIMIT 1";

    // Execute SQL Query
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $query)) {
      echo json_encode(array('error' => 'backend error'));
    }
    else {
      mysqli_stmt_bind_param($stmt, "ss", $linkToShorten, $expire);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      while ($row = mysqli_fetch_assoc($result)) {
        $link_key = $row['link_key'];
      }

      $rowsSelected = mysqli_num_rows($result);
    }

    if ($rowsSelected > 0) {
      /**
       * use sql query above to find out if the requested url
       * has already been shortened before.
       * If it has.. just get the same url & key to avoid duplication.
       */
      echo json_encode(array('linkKey' => $link_key));
    }
    else {
      // if link hasn't been shortened before then run this code (to shorten):

      $link_key = generateRandomString(); // generate random string for the key

      // Insert link_key and link into table
      $sql = "INSERT INTO links (link_key, link, expire) value(?, ?, ?)";

      // execute sql query
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(array('error' => 'backend error'));
      }
      else {
        mysqli_stmt_bind_param($stmt, "sss", $link_key, $linkToShorten, $expire);
        mysqli_stmt_execute($stmt);
      }

      // Return links key
      echo json_encode(array('linkKey' => $link_key));
    }

    // Close Connection
    mysqli_close($conn);
  }
?>
