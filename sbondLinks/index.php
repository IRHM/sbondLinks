<?php
$notice = "";

if (isset($_GET["k"]) && $link_key = $_GET["k"]) {
  // use link_key to get link and then redirect to the link
  require("dbConnect.php");

  // Declare vars that may not be later, but are needed
  $link = null;

  $query = "SELECT link FROM links WHERE link_key=?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $query)) {
    echo "Request failed. Please try again.";
    exit();
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $link_key);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
      $link = $row["link"];
    }
  }

  // Only redirect if $link is a valid url
  if (filter_var($link, FILTER_VALIDATE_URL)) {
    header("location: $link", TRUE, 301);
    exit;
  }
  else {
    $notice = "SHORTENED URL IS INVALID.";
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include("head.php"); ?>
  </head>
  <body>
    <script type="text/javascript" src="js/loading.js?v=3"></script>
    <div id="loadingScreen"></div>

    <div class="logoBackground">
      <h1 class="logo">sbondLinks</h1>
    </div>

    <div class="shortenLink">
      <div class="shortenLinkForm">
        <div class="linkGeneratedContainer">
          <div class="linkGeneratedWrapper">
            <div class="clickToCopyWrapper" id="clickToCopyWrapper">
              <div class="clickToCopy">
                click to copy
              </div>
            </div>

            <div id="linkGenerated" class="" data-clipboard-target="#linkGenerated"><?= $notice ?></div>
          </div>

          <div id="linkSpinner" class="spinner hidden"></div>
        </div>

        <div class="formContainer">
          <input id="linkInput" class="boxShadow" type="text" placeholder="Enter Link" autocomplete="off" name="linkToShorten">

          <select id="expiryDateSelect" class="boxShadow" name="expiryDate" required>
            <option value="" disabled selected invalid>Link Expiry &#x25BE;</option>
            <option value="A Day">A Day</option>
            <option value="A Week">A Week</option>
            <option value="A Month">A Month</option>
            <option value="Never">Never</option>
          </select>

          <button id="shortenBtn" class="boxShadow">
            SHORTEN
          </button>
        </div>
      </div>
    </div>

    <div id="notificationsContainer"></div>
  </body>
</html>