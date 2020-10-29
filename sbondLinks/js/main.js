var linkInput = document.getElementById("linkInput");
var expiryDateSelect = document.getElementById("expiryDateSelect");
var clickToCopy = document.getElementById("clickToCopy");
var clickToCopyWrapper = document.getElementById("clickToCopyWrapper");
var clipboard = new ClipboardJS('#linkGenerated');

clipboard.on('success', () => {
  clickToCopy.innerText = "copied!";
});

clipboard.on('error', (e) => {
  // Tell user to press control + c if copy failed
	clickToCopy.innerText = "press ctrl+c";
});

async function shortenLink(link, expiry) {
  // Set query
  let query = { 'link': link, 'expiry': expiry };

  let response = await fetch('/shortenLink.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(query)
  });

  return await response.json();
}

function validateForm(link) {	
	// link regex
	let p = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
	
	let linkIsValid = p.test(link);
	let expiryIsValid = expiryDateSelect.value != "";
	
	// Link
	if (linkIsValid) {
		linkInput.classList.remove("incorrect");
		linkInput.classList.add("correct");
	}
	else {
		linkInput.classList.remove("correct");
		linkInput.classList.add("incorrect");
	}
	
	// Expiry
	if (expiryIsValid) {
		expiryDateSelect.classList.remove("incorrect");
		expiryDateSelect.classList.add("correct");
	}
	else {
		expiryDateSelect.classList.remove("correct");
		expiryDateSelect.classList.add("incorrect");
	}
	
	// Return true if link and expiry is valid
	if (linkIsValid && expiryIsValid) {
		return true;
	}
	else {
		return false;
	}
}

async function getShortened() {
  // Elements
  let linkGenerated = document.getElementById("linkGenerated");

  // Hide link related elements
  linkGenerated.classList.add("hidden");

  linkLoading(true);

  // Get form info
  let link = linkInput.value;
  let expiry = expiryDateSelect.value;
	
	// Validate and handle form
	let valid = validateForm(link);
	
	if (valid) {
		// Get link
		let linkKey = await shortenLink(link, expiry);
		linkKey = linkKey['linkKey'];
		let shortenedLink = "https://l.sbond.co/?k=" + linkKey;
		
		// Display link and svg
		linkGenerated.innerHTML = "";
		linkGenerated.classList.remove("hidden");
		linkGenerated.insertAdjacentHTML('afterbegin', shortenedLink);
	}

  linkLoading(false);
}

document.getElementById("shortenBtn").onclick = getShortened;

document.getElementById("linkGenerated").addEventListener("mouseover", () => {
  clickToCopyWrapper.style.opacity = 1;
  clickToCopyWrapper.style.top = "-33px";
});

document.getElementById("linkGenerated").addEventListener("mouseout", async () => {
  clickToCopyWrapper.style.opacity = 0;
  clickToCopyWrapper.style.top = "-28px";

  // Wait until after transition on above styling
  await new Promise(r => setTimeout(r, 250));

  // Reset text in clickToCopy element
  clickToCopy.innerHTML = "click to copy";
});
