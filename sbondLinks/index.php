<?php
  if(isset($_GET['k']) && $link_key = $_GET['k']){
    // use link_key to get link and then redirect to the link
    require('dbConnect.php');
    $sql = "SELECT link FROM links WHERE link_key='$link_key'";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result)){
      $link = $row['link'];
    }

    // Redirect to link
    header("location: $link", TRUE, 301);
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include('head.php'); ?>
  </head>

  <body>
    <script type="text/javascript" src="js/loading.js"></script>
    <div id="loadingScreen"></div>

    <center>
      <div class="logoBackground">
        <h1 class="logo">sbondLinks</h1>
      </div>

      <div class="shortenLink">
        <div class="shortenLinkForm">
          <div class="linkGeneratedContainer">
            <span id="linkGenerated" class="hidden"
                  data-clipboard-target="#linkGenerated">
            </span>
            <svg id="linkSvg" class='hidden' aria-hidden='true' focusable='false'
              data-prefix='fas' data-icon='copy'
              role='img' xmlns='http://www.w3.org/2000/svg'
              viewBox='0 0 448 512' width='15px' height='15px'>
              <path fill='currentColor'
              d='M320 448v40c0 13.255-10.745 24-24 24H24c-13.255
              0-24-10.745-24-24V120c0-13.255 10.745-24 24-24h72v296c0
              30.879 25.121 56 56 56h168zm0-344V0H152c-13.255 0-24
              10.745-24 24v368c0 13.255 10.745 24 24 24h272c13.255
              0 24-10.745 24-24V128H344c-13.2
              0-24-10.8-24-24zm120.971-31.029L375.029 7.029A24 24 0 0 0 358.059
              0H352v96h96v-6.059a24 24 0 0 0-7.029-16.97z'></path>
            </svg>
          </div>
          <div class="formContainer">
            <input id="linkInput" class="boxShadow" type="text" placeholder="Enter Link" autocomplete="off"
              name="linkToShorten" required>

            <select id="expiryDateSelect" class="boxShadow" name="expiryDate" required>
              <option class="" value="" disabled selected invalid>Select Expiry Date ⇣</option>
              <option value="A Day">A Day</option>
              <option value="A Week">A Week</option>
              <option value="A Month">A Month</option>
              <option value="Never">Never</option>
            </select>

            <button onclick="getShortened();">
              Shorten
            </button>
          </div>
        </div>
      </div>
    </center>

    <script type="text/javascript" src="js/main.js"></script>
    <!-- Clipboard.js -->
    <script type="text/javascript" src="js/clipboard.min.js"></script>
    <script type="text/javascript">
      var clipboard = new ClipboardJS('#linkGenerated');
      clipboard.on('success', function(e) {
          // console.log(e);
      });
      clipboard.on('error', function(e) {
          console.log(e);
      });
    </script>

  </body>
</html>
