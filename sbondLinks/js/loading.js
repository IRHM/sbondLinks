// fadeOut then display: none
function fadeOut(e){
  e.style.opacity = 1;

  (function fade() {
    if ((e.style.opacity -= .1) < 0) {
      e.style.display = "none";
    }
    else {
      requestAnimationFrame(fade);
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
winLoad(function() {
  // console.log('Window is loaded');
  var e = document.getElementById("loadingScreen");
  fadeOut(e);
});
