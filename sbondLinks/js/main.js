async function shortenLink(){
  // Get input info
  var link = document.getElementById("linkInput").value;
  var expiry = document.getElementById("expiryDateSelect").value;
  var query = { 'link':link, 'expiry':expiry };

  var response = await fetch('https://l.sbond.co/shortenLink.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(query)
  });

  console.log(JSON.stringify(response));
  // return await response.json();
}
