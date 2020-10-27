// fade out
function fadeOut(el) {
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .1) < 0) {
      el.style.display = "none";
    }
    else {
      requestAnimationFrame(fade);
    }
  })();
}

// fade in
function fadeIn(el, display) {
  el.style.opacity = 0;

  (function fade() {
    var val = parseFloat(el.style.opacity);
    if (!((val += .1) > 1)) {
      el.style.opacity = val;
      requestAnimationFrame(fade);
    }
    else {
      el.style.display = display;
    }
  })();
}

// Wait for window load
function winLoad(callback) {
  if (document.readyState === 'complete') {
    callback();
  }
  else {
    window.addEventListener("load", callback);
  }
}

// Add opacity class when loaded
winLoad(function () {
  pageLoading(false);
});

function pageLoading(loading) {
  var loadingScreen = document.getElementById("loadingScreen");

  if (loading) {
    fadeIn(loadingScreen, 'flex');
  }
  else {
    fadeOut(loadingScreen);
  }
}

function linkLoading(loading) {
  var spinner = document.getElementById("linkSpinner");

  if (loading) {
    spinner.style.display = "flex";
  }
  else {
    spinner.style.display = "none";
  }
}
