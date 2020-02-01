async function shortenLink(link, expiry){
  // Set query
  var query = { 'link':link, 'expiry':expiry };

  var response = await fetch('https://l.sbond.co/shortenLink.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(query)
  });

  return await response.json();
}

async function getShortened(){
  pageLoading(true);

  // Get form info
  var link          = document.getElementById("linkInput").value;
  var expiry        = document.getElementById("expiryDateSelect").value;
  var linkGenerated = document.getElementById("linkGenerated");
  var linkSvg       = document.getElementById("linkSvg");

  // Get link
  var linkKey       = await shortenLink(link, expiry);
  linkKey           = linkKey['linkKey'];
  var shortenedLink = "l.sbond.co/?k=" + linkKey;

  // Display link and svg
  linkGenerated.innerHTML = "";
  linkGenerated.classList.remove("hidden");
  linkSvg.classList.remove("hidden");
  linkGenerated.insertAdjacentHTML('afterbegin', shortenedLink);

  pageLoading(false);
}
